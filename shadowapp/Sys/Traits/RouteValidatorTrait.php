<?php

namespace Shadowapp\Sys\Traits;

use Shadowapp\Sys\Traits\Support\ComprasionTrait;

trait RouteValidatorTrait
{

    use ComprasionTrait;

    protected static $allowedTypes = [
        'int', 'string'
    ];
    protected static $allowedOperators = [
        'moreThan' => '>',
        'lessThan' => '<',
        'equalTo' => '=',
        'notEqualTo' => '!'
    ];

    public static function validate(array $fields, array $routeWhere)
    {

        if (empty($fields)) {
            echo 'Params do not have to be empty';
            exit;
        }

        foreach ($fields as $pattern => $value) {
            self::validatePattern($pattern, $value, $routeWhere);
        }
    }

    private static function validatePattern($pattern, $value, $routeWhere)
    {
        $cleanPattern = self::cleanPattern($pattern);

        $patternArray = explode(':', $cleanPattern);

        if (count($patternArray) != 2) {

            if (!isset($routeWhere[$patternArray[0]]))
                return;

            self::validateRouteParam(trim($routeWhere[$patternArray[0]]), $value, $patternArray[0]);
            return;
        }

        $patternType = $patternArray[0];


        if (!in_array($patternType, self::$allowedTypes)) {
            $message = $patternType . ' is not correct pattern type. allowed types are ' . implode(', ', self::$allowedTypes);

            throw new \Shadowapp\Sys\Exceptions\Routing\WrongUriParameterException($message);
        }


        switch ($patternType) {
            case 'string':
                if (!ctype_alpha($value)) {
                    $message = $patternArray[1] . " have to be " . $patternType;
                    throw new \Shadowapp\Sys\Exceptions\Routing\WrongUriParameterException($message);
                }
                break;

            case 'int':
                if (!is_numeric($value)) {
                    $message = $patternArray[1] . " have to be " . $patternType;

                    throw new \Shadowapp\Sys\Exceptions\Routing\WrongUriParameterException($message);
                }
                break;
        }

        if (!isset($routeWhere[$patternArray[1]]))
            return;
        self::validateRouteParam(trim($routeWhere[$patternArray[1]]), $value, $patternArray[1]);
    }

    protected static function validateRouteParam($toValidate, $value, $variable)
    {
        $validationOperator = substr($toValidate, 0, 1);

        if (!in_array($validationOperator, self::$allowedOperators)) {
            return;
        }

        $toCheck = trim(substr($toValidate, 1));

        if (empty($toCheck))
            return;

        $comprasionMethod = shcol($validationOperator, array_flip(self::$allowedOperators));

        if (false === self::$comprasionMethod($toCheck, $value)) {
            $correctComprasionString = self::splitAtUpperCase($comprasionMethod);
            $message = $variable . ' have to be ' . $correctComprasionString . ' ' . $toCheck;

            throw new \Shadowapp\Sys\Exceptions\Routing\WrongUriParameterException($message);
        }
    }

    private static function splitAtUpperCase(string $string)
    {
        $splitted = preg_split('/(?=[A-Z])/', $string, -1, PREG_SPLIT_NO_EMPTY);

        if (!count($splitted))
            return '';

        $lowered = array_map(function ($k) {
            return strtolower($k);
        }, $splitted);

        return implode(' ', $lowered);
    }

    protected static function containsBraces($endArr)
    {
        $contains = false;

        foreach ($endArr as $endp) {

            if (self::stringContainsBraces($endp)) {
                $contains = true;
                break;
            }
        }

        return $contains;
    }

    protected static function stringContainsBraces($endp)
    {
        if (empty($endp)) {
            return false;
        }

        return ($endp[0] == '{' && $endp[strlen($endp) - 1] == '}');
    }

    private static function cleanPattern($pattern)
    {
        if (empty($pattern) && !is_string($pattern)) {
            return false;
        }

        if (!self::stringContainsBraces($pattern)) {
            return false;
        }

        return substr($pattern, 1, strlen($pattern) - 2);
    }

}

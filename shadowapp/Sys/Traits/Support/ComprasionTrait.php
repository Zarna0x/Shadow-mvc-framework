<?php

namespace Shadowapp\Sys\Traits\Support;


trait ComprasionTrait
{
	public static function moreThan($toCheck,$value)
	{ 
		if (!is_numeric($toCheck) || !is_numeric($value)) {
           return false;
		}
        
        return ($value > $toCheck );	
	}
    public static function lessThan($toCheck,$value)
    { 
    	if (!is_numeric($toCheck) || !is_numeric($value)) {
           return false;
		}

    	return ($value < $toCheck); 
    }
	public static function equalTo($toCheck,$value)
	{
		return ($toCheck == $value);
	}
	public static function notEqualTo($toCheck,$value)
	{
		return ($toCheck != $value);
	}
}
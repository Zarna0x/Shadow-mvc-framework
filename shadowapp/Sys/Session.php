<?php

namespace Shadowapp\sys;

Class Session
{

    protected static $_flashData = [];

    /*
     * Set new session
     */

    public static function start($key, $data) {
        #session_start();
        if (isset($key) && !empty($data)) {
            $_SESSION[$key] = $data;
            return true;
        }

        return false;
    }

    /*
     * Get session
     */

    public static function get($key) {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }

        return null;
    }

    /*
     * destroy session
     */

    public static function smash() {
        session_destroy();
    }

    /*
     * Create Flash Session
     */

    public static function flashShadow($type, $message) {


        self::start($type, $message);
    }

    public static function flashOutput($type) {

        //
        switch ($type) {
            case 'error':
                echo "<div class='alert alert-danger'>" .
                self::get($type)
                . "</div>";
                break;




            case "success":

                echo "<div class='alert alert-success'>" .
                self::get($type)
                . "</div>";
                break;


            case "info":

                echo "<div class='alert alert-info'>" .
                self::get($type)
                . "</div>";
                break;


            case "warning":

                echo "<div class='alert alert-warning'>" .
                self::get($type)
                . "</div>";
                break;



            default:
                echo "<div>" .
                self::get($type)
                . "</div>";

                break;
        }

        unset($_SESSION[$type]);
    }

    public static function has($key) {
        if (isset($_SESSION[$key])) {
            return true;
        }

        return false;
    }

    public static function remove($key) {
        if ($_SESSION[$key]) {
            unset($_SESSION[$key]);
            return true;
        }

        return false;
    }

}

?>

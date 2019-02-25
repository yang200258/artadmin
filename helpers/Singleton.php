<?php

namespace app\helpers;

class Singleton
{
    public static function instance()
    {
        // Gets the name of the class the static method is called in
        $classname = get_called_class();
        if (!isset(self::$instance[$classname]))
        {
            self::$instance[$classname] = new $classname();
        }
        return self::$instance[$classname];
    }

    protected function __construct()
    {
    }

    protected function __clone()
    {
    }

    protected static $instance = [];
}
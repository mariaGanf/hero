<?php

/**
 * Class Autoloader
 */
class Autoloader
{
    /**
     * @param $class
     * @return bool
     */
    public static function loader($class)
    {
        $filename = strtolower($class) . '.php';
        $file = 'lib/' . $filename;
        if (!file_exists($file))
        {
            return false;
        }
        include $file;
    }
}
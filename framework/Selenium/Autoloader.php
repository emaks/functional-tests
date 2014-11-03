<?php
namespace Selenium;

class Autoloader
{
    /**
     * Registers the autoloader handler
     */
    public static function register()
    {
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    /**
     * Autoload handler implementation. Performs calling of class/interface, which has not been defined yet
     *
     * @param string $className Class name to be loaded, e.g. Mage_Selenium_TestCase
     *
     * @return boolean True if the class was loaded, otherwise False.
     */
    public static function autoload($className)
    {
        $classFile = str_replace(' ', DIRECTORY_SEPARATOR, ucwords(str_replace('_', ' ', $className)));
        $classFile = $classFile . '.php';
        $path = explode(PATH_SEPARATOR, ini_get('include_path'));
        foreach ($path as $possiblePath) {
            if (file_exists($possiblePath . DIRECTORY_SEPARATOR . $classFile)) {
                include_once $classFile;
                if (class_exists($className, false)) {
                    return true;
                }
            }
        }
        return false;
    }
}

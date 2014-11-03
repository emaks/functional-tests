<?php
namespace Selenium;

class TestConfiguration
{
    /**
     * Configuration object instance
     * @var TestConfiguration
     */
    private static $instance;

    /**
     * File helper instance
     * @var \Selenium\Helper\File
     */
    protected $fileHelper;

    /**
     * Config helper instance
     * @var \Selenium\Helper\Config
     */
    protected $configHelper;

    /**
     * Array of class names for test Page files
     * @var array
     */
    protected $pageNames = [];

    /**
     * @var string
     */
    protected $initialPath;

    /**
     * Get test configuration instance
     *
     * @static
     * @return TestConfiguration
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
            static::$instance->init();
        }
        return static::$instance;
    }

    public function init()
    {
        $this->setInitialPath(SELENIUM_TESTS_BASEDIR . DIRECTORY_SEPARATOR);
        $this->getHelper('config');
        $this->getPageNames();
    }

    /**
     * Set initial path
     *
     * @param string $path
     *
     * @return $this
     */
    public function setInitialPath($path)
    {
        $this->initialPath = $path;
        return $this;
    }

    /**
     * Retrieve initial path
     *
     * @return string
     */
    public function getInitialPath()
    {
        return $this->initialPath;
    }

    /**
     * Get $helperName helper instance
     *
     * @param string $helperName config|data|dataGenerator|file|params|uimap
     *
     * @return \Selenium\Helper\AbstractHelper
     * @throws \OutOfRangeException
     */
    public function getHelper($helperName)
    {
        $class = 'Selenium\Helper\\' . ucfirst($helperName);
        if (!class_exists($class)) {
            throw new \OutOfRangeException($class . ' does not exist');
        }
        $variableName = '_' . preg_replace('/^[A-Za-z]/', strtolower($helperName[0]), $helperName) . 'Helper';
        if (!isset($this->$variableName)) {
            $this->$variableName = new $class($this);
        }
        return $this->$variableName;
    }

    /**
     * Get all test helper class names
     * @return array
     */
    public function getPageNames()
    {
        if (!empty($this->pageNames)) {
            return $this->pageNames;
        }
        //Get initial path to test blocks
        $initialPath = $this->getInitialPath() . 'testsuite';

        $facade = new \File_Iterator_Facade();
        $files = $facade->getFilesAsArray($initialPath, '.php');
        foreach ($files as $file) {
            $className = str_replace($initialPath . DIRECTORY_SEPARATOR, '', str_replace('.php', '', $file));
            $array = explode(DIRECTORY_SEPARATOR, $className);
            if ($array[count($array) - 2] == 'Page') {
                $blockName = end($array);
                $this->pageNames[$blockName] = $className;
            }
        }
        return $this->pageNames;
    }

    /**
     * Get node|value by path
     *
     * @param array $data Array of Configuration|DataSet data
     * @param string $path XPath-like path to Configuration|DataSet value
     *
     * @return array|string|bool
     */
    public function _descend($data, $path)
    {
        $pathArr = (!empty($path)) ? explode('/', $path) : '';
        $currNode = $data;
        if (!empty($pathArr)) {
            foreach ($pathArr as $node) {
                if (isset($currNode[$node])) {
                    $currNode = $currNode[$node];
                } else {
                    return false;
                }
            }
        }
        return $currNode;
    }
}
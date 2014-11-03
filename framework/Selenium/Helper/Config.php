<?php
namespace Selenium\Helper;

class Config extends AbstractHelper
{
    /**
     * Configuration data
     * @var array
     */
    protected $configData = array();

    /**
     * Configuration data for browser
     * @var array
     */
    protected $browser = array();

    /**
     * Configuration data for framework
     * @var array
     */
    protected $framework = array();

    /**
     * Configuration data for application
     * @var array
     */
    protected $application = array();

    /**
     * Path to the screenshots directory
     * @var null|string
     */
    protected $screenshotDir;

    /**
     * Initialize config
     */
    protected function _init()
    {
        $this->_loadConfigData();
    }

    /**
     * Load Config Data
     * @return \Selenium\Helper\Config
     * @throws \OutOfRangeException
     */
    protected function _loadConfigData()
    {
        $file = 'config.yml';
        $configDir = implode(DIRECTORY_SEPARATOR, array(SELENIUM_TESTS_BASEDIR, 'config', $file));
        $fileData = $this->getConfigData()->getHelper('file')->loadYamlFile($configDir);
        if ($fileData) {
            $this->configData = $fileData;
            return $this;
        }
        throw new \OutOfRangeException('Configuration file does not exist');
    }

    /**
     * Get value from Configuration file
     *
     * @param string $path XPath-like path to config value (by default = '')
     *
     * @return array|string|bool
     */
    public function getConfigValue($path = '')
    {
        return $this->getConfigData()->_descend($this->configData, $path);
    }

    /**
     * Return config for framework
     * @return array
     */
    public function getFrameworkConfig()
    {
        if (empty($this->framework)) {
            $this->framework = $this->getConfigValue('config/framework');
        }
        return $this->framework;
    }

    /**
     * Get browser config
     * @return array
     */
    public function getBrowserConfig()
    {
        if (empty($this->browser)) {
            $this->browser = $this->getConfigValue('config/browser');
        }
        return $this->browser;
    }

    /**
     * Get application config
     * @return array
     */
    public function getApplicationConfig()
    {
        if (empty($this->application)) {
            $this->application = $this->getConfigValue('config/application');
        }
        return $this->application;
    }

    /**
     * Set path to the screenshot directory.
     * Creates a directory if it does not exist.
     *
     * @param string $dirPath
     *
     * @return $this
     * @throws \RuntimeException if the directory could not be created
     */
    public function setScreenshotDir($dirPath)
    {
        if (is_dir($dirPath) || mkdir($dirPath, 0777, true)) {
            $this->screenshotDir = $dirPath;
            return $this;
        }
        throw new \RuntimeException('Could not create directory "' . $dirPath . '"');
    }

    /**
     * Get path to the screenshot directory.
     * @return string
     */
    public function getScreenshotDir()
    {
        if (is_null($this->screenshotDir) && defined('SELENIUM_TESTS_SCREENSHOTDIR')) {
            $this->setScreenshotDir(SELENIUM_TESTS_SCREENSHOTDIR);
        }
        return $this->screenshotDir;
    }
}
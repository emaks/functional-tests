<?php
namespace Selenium;

class TestCase extends \PHPUnit_Extensions_Selenium2TestCase
{
    /**
     * @var array
     */
    public static $browsers = [];

    /**
     * Array of Test Page instances
     * @var array
     */
    protected static $testPages = [];

    /**
     * Framework setting
     * @var array
     */
    public $frameworkConfig;

    /**
     * @var bool
     */
    protected $saveScreenOnFail = false;

    /**
     * @var null
     */
    protected $screenshotPath;

    /**
     * Timeout in seconds
     * @var int
     */
    protected $browserTimeout = 30;
    /**
     * Configuration object instance
     * @var TestConfiguration
     */
    private $testConfig;
    /**
     * Config helper instance
     * @var \Selenium\Helper\Config
     */
    private $configHelper;

    /**
     * Constructs a test case with the given name and browser to test execution
     *
     * @param  string $name Test case name(by default = null)
     * @param  array $data Test case data array(by default = [])
     * @param  string $dataName Name of Data set(by default = '')
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->testConfig = TestConfiguration::getInstance();
        $this->configHelper = $this->testConfig->getHelper('config');
        $this->frameworkConfig = $this->configHelper->getFrameworkConfig();

        parent::__construct($name, $data, $dataName);

        $this->screenshotPath = $this->configHelper->getScreenshotDir();
        $this->saveScreenOnFail = $this->frameworkConfig['saveScreenshotOnFailure'];
        $this->browserTimeout = (isset($this->frameworkConfig['browserTimeoutPeriod']))
            ? $this->frameworkConfig['browserTimeoutPeriod']
            : $this->browserTimeout;
    }

    public final function setUp()
    {
        $this->prepareBrowserSession();
    }

    /**
     * Prepare browser session
     */
    public function prepareBrowserSession()
    {
        if (empty(self::$browsers)) {
            $browser = $this->configHelper->getBrowserConfig();
            $this->setupSpecificBrowser($browser);
        }
        $this->setBrowserUrl($this->getBaseUrl());
        $this->prepareSession();
        $this->currentWindow()->maximize();
    }

    public function getBaseUrl()
    {
        $application = $this->configHelper->getApplicationConfig();
        return $application['url'];
    }

    /**
     * Implementation of tearDownAfterAllTests() method in the object context, called as tearDownAfterTestClass()<br>
     * Used ONLY one time after execution of last test in test class
     * Implementation of tearDownAfterEachTest() method in the object context, called as tearDownAfterTest()<br>
     * Used after execution of each test in test class
     * @throws \Exception
     */
    public final function tearDown()
    {
        if ($this->hasFailed()) {
            if ($this->saveScreenOnFail) {
                $this->takeScreenshot();
            }
        }
        try {
            $this->tearDownAfterTest();
        } catch (\Exception $e) {
        }

        if (isset($e) && !$this->hasFailed()) {
            if ($this->saveScreenOnFail) {
                $this->takeScreenshot();
            }
        }
        if (isset($e)) {
            throw $e;
        }
    }

    /**
     * Take a screenshot and return information about it.
     * Return an empty string if the screenshotPath property is empty.
     *
     * @param null|string $fileName
     *
     * @return string
     */
    public function takeScreenshot($fileName = null)
    {
        $screenshotPath = $this->getScreenshotPath();
        if (empty($screenshotPath)) {
            $this->fail('Screenshot Path is empty');
        }

        if ($fileName == null) {
            $fileName = time() . '__' . str_replace('\\', '_', $this->getTestId());
            $fileName = str_replace('"', '\'', $fileName);
            $fileName = preg_replace('/ with data set #/', '__DataSet_', $fileName);
        }
        $filePath = $screenshotPath . $fileName . '.png';
        $screenshotContent = $this->currentScreenshot();
        $file = fopen($filePath, 'a+');
        fputs($file, $screenshotContent);
        fflush($file);
        fclose($file);
        if (file_exists($filePath)) {
            return 'Screenshot: ' . $filePath . "\n";
        }
        return '';
    }

    /**
     * Returns correct path to screenshot save path.
     *
     * @return string
     */
    public function getScreenshotPath()
    {
        $path = $this->screenshotPath;

        if (!in_array(substr($path, strlen($path) - 1, 1), array("/", "\\"))) {
            $path .= DIRECTORY_SEPARATOR;
        }

        return $path;
    }

    protected function tearDownAfterTest()
    {
    }

    /**
     * Retrieve instance of page
     *
     * @param  string $pageName
     *
     * @return \Selenium\Page
     */
    public function getPage($pageName)
    {
        if (isset(self::$testPages[$pageName])) {
            /** @var $page \Selenium\Page */
            $page = self::$testPages[$pageName];
            $page->setTestInstance($this);
            return $page;
        }

        $pages = $this->testConfig->getPageNames();
        $blockClass = $pages[$pageName];
        self::$testPages[$pageName] = new $blockClass($this);
        return self::$testPages[$pageName];
    }

    /**
     * @param string $locator
     * @param string $strategy
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function getElement($locator, $strategy = 'xpath')
    {
        return $this->element($this->using($strategy)->value($locator));
    }

    /**
     * @param string $locator
     * @param null|int $timeout
     * @throws \RuntimeException
     */
    public function waitForElementVisible($locator, $timeout = null)
    {
        if (is_null($timeout)) {
            $timeout = $this->browserTimeout;
        }
        $iStartTime = time();
        while ($timeout > time() - $iStartTime) {
            try {
                /** @var \PHPUnit_Extensions_Selenium2TestCase_Element $availableElement */
                foreach ($this->getElements($locator) as $availableElement) {
                    if ($availableElement->displayed()) {
                        return;
                    }
                }
                usleep(500000);
            } catch (\Exception $e) {
            }
        }
        throw new \RuntimeException(
            "Timeout after $timeout seconds\nElement is not visible or not present on the page.\nLocator: $locator"
        );
    }

    /**
     * @param string $locator
     * @param string $strategy
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element[]
     */
    public function getElements($locator, $strategy = 'xpath')
    {
        return $this->elements($this->using($strategy)->value($locator));
    }

    /**
     * @param string $locator
     * @return bool
     */
    public function isElementVisible($locator)
    {
        $elements = $this->getElements($locator);
        return !empty($elements) && $elements[0]->displayed();
    }
}

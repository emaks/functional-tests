<?php
namespace Selenium;

/**
 * @method \PHPUnit_Extensions_Selenium2TestCase_Element getElement(string $locator)
 * @method \PHPUnit_Extensions_Selenium2TestCase_Element[] getElements(string $locator)
 * @method bool isElementVisible(string $locator)
 * @method string getBaseUrl()
 * @method Page getPage()
 *
 * @method void fail(string $message)
 * @method string|null url($url = null)
 * @method string title()
 * @method assertSame($expected, $actual, $message = '')
 * @method assertTrue($condition, $message = '')
 */
abstract class Page
{
    protected $testInstance;

    public function __construct(\Selenium\TestCase $testObject)
    {
        $this->testInstance = $testObject;
        $this->_init();
    }

    protected function _init()
    {
        return $this;
    }

    public function __call($command, $arguments)
    {
        $className = get_class($this->testInstance);
        $reflectionClass = new \ReflectionClass($className);
        if ($reflectionClass->hasMethod($command)) {
            $reflectionMethod = new \ReflectionMethod($className, $command);

            return $reflectionMethod->invokeArgs($this->testInstance, $arguments);
        }

        return $this->testInstance->__call($command, $arguments);
    }

    public function setTestInstance(\Selenium\TestCase $testObject)
    {
        $this->testInstance = $testObject;
    }

    abstract public function openAndVerify();
}

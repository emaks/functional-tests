<?php
namespace Selenium\Helper;

class AbstractHelper
{
    /**
     * Test configuration object
     * @var \Selenium\TestConfiguration
     */
    protected $config;

    /**
     * Constructor expects global test configuration object
     *
     * @param \Selenium\TestConfiguration $config
     */
    public function __construct(\Selenium\TestConfiguration $config)
    {
        $this->config = $config;
        $this->_init();
    }

    /**
     * @return $this
     */
    protected function _init()
    {
        return $this;
    }

    /**
     * Return config
     * @return \Selenium\TestConfiguration
     */
    public function getConfigData()
    {
        return $this->config;
    }
}

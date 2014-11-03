<?php
define('SELENIUM_TESTS_BASEDIR', realpath(__DIR__ . '/..'));
define('SELENIUM_TESTS_SCREENSHOTDIR', realpath(SELENIUM_TESTS_BASEDIR . '/var/screenshots'));
define('SELENIUM_TESTS_LOGS', realpath(SELENIUM_TESTS_BASEDIR . '/var/logs'));

set_include_path(
    implode(
        PATH_SEPARATOR,
        array(
            realpath(SELENIUM_TESTS_BASEDIR . '/framework'),
            realpath(SELENIUM_TESTS_BASEDIR . '/testsuite'),
            get_include_path(),
        )
    )
);

require_once realpath(SELENIUM_TESTS_BASEDIR . '/vendor/autoload.php');
require_once realpath(SELENIUM_TESTS_BASEDIR . '/framework/Selenium/Autoloader.php');
\Selenium\Autoloader::register();
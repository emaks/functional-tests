<?php
namespace Devmate\Tests;

class DevmateTest extends \Selenium\TestCase
{
    /**
     * @test
     */
    public function scenario1()
    {
        /** @var \Devmate\Page\Main $mainPage */
        $mainPage = $this->getPage('Main');
        /** @var \Devmate\Page\SignUp $signUpPage */
        $signUpPage = $this->getPage('SignUp');

        $mainPage->openAndVerify();
        $mainPage->signUpNow();
        $signUpPage->chooseSellingOutsideStore();
        $this->assertTrue(
            $signUpPage->isSolutionFieldVisible(),
            'Solution field is not visible on the page'
        );
    }

    /**
     * @test
     */
    public function scenario2()
    {
        /** @var \Devmate\Page\Main $mainPage */
        $mainPage = $this->getPage('Main');
        /** @var \Devmate\Page\Frameworks $frameworksPage */
        $frameworksPage = $this->getPage('Frameworks');
        /** @var \Devmate\Page\AppManagement $managementPage */
        $managementPage = $this->getPage('AppManagement');

        $mainPage->openAndVerify();
        $frameworksPage->openAndVerify();
        $this->assertTrue(
            $frameworksPage->isSparcleUpdatesFrameworkImagePresent(),
            'Sparcle updates framework image is not visible on the page'
        );
        $this->takeScreenshot();

        $managementPage->openAndVerify();
        $this->assertTrue(
            $managementPage->isEasyUpdatesImagePresent(),
            'Easy updates image is not visible on the page'
        );
        $this->takeScreenshot();
    }
}

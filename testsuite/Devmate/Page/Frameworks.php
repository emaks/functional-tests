<?php
namespace Devmate\Page;

use Selenium\Page;

class Frameworks extends Page
{
    protected $mca = '/features/frameworks';
    protected $title = 'Simplify App Development | DevMate';

    protected $featuresLink = "//a[text()='Features']";
    protected $isActivePage = "//a[@class='head-slider-tab on' and span='Frameworks']";

    protected $frameworkImage =
        "//div[img/@data-src='/img/screens/features/frameworks/0-0-sparcle-updates-framework.jpg']";

    public function openAndVerify()
    {
        $this->getElement($this->featuresLink)->click();
        $this->verify();
    }

    public function verify()
    {
        $this->assertSame($this->title, $this->title());
        $this->assertSame($this->getBaseUrl() . $this->mca, $this->url());
        $this->assertTrue($this->isElementVisible($this->isActivePage));
    }

    public function isSparcleUpdatesFrameworkImagePresent()
    {
        return $this->isElementVisible($this->featuresLink);
    }
}
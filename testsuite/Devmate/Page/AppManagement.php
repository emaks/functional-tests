<?php
namespace Devmate\Page;

use Selenium\Page;

class AppManagement extends Page
{
    protected $mca = '/features/app-management';
    protected $title = 'Fasten App Distribution | DevMate';

    protected $isActivePage = "//a[@class='head-slider-tab on' and span='App Management']";

    protected $easyUpdatesImage =
        "//div[img/@data-src='/img/screens/features/app-management/1-0-easy-updates.jpg']";

    public function openAndVerify()
    {
        $this->url($this->mca);
        $this->verify();
    }

    public function verify()
    {
        $this->assertSame($this->title, $this->title());
        $this->assertSame($this->getBaseUrl() . $this->mca, $this->url());
        $this->assertTrue($this->isElementVisible($this->isActivePage));
    }

    public function isEasyUpdatesImagePresent()
    {
        return $this->isElementVisible($this->easyUpdatesImage);
    }
}
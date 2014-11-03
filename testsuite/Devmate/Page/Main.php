<?php
namespace Devmate\Page;

use Selenium\Page;

class Main extends Page
{
    protected $mca = '/';
    protected $title = 'DevMate Is the Ultimate Tool to Deploy, Manage and Monitor | DevMate';

    protected $signUpButton = "//a[text()='Sign Up Now']";

    public function openAndVerify()
    {
        $this->url($this->mca);
        $this->verify();
    }

    public function verify()
    {
        $this->assertSame($this->title, $this->title());
        $this->assertSame($this->getBaseUrl() . $this->mca, $this->url());
    }

    public function signUpNow()
    {
        $this->getElement($this->signUpButton)->click();
        /** @var \Devmate\Page\SignUp $signUpPage */
        $signUpPage = $this->getPage('SignUp');
        $signUpPage->verify();
    }
}
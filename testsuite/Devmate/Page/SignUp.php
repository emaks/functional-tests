<?php
namespace Devmate\Page;

use Selenium\Page;

class SignUp extends Page
{
    protected $mca = '/signup';
    protected $title = 'Request for Beta Now | DevMate';

    protected $signUpLink = "//a[text()='Sign Up']";
    protected $sellingOutsideCheckbox = "//input[@name='selling_outside_app_store']";
    protected $solutionField = "//input[@name='solution']";

    public function openAndVerify()
    {
        $this->getElement($this->signUpLink)->click();
        $this->verify();
    }

    public function verify()
    {
        $this->assertSame($this->title, $this->title());
        $this->assertSame($this->getBaseUrl() . $this->mca, $this->url());
    }

    public function chooseSellingOutsideStore()
    {
        $element = $this->getElement($this->sellingOutsideCheckbox);
        if (!$element->selected()) {
            $element->click();
        }
    }

    public function isSolutionFieldVisible()
    {
        return $this->isElementVisible($this->solutionField);
    }
}
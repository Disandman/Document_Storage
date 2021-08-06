<?php

use yii\helpers\Url;

class UploadFunctionalTestCept
{
    public function ensureThatHomePageWorks(FunctionalTester $I)
    {

        $I->amOnPage('/upload/index');
        $I->click("//select[contains(@class,'form-control')]");
        $I->wait(1);
        $I->click("//path[@fill='currentColor']");
        $I->wait(1);
        $I->click("//li[contains(.,'За сегодня')]");
        $I->wait(1);
        $I->click("//li[@data-range-key='За неделю']");
    }
}

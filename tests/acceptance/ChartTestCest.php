<?php

use yii\helpers\Url;

class ChartTestCest
{
    public function ensureThatHomePageWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/chart/index-alt');
        $I->click("//b[@class='caret']");
        $I->wait(1);
        $I->click("//li[contains(.,'За месяц')]");
        $I->wait(1);
        $I->click("//span[@title='Clear']");

    }
}
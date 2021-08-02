<?php

use yii\helpers\Url;

class ChartCest
{
    public function ensureThatAboutWorks(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/chart/index-alt'));
        $I->see('Загружено файлов', 'h2');
    }
}

<?php

use yii\helpers\Url;

class LoginTestCest
{
    public function ensureThatHomePageWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/user/login');
        $I->fillField('Логин', 'def');
        $I->fillField('Пароль', 'def');
        $I->fillField('Логин', $_ENV['TEST_LOGIN']);
        $I->fillField('Пароль', $_ENV['TEST_PASSWORD']);
        $I->click('Авторизоваться');
        $I->wait(1);
        $I->see($_ENV['TEST_LOGIN']);
    }
}

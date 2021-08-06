<?php

class UnitTest extends \Codeception\Test\Unit
{

    public function testSomeFeature()
    {
     $upload = new \app\models\Upload();
     $user = new \dektrium\user\models\User();
        var_dump($upload);
        var_dump($user);
        ob_flush();
    }
}
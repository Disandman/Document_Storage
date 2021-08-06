<?php

class ErrorTest extends \Codeception\Test\Unit
{

    public function testSomeFeature()
    {
        $upload = \app\models\Upload::find()->all();
        var_dump($upload);
        ob_flush();
    }
}
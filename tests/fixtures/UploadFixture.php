<?php

namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class UploadFixture extends ActiveFixture
{
    public $modelClass = 'app\models\Upload';
    public $depends = ['app\tests\fixtures\UserFixture'];
}

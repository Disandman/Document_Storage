<?php

namespace app\modules\v1\models;


use app\models\Upload;
use yii\helpers\Url;
use yii\web\Linkable;
class ApiModel  extends Upload implements Linkable
{
    public function fields()
    {
        return parent::fields(); // TODO: Change the autogenerated stub
    }

    public function extraFields()
    {
        return parent::extraFields(); // TODO: Change the autogenerated stub
    }

    public function getLinks()
    {
        return [
            'viewlink' => Url::to(['upload/view', 'id' => $this->id], true),
        ];
    }
}
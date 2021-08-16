<?php

namespace app\modules\v1\controllers;

use app\components\BaseApiController;
use app\models\Upload;
use app\modules\v1\models\ApiModel;
use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;


class ApiController extends BaseApiController
{
    public $modelClass = ApiModel::class;

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        return $actions;
    }

    public function actionCreate()
    {
        $uploads = \yii\web\UploadedFile::getInstancesByName('file');
        if (empty($uploads)) {
            return false;
        }
        $path = $_ENV['DOWNLOAD_PATH']; // set your path
        foreach ($uploads as $upload) {
            $filename = $path . time() . '_' . $upload->name;
            $upload->saveAs($filename);
            $model = new $this->modelClass;
            $model->name = $upload->name;
            $model->date = date("Y-m-d");
            $model->size = number_format($upload->size / 1048576, 3) . ' ' . 'MB';
            $model->save();
        }
        return true;

    }
}

<?php

namespace app\modules\v1\controllers;

use app\components\BaseApiController;
use app\models\Upload;
use app\modules\v1\models\ApiModel;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;


class ApiController extends BaseApiController
{
    public $modelClass = ApiModel::class;

    public function actionCreatenew()
    {
        $model = new Upload();

        if ($model->load(\Yii::$app->request->post())) {
            if ($model->file = UploadedFile::getInstances($model, 'file')) {
                foreach ($model->file as $file) {
                    $modelMulti = new Upload();
                    $file->saveAs($_ENV['DOWNLOAD_PATH'] . $file->name);
                    $modelMulti->name = $file->name;
                    $modelMulti->type = $model->type;
                    $modelMulti->user_id = \Yii::$app->user->id;
                    $modelMulti->date = date("Y-m-d");
                    $modelMulti->size = number_format($file->size / 1048576, 3) . ' ' . 'MB';
                    $modelMulti->save(false);
                }

            }
            return true;
        }

        return false;
    }
}

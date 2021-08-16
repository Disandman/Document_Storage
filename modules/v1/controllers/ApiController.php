<?php

namespace app\modules\v1\controllers;

use app\components\BaseApiController;
use app\models\Upload;
use app\modules\v1\models\ApiModel;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
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
        $model = new $this->modelClass;
        $modelIds = [];
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
                    $modelIds[] = $modelMulti->id;
                }

            }
            return new ActiveDataProvider([
                'query' => Upload::find()->where(['id' => $modelIds])
            ]);
        } else return $model->getErrors();


    }

}

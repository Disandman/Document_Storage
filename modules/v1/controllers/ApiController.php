<?php

namespace app\modules\v1\controllers;

use app\components\BaseApiController;
use app\models\Upload;
use app\modules\v1\models\ApiModel;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecordInterface;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;


class ApiController extends BaseApiController
{
    public $findModel;
    public $modelClass = ApiModel::class;

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);
        return $actions;
    }


    public function actionCreate()
    {
        $uploads = \yii\web\UploadedFile::getInstancesByName('file');
        if (empty($uploads)) {
            return "Failure";
        }
        $path = $_ENV['DOWNLOAD_PATH'];
        foreach ($uploads as $upload) {
            $filename = $path . $upload->name;
            $upload->saveAs($filename);
            $model = new $this->modelClass;
            $model->name = $upload->name;
            $model->type = 0;
            $model->date = date("Y-m-d");
            $model->size = number_format($upload->size / 1048576, 3) . ' ' . 'MB';
            $model->save();
        }
        return "success";

    }

    public function findModel($id)
    {
        if ($this->findModel !== null) {
            return call_user_func($this->findModel, $id, $this);
        }

        /* @var $modelClass ActiveRecordInterface */
        $modelClass = $this->modelClass;
        $keys = $modelClass::primaryKey();
        if (count($keys) > 1) {
            $values = explode(',', $id);
            if (count($keys) === count($values)) {
                $model = $modelClass::findOne(array_combine($keys, $values));
            }
        } elseif ($id !== null) {
            $model = $modelClass::findOne($id);
        }

        if (isset($model)) {
            return $model;
        }

        throw new NotFoundHttpException("Object not found: $id");
    }

    public function actionUpdate($id)

    {    $modelDel = $this->findModel($id);
        \Yii::$app->request->getBodyParams();
        $uploads = \yii\web\UploadedFile::getInstancesByName('file');
        if (empty($uploads)) {
            return "Failure";
        }
        $path = $_ENV['DOWNLOAD_PATH'];
        foreach ($uploads as $upload) {
            $filename = $path . $upload->name;
            $upload->saveAs($filename);
            $model = new $this->modelClass;
            $model->name = $upload->name;
            $model->type = 0;
            $model->date = date("Y-m-d");
            $model->size = number_format($upload->size / 1048576, 3) . ' ' . 'MB';
            unlink($_ENV['DOWNLOAD_PATH'] . $modelDel->name);
            $this->findModel($id)->delete();
            $model->save();
        }
        return "success";

    }


    public function actionDelete($id)
    {   $modelDel = $this->findModel($id);
        $model = $this->findModel($id);
        if (!$_ENV['DOWNLOAD_PATH'] . $modelDel->name) {
            unlink($_ENV['DOWNLOAD_PATH'] . $modelDel->name);
        }
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }
}
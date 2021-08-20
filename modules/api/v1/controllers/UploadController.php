<?php

namespace app\modules\api\v1\controllers;

use app\models\Upload;
use app\modules\api\v1\models\ApiModel;
use yii\db\ActiveRecordInterface;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;


class UploadController extends BaseApiController
{
    public $findModel;
    public $modelClass = ApiModel::class;

//////////////////////////////////Замена стандартных экшенов на свои////////////////////////////////
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);
        return $actions;
    }

//////////////////////////////////Добавление нового объекта////////////////////////////////
    public function actionCreate()
    {
        $upload = \yii\web\UploadedFile::getInstanceByName('file');
        if (!empty($upload)) {
            $upload->saveAs(Upload::getPathToFile($upload));
            $model = new $this->modelClass;
            $model->name = $upload->name;
            $model->type = 0;
            $model->date = date("Y-m-d");
            $model->size = number_format($upload->size / 1048576, 3) . ' ' . 'MB';
            $model->save();
            return array('status' => 'A new object has been added.', 'data' => $model);
        }
        throw new ServerErrorHttpException('Failed to add object for unknown reason.');
    }

//////////////////////////////////Изменение существующего объекта////////////////////////////////
    public function actionUpdate($id)
    {
        \Yii::$app->request->getBodyParams();
        $upload = \yii\web\UploadedFile::getInstanceByName('file');
        if (!empty($upload)) {
            $upload->saveAs(Upload::getPathToFile($upload));
            $model = $this->findModel($id);
            $model->name = $upload->name;
            $model->type = 0;
            $model->date = date("Y-m-d");
            $model->size = number_format($upload->size / 1048576, 3) . ' ' . 'MB';
            if (!Upload::getPathToFile($this->findModel($id)->name)) {
                unlink(Upload::getPathToFile($this->findModel($id)->name));
            }
            $model->save();
            return array('status' => 'A new object has been added.', 'data' => $model);
        }
        throw new ServerErrorHttpException('Failed to add object for unknown reason.');
    }

//////////////////////////////////Удаление объекта/////////////////////////////////////////////
    public function actionDelete($id)
    {
        if (!Upload::getPathToFile($this->findModel($id)->name)) {
            unlink(Upload::getPathToFile($this->findModel($id)->name));
        }
        return false !== $this->findModel($id)->delete();
    }

///////////////////////////////////Функция поиска объекта//////////////////////////////////////
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
}
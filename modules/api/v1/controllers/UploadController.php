<?php

namespace app\modules\api\v1\controllers;

use app\models\Upload;
use app\modules\api\v1\models\ApiModel;
use yii\db\ActiveRecordInterface;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;

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

        // Лучше сразу выкинуть исключение, если что-то ломает работу.
        // Такой код читается чуть лучше.
        if(empty($upload)) {
            // Конструкция Yii::t используется для интернационализации приложения
            // т.е. если мы захотим перевести API на, например, французский,
            // всё, что потребуется - добавить словарь с переводом в папке message
            // Не ошибка, просто для понимания https://yiiframework.com.ua/ru/doc/guide/2/tutorial-i18n/
            throw new BadRequestHttpException(\Yii::t('api', 'File for upload not attached!'));
        }

        /** @var \yii\base\Model $model */
        $model = new $this->modelClass;
        $model->name = $upload->name;
        $model->type = 0;
        $model->date = date("Y-m-d");
        $model->size = number_format($upload->size / 1048576, 3) . ' ' . 'MB';
        $result = $model->save();

        // А вдруг модель не сохранилась?
        // return array('status' => 'A new object has been added.', 'data' => $model);

        // Сделаем так
        if(!$result) {
            // Получим первую ошибку и отдадим пользователю через exception
            $error = empty($model->getFirstErrors()) ? '' : array_shift($model->getFirstErrors());
            throw new UnprocessableEntityHttpException(\Yii::t('api', 'Error on save entity {error}', ['{error}' => $error]));
        }
            
        // файл загрузим только после того, как модель успешно сохранилась
        $upload->saveAs(Upload::getPathToFile($model->name));

        return $model;
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
            unlink(Upload::getPathToFile($this->findModel($id)->name));
            $model->save();
            return array('status' => 'The object has been updated.', 'data' => $model);
        }
        throw new ServerErrorHttpException('Failed to update the object for an unknown reason..');
    }
//////////////////////////////////Удаление объекта/////////////////////////////////////////////
    public function actionDelete($id)
    {
        // Вот тут лучше сделать переменную $model = ($this->findModel($id),
        // чтобы меньше дергать базу данных
        unlink(Upload::getPathToFile($this->findModel($id)->name));
        return false !== $this->findModel($id)->delete();
    }
///////////////////////////////////Функция поиска объекта//////////////////////////////////////
    public function findModel($id)
    {
        // Всё ещё не понимаю, что это и зачем))
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
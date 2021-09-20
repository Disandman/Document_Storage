<?php

namespace app\modules\api\v1\controllers;

use app\modules\api\v1\models\UploadModelSearch;
use Yii;
use app\models\Upload;
use app\modules\api\v1\models\UploadModel;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecordInterface;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;
use yii\web\UploadedFile;


class UploadController extends BaseApiController
{
    public $findModel;
    public $modelClass = UploadModel::class;

    /**
     * Замена стандартных экшенов на свои.
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        $actions['index'] = [
            'class' => 'yii\rest\IndexAction',
            'modelClass' => $this->modelClass,
            'prepareDataProvider' => function () {
                $searchModel = new UploadModelSearch();
                return $searchModel->search(Yii::$app->request->queryParams);
            },
        ];
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);
        return $actions;
    }

    /**
     * Добавление нового объекта.
     * @return array
     * @throws UnprocessableEntityHttpException
     */
    public function actionCreate()
    {
        $model = new $this->modelClass;
        $model->load($this->request->getBodyParams(), '');
        $model->file = UploadedFile::getInstanceByName('file');
        if(!$model->file) {
            throw new BadRequestHttpException(Yii::t('app', 'File attachment is required!'));
        }
        $model->size = $model->getFileSize();
        $model->unique_name = $model->getUniqueName();
        $model->name = $model->file->name;
        $model->date = date("Y-m-d");
        $transaction = $model::getDb()->beginTransaction(); // начало транзакции
        if($model->save()) {
            if($model->file->saveAs($model::getPathToFile($model->getUniqueName()))) {
                $transaction->commit(); // транзакция успешно выполнена
                $this->response->setStatusCode(201);
                return $model;
            } else {
                $transaction->rollBack(); // при ошибке откатили изменения
                throw new ServerErrorHttpException(Yii::t('app', 'Failed to save file on disk'));
            }
        }

        $transaction->rollBack(); // при ошибке откатили изменения

        if(!$model->hasErrors()) {
            throw new ServerErrorHttpException(Yii::t('app', 'Failed to save object'));
        }

        return $model;
    }




    /**
     * Изменение существующего объекта
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     * @throws InvalidConfigException|UnprocessableEntityHttpException
     */

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->load($this->request->getBodyParams(), '');
        $model->file = UploadedFile::getInstanceByName('file');
        if (!$model->file) {
            throw new BadRequestHttpException(Yii::t('app', 'File attachment is required!'));
        }
        $model->size = $model->getFileSize();
        $model->unique_name = $model->getUniqueName();
        $model->name = $model->file->name;
        $model->date = date("Y-m-d");
        $transaction = $model::getDb()->beginTransaction(); // начало транзакции
        if ($model->save()) {
            if (file_exists(Upload::getPathToFile($model->unique_name))) {
                unlink(Upload::getPathToFile($model->unique_name));
            }
            if ($model->file->saveAs($model::getPathToFile($model->getUniqueName()))) {
                $transaction->commit(); // транзакция успешно выполнена
                $this->response->setStatusCode(201);
                return $model;
            } else {
                $transaction->rollBack(); // при ошибке откатили изменения
                throw new ServerErrorHttpException(Yii::t('app', 'Failed to save file on disk'));
            }
        }

        $transaction->rollBack(); // при ошибке откатили изменения

        if (!$model->hasErrors()) {
            throw new ServerErrorHttpException(Yii::t('app', 'Failed to save object'));
        }

        return $model;
    }

    /**
     * Удаление объекта
     * @param $id
     * @return bool
     * @throws NotFoundHttpException
     */
    public function actionDelete($id): void
    {
        $model = $this->findModel($id);
        if (file_exists(Upload::getPathToFile($model->unique_name))) {
            unlink(Upload::getPathToFile($model->unique_name));
        }

        if(false === $model->delete()) {
            throw new ServerErrorHttpException(Yii::t('app','Failed to delete the entity'));
        }

        $this->response->setStatusCode(204);
    }

    /**
     * Функция поиска объекта.
     * @param $id
     * @return false|mixed|ActiveRecordInterface
     * @throws NotFoundHttpException
     */

    public function findModel($id)
    {
        $modelClass = $this->modelClass;
        if (($model = $modelClass::findOne($id)) !== null) {
                return $model;
        }
        throw new NotFoundHttpException("Object not found: $id");
    }
}
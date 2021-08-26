<?php

namespace app\modules\api\v1\controllers;

use Yii;
use app\models\Upload;
use app\modules\api\v1\models\ApiModel;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecordInterface;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;
use yii\web\UploadedFile;


class UploadController extends BaseApiController
{
    public $findModel;
    public $modelClass = ApiModel::class;

    /**
     * Замена стандартных экшенов на свои.
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
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
    public function actionCreate(): array
    {
        $model = new $this->modelClass;
        $model->file = UploadedFile::getInstanceByName('file');
        $unique_name = $model->file->name . '_' . date('d.m.Y_h:i:s') . '_' . rand(1, 1000);
        $model->name = $model->file->name;
        $model->unique_name = $unique_name;
        $model->type = Yii::$app->request->getBodyParam('type');
        $model->date = date("Y-m-d");
        $model->size = number_format($model->file->size / 1048576, 3) . ' ' . 'MB';
        if ($model->validate()) {
            $result = $model->save();
            if (!$result) {
                $error = empty($model->getFirstErrors()) ? '' : array_shift($model->getFirstErrors());
                throw new UnprocessableEntityHttpException(Yii::t('api', 'Error on save entity {error}', ['{error}' => $error]));
            }
            $model->file->saveAs(Upload::getPathToFile($unique_name));
            return array(Yii::t('api', 'A new object has been added.'), 'data' => $model);
        }
        return array(Yii::t('api', 'No new object has been added.'), 'error' => $model);
    }

    /**
     * Изменение существующего объекта
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     * @throws InvalidConfigException|UnprocessableEntityHttpException
     */

    public function actionUpdate($id): array
    {
        $model = $this->findModel($id);
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        $model->file = UploadedFile::getInstanceByName('file');
        if ($model->validate()) {
            if ($model->file) {

                $unique_name = $model->file->name . '_' . date('d.m.Y_h:i:s') . '_' . rand(1, 1000);
                $model->name = $model->file->name;
                $model->unique_name = $unique_name;
                $model->type = Yii::$app->request->getBodyParam('type');
                $model->date = date("Y-m-d");
                $model->size = number_format($model->file->size / 1048576, 3) . ' ' . 'MB';

            if (!file_exists(Upload::getPathToFile($model->unique_name))) {
                unlink(Upload::getPathToFile($model->unique_name));
            }
                $model->file->saveAs(Upload::getPathToFile($unique_name));
            }
            if ($model->save(false) === false && !$model->hasErrors()) {
                $error = empty($model->getFirstErrors()) ? '' : array_shift($model->getFirstErrors());
                throw new UnprocessableEntityHttpException(Yii::t('api', 'Error on save entity {error}', ['{error}' => $error]));
            } else {
                return array(Yii::t('api', 'A new object has been added.'), 'data' => $model);
            }
        }
        return array(Yii::t('api', 'No new object has been added.'), 'error' => $model);
    }

    /**
     * Удаление объекта
     * @param $id
     * @return bool
     * @throws NotFoundHttpException
     */
    public function actionDelete($id): bool
    {
        $model = $this->findModel($id);
        if (!file_exists(Upload::getPathToFile($model->unique_name))) {
            unlink(Upload::getPathToFile($model->unique_name));
        }
        return false !== $model->delete();
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
            if (isset($model)) {
                return $model;
            }
        }
        throw new NotFoundHttpException("Object not found: $id");
    }
}
<?php

namespace app\modules\v1\controllers;

use app\components\BaseApiController;
use app\modules\v1\models\ApiModel;
use yii\web\NotFoundHttpException;


class ApiController extends BaseApiController
{
    // Вот это здесь лишнее. 
    //
    // Подобное имеет смысл использовать, если 
    // надо сохранить данные в рамках одного запроса.
    // PHP-процесс отдает данные по запросу и умирает,
    // т.е. память очищается.
    // В случаях, когда надо срохранять данные между запросами,
    // используют кэширование.
    public $findModel;
    
    /**
     * @inheritDoc
     */
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
            // Когда что-то не получается -
            // выкидываем исключение.
            // Метод должен вернуть соответствующий HTTP-статус,
            // отличный от 200 ОК.
            return "Failure";
        }
        $path = $_ENV['DOWNLOAD_PATH'];
        // Почему POST создает сразу несколько файлов? 
        // 1 запрос = 1 файл. 
        // Чем меньше у пользователя выбора,
        // тем проще ему живется :D
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

    public function actionUpdate($id)
    {
        $modelDel = $this->findModel($id);
        \Yii::$app->request->getBodyParams();
        $uploads = \yii\web\UploadedFile::getInstancesByName('file');
        if (empty($uploads)) {
            // Когда что-то не получается -
            // выкидываем исключение.
            // Метод должен вернуть соответствующий HTTP-статус,
            // отличный от 200 ОК.
            return "Failure";
        }
        $path = $_ENV['DOWNLOAD_PATH'];
        // Неправильно с точки зрения REST. 
        // Метод PUT должен изменять существующий файл, 
        // а не создавать новый
        // И почему файлов несколько?
        foreach ($uploads as $upload) {
            $filename = $path . $upload->name;
            $upload->saveAs($filename);
            $model = new $this->modelClass;
            $model->name = $upload->name;
            $model->type = 0;
            $model->date = date("Y-m-d");
            $model->size = number_format($upload->size / 1048576, 3) . ' ' . 'MB';
            unlink($_ENV['DOWNLOAD_PATH'] . $modelDel->name);
            // для манипуляций с моделью достаточно $model->delete()
            $this->findModel($id)->delete();
            $model->save();
        }
        return "success";
    }

    /**
     * Не забываем писать аннотации к методам! Пусть даже примитивные. 
     * Что понятно сейчас, может стать болью в будущем.
     *
     * @param int|string $id
     * @return void
     */
    public function actionDelete($id)
    {
        $modelDel = $this->findModel($id);
        if (!$_ENV['DOWNLOAD_PATH'] . $modelDel->name) {
            unlink($_ENV['DOWNLOAD_PATH'] . $modelDel->name);
        }

        /**
         * Метод должен возвращать реальный результат.
         * В данном случае delete возвращает значение int|false,
         * причём если записи не было - вернётся 0.
         * Поэтому принудительно сравниваем с булевой переменной.
         * Если false - ошибка удаления, в остальных случаях - всё хорошо.
         */
        return false !== $modelDel->delete();
        // $this->findModel($id)->delete();
        // return "success delete";
    }

    /**
     * Поменяла на `protected`, т.к. это внутренний метод.
     */
    protected function findModel($id) : \yii\db\ActiveRecord
    {
        // Зачем эта конструкция? findModel может быть функцией?
        if ($this->findModel !== null) {
            return call_user_func($this->findModel, $id, $this);
        }

        // Не очень поняла, зачем нужна поддержка составных ПК.
        // Обычно всё-таки есть уникальный идентификатор. 
        // Например, тот же uuid
        // Но пусть будет)

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

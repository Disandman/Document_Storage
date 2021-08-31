<?php

namespace app\modules\api\v1\controllers;

use Yii;
use app\models\Upload;
use app\modules\api\v1\models\ApiModel;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\db\ActiveRecordInterface;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
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
     * 
     * @return ActiveRecord Созданная модель
     * @throws UnprocessableEntityHttpException
     */
    public function actionCreate()
    {
        // Шаг 1. Создаем новую модель
        /** @var Upload $model */
        $model = new $this->modelClass;
        
        // В некоторых случаях имеет смысл использовать сценарии
        // С помощью сценариев можно определять надо rules для разных случаев
        // Подробнее можно почитать тут https://www.yiiframework.com/doc/guide/2.0/ru/structure-models#scenarios
        // $model->scenario = ApiModel::SCENARIO_CREATE;

        /**
         * Шаг 2. Загружаем в модель данные, полученные от пользователя. 
         * Обрати внимание, второй параметр - пустая строка. 
         * По умолчанию метод load ждёт, что данные для модели будут как "обернуты" в название модели, т.е.
         *      [
         *          'Upload' => [
         *                  'id' => 1,
         *                  'type' => 0,
         *                  'name' => 'test'
         *           ]
         *      ]
         * В нащем же случае мы передаем запрос без этой обертки:
         *      [
         *          'id' => 1,
         *          'type' => 0,
         *          'name' => 'test'
         *      ]
         */
        $model->load($this->request->getBodyParams(), '');
        $model->file = UploadedFile::getInstanceByName('file');

        /**
         * Шаг 3. Проверяем наличие файла. Если его нет - выкидываем исключение
         */
        if(!$model->file) {
            throw new BadRequestHttpException(Yii::t('app', 'File attachment is required!'));
        }


        // Правило именования - camelCase. 
        // Underscope обычно в php сообществе не принято использовать, за некоторыми исключениями
        $uniqueName = $model->file->name . '_' . date('d.m.Y_h:i:s') . '_' . rand(1, 1000);

        // По хорошему стоило бы uniqueName и расчет размера вынести в методы класса
        $model->size = number_format($model->file->size / 1048576, 3) . ' ' . 'MB';
        $model->unique_name = $uniqueName;
        $model->name = $model->file->name;
        $model->date = date("Y-m-d");

        /**
         * Шаг 4. Валидируем и сохраняем
         * 
         * Валидация здесь внутри метода save(). Перед тем, как выполнить сохранение в БД, 
         * Yii делает проверку на соответствие данных заданным rules.
         * 
         * Здесь я также добавила такую вещь, как транзакции.
         * Транзакция отвечает за целостность данных в БД. Если в процессе выполнения действий
         * в блоке транзации происходит ошибка, изменения из этого блока, внесенные в БД
         * ранее будут откачены назад.
         * Например, не получилось записать файл на диск. Тогда мы откатим
         * запись в БД и выкинем пользователю сообщение. И в базе не будет "огрызков".
         */
        $transaction = $model::getDb()->beginTransaction(); // начало транзакции

        if($model->save()) {
            if($model->file->saveAs($model::getPathToFile($uniqueName))) {
                $transaction->commit(); // транзакция успешно выполнена

                $this->response->setStatusCode(201);
                return $model;
            } else {
                $transaction->rollBack(); // при ошибке откатили изменения

                throw new ServerErrorHttpException(Yii::t('app', 'Failed to save file on disk'));
            }
        } 

        $transaction->rollBack(); // при ошибке откатили изменения

        /**
         * Шаг 5. Если что-то пошло не так, мы должны об этом сказать пользователю
         * Если это ошибка не связанная с валидацией - выкинем общую ошибку
         * Иначе Yii сама составит список ошибок для пользователя
         */
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
     * 
     * @param $id ID объекта
     * @return void
     * @throws \Exception
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
            if (isset($model)) {
                return $model;
            }
        }
        throw new NotFoundHttpException("Object not found: $id");
    }
}
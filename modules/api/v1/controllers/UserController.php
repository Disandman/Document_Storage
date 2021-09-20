<?php

namespace app\modules\api\v1\controllers;



use Yii;
use app\modules\api\v1\models\UserModel;
use dektrium\user\models\User;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;


class UserController extends BaseApiController
{

    public $findModel;
    public $modelClass = UserModel::class;

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['update']);
        return $actions;
    }

    public function actionCreate()
    {
        $model = new User();
        $model->load($this->request->getBodyParams(), '');
        $password = $this->request->getBodyParam('password_hash');
        $model->password_hash = \Yii::$app->security->generatePasswordHash($password, \Yii::$app->getModule('user')->cost);
        $transaction = $model::getDb()->beginTransaction(); // начало транзакции

        if ($model->save()) {
            $transaction->commit(); // транзакция успешно выполнена
            $this->response->setStatusCode(201);
            return $model;
        } else {
            $transaction->rollBack(); // при ошибке откатили изменения
            throw new ServerErrorHttpException(\Yii::t('app', "Couldn't add user"));
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->load($this->request->getBodyParams(), '');
        $password = $this->request->getBodyParam('password_hash');
        $model->password_hash = \Yii::$app->security->generatePasswordHash($password, \Yii::$app->getModule('user')->cost);
        $transaction = $model::getDb()->beginTransaction(); // начало транзакции

        if ($model->save()) {
            $transaction->commit(); // транзакция успешно выполнена
            $this->response->setStatusCode(201);
            return $model;
        } else {
            $transaction->rollBack(); // при ошибке откатили изменения
            throw new ServerErrorHttpException(\Yii::t('app', "Couldn't add user"));
        }
    }


    public function actionDelete($id): void
    {
        $model = $this->findModel($id);

        if(false === $model->delete()) {
            throw new ServerErrorHttpException(Yii::t('app','Failed to delete the entity'));
        }

        $this->response->setStatusCode(204);
    }

    public function findModel($id)
    {
        $modelClass = $this->modelClass;
        if (($model = $modelClass::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException("Object not found: $id");
    }
}
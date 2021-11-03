<?php

namespace app\modules\api\v1\controllers;

use app\modules\api\v1\models\AuthModel;
use dektrium\user\models\Token;
use Yii;
use dektrium\user\helpers\Password;
use dektrium\user\models\User;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\ContentNegotiator;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class AuthController extends ActiveController
{

    public $findModel;
    public $modelClass = AuthModel::class;

    public function actions()
    {
        return ['index'];
    }
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(),
            'auth' => [$this, 'auth'],

        ];
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
                'xml' => \yii\web\Response::FORMAT_XML
            ],
        ];
        return $behaviors;
    }
    public function auth($username, $password)
    {
        $user = User::findOne(['username' => $username]);
        if(!$user) return null;
        return Password::validate($password, $user->password_hash) ? $user : null;

    }

    public function actionIndex()
    {
        $token = Yii::$app->security->generateRandomString();
        $model = $this->findModel(Yii::$app->user->id);
        $model->created_at = time();
        $model->expired_at = strtotime('+3 hours');
        $model->code = $token;
        $model->save();
        return $token;
    }

    public function findModel($id)
    {
        $modelClass = Token::class;
        if (($model = $modelClass::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException("Object not found: $id");
    }



}
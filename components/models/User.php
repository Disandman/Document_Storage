<?php

namespace app\components\models;

use dektrium\user\models\Token;
use yii\web\NotFoundHttpException;

class User extends \dektrium\user\models\User
{

    /** @inheritdoc */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $modelClass = Token::find()->where([
            "and",
            ['code' => $token],
            ['>=', 'created_at', time()],
        ])->all();

        if (!empty($modelClass)) {
            $id = $modelClass[0]['user_id'];
            return static::findOne(['id' => $id]);
        } else {
            throw new NotFoundHttpException("The token has expired, please get a new one!");
        }
    }

}
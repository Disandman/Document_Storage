<?php

namespace app\components\models;

class User extends \dektrium\user\models\User
{

    /** @inheritdoc */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key' => $token]);
    }

}
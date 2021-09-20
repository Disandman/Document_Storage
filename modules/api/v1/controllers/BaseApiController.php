<?php

namespace app\modules\api\v1\controllers;

use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\rest\ActiveController;
use yii\web\Response;

class BaseApiController extends ActiveController
{
    /**
     * @var array
     */
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function init()
    {
        parent::init();
        \Yii::$app->user->enableSession = false;
    }

    public function checkAccess($action, $model = null, $params = []): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'authMethods' => [
                HttpBearerAuth::class,
            ],
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

}
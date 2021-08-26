<?php

namespace app\controllers;

use app\models\Tech;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

class SiteController extends Controller
{

    /**
     * @return array[]
     */
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }


    /**
     * @return array
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        return $this->render('index');
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionRoutes()
    {
        throw new NotFoundHttpException('Страница не найдена.');
        return $this->render('routes', [
            'routes' => (new Tech())->getAppRoutes(),
        ]);
    }

}

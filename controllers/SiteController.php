<?php

namespace app\controllers;

use app\models\Tech;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
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
     * {@inheritdoc}
     */
    public function actions()
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
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Технический констроллер для выгрузки списка доступных марщрутов
     * Нагло вытащено из  mdmsoft / yii2-admin 
     *
     * @return string
     */
    public function actionRoutes()
    {
        throw new NotFoundHttpException('Страница не найдена.');
        return $this->render('routes', [
            'routes' => (new Tech())->getAppRoutes(),
        ]);
    }

}

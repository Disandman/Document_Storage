<?php

namespace app\controllers;

use app\models\Upload;
use app\models\ChartSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ChartController implements the CRUD actions for Upload model.
 */
class ChartController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => \yii\filters\AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }


    public function actionIndex()
    {
        $model = new Upload();

        return $this->render('index', [

            'model' => $model,
        ]);
    }

    public function actionIndexAlt()
    {
        $model = new ChartSearch();
        $modelCounter = $model->searchCounter($this->request->queryParams);

        return $this->render('index-alt', [
            'model' => $model,
            'modelCounter' => $modelCounter
        ]);
    }
}

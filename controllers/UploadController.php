<?php

namespace app\controllers;

use Yii;
use app\models\Upload;
use app\models\UploadSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;


/**
 * UploadController implements the CRUD actions for Upload model.
 */
class UploadController extends Controller
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
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Upload models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UploadSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Upload model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Upload model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Upload();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->file = UploadedFile::getInstances($model, 'file')) {
                foreach ($model->file as $file) {
                    $modelMulti = new Upload();
                    $file->saveAs($_ENV['DOWNLOAD_PATH'] . $file->name);
                    $modelMulti->name = $file->name;
                    $modelMulti->type = $model->type;
                    $modelMulti->user_id = Yii::$app->user->id;
                    $modelMulti->date = date("Y-m-d");
                    $modelMulti->size = number_format($file->size / 1048576, 3) . ' ' . 'MB';
                    $modelMulti->save(false);
                }

            }
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,

        ]);
    }

    public function actionDownload($id)
    {
        $model = $this->findModel($id);
        return \Yii::$app->response->sendFile($_ENV['DOWNLOAD_PATH'] . $model->name);

    }

    /**
     * Updates an existing Upload model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */


    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if (!$_ENV['DOWNLOAD_PATH'] . $model->name) {
            unlink($_ENV['DOWNLOAD_PATH'] . $model->name);
        }
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($model->file = UploadedFile::getInstances($model, 'file')) {
                foreach ($model->file as $file) {
                    $modelMulti = new Upload();
                    $file->saveAs($_ENV['DOWNLOAD_PATH'] . $file->name);
                    $modelMulti->name = $file->name;
                    $modelMulti->type = $model->type;
                    $modelMulti->user_id = Yii::$app->user->id;
                    $modelMulti->date = date("Y-m-d");
                    $modelMulti->size = number_format($file->size / 1048576, 3) . ' ' . 'MB';
                    $this->findModel($id)->delete();
                    unlink($_ENV['DOWNLOAD_PATH'] . $model->name);
                    $modelMulti->save(false);
                }

            }

            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    /**
     * Finds the Upload model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Upload the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Upload::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

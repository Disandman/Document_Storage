<?php

namespace app\controllers;

use Yii;
use app\models\Upload;
use app\models\UploadSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;


/**
 * UploadController implements the CRUD actions for Upload model.
 */
class UploadController extends Controller
{
    /**
     * Список всех загружаемых моделей.
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new UploadSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 24;
        $dataProvider->prepare();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Отображает одну модель загрузки.
     * @param integer $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Создание файла.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */

    public function actionCreate()
    {
        $model = new Upload();


        if ($model->load(Yii::$app->request->post())) {
            if ($model->file = UploadedFile::getInstances($model, 'file')) {
                foreach ($model->file as $file) {
                    $modelMulti = new Upload();
                    $unique_name = uniqid() . '.' . $file->getExtension();
                    $file->saveAs(Upload::getPathToFile($unique_name));
                    $modelMulti->name = $file->name;
                    $modelMulti->unique_name = $unique_name;
                    $modelMulti->type = $model->type;
                    $modelMulti->user_id = Yii::$app->user->id;
                    $modelMulti->date = date("Y-m-d");
                    $modelMulti->size = number_format($file->size / 1048576, 3) . ' ' . 'MB';
                    $modelMulti->save();
                }
            }
            return $this->redirect(['index']);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Загрузка файла.
     * @param $id
     * @return \yii\console\Response|Response
     * @throws NotFoundHttpException
     */
    public function actionDownload($id)
    {
        $model = $this->findModel($id);
        return \Yii::$app->response->sendFile(Upload::getPathToFile($model->unique_name));

    }

    /**
     * Удаление файла.
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */

    public function actionDelete($id): Response
    {
        $model = $this->findModel($id);
        if (file_exists(Upload::getPathToFile($model->unique_name))) {
            unlink(Upload::getPathToFile($model->unique_name));
        }
        $model->delete();
        return $this->redirect(['index']);
    }


    /**
     * Обновление файла.
     * @param $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($model->file = UploadedFile::getInstances($model, 'file')) {
                foreach ($model->file as $file) {
                    $modelMulti = $this->findModel($id);
                    $unique_name = uniqid() . '.' . $file->getExtension();
                    $file->saveAs(Upload::getPathToFile($unique_name));
                    $modelMulti->name = $file->name;
                    $modelMulti->unique_name = $unique_name;
                    $modelMulti->type = $model->type;
                    $modelMulti->user_id = Yii::$app->user->id;
                    $modelMulti->date = date("Y-m-d");
                    $modelMulti->size = number_format($file->size / 1048576, 3) . ' ' . 'MB';
                    if (file_exists(Upload::getPathToFile($model->unique_name))) {
                        unlink(Upload::getPathToFile($model->unique_name));
                    }
                    $modelMulti->save();
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
    protected function findModel(int $id): Upload
    {
        if (($model = Upload::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

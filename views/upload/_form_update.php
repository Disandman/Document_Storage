<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Upload;

/* @var $this yii\web\View */
/* @var $model app\models\Upload */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="upload-form">

    <?php $form = ActiveForm::begin(['options' => ['entype' => 'multipart/form-data']]); ?>

    <?php echo $form->field($model, 'type')->dropDownList(Upload::$typeNames); ?>


    <hr align="center" width="1130" size="2" color="#ff0000"/>

    <div class="card-footer">
        <?php if ($_ENV['DOWNLOAD_PATH'] . $model->name == true) {
            echo '<h5>Загруженый файл:&#160&#160</h5>', Html::a('<i class="fa fa-download"></i>' . '&#160' . $model->name, ['upload/download', 'id' => $model->id], [
                    'data' => [
                        'method' => 'post',
                    ],
                ]) . '&#160&#160&#160' . Html::a('x', ['upload/delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger btn-xs',
                    'title' => 'Удалить файл',
                    'data' => [
                        'method' => 'post',
                    ],
                ]);
        } else {
            Html::a($_ENV['DOWNLOAD_PATH'] . $model->name, ['upload/delete', 'id' => $model->id], [
                'data' => [
                    'method' => 'post',
                ],
            ]);
        } ?>
    </div>

    <hr align="center" width="1130" size="2" color="#ff0000"/>
    <h4>Заменить файл</h4>
    <div class="card-footer">
        <?php echo $form->field($model, 'file[]')->fileInput(['multiple' => 'multiple']); ?>
    </div>


    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

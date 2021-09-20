<?php

use kartik\file\FileInput;
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


    <div class="row">
        <div class="mx-auto">
            <div class="mb-3 text-center" style="width: 10rem;">
                <img class="card-img-top" src="<?php echo $model->getExtensionFile() ?>">
                <div class="card-body" style="padding: 4px">
                    <h5 class="card-title" style="height: 5.5rem">
                        <b><?php echo \yii\helpers\StringHelper::truncate($model->name, 34, '...'); ?></b></h5>
                    <p class="card-text"
                       style="height: 1rem"><?php echo Yii::$app->formatter->asDate($model->date) ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="col text-center">
        <?php echo $form->field($model, 'file')->widget(FileInput::classname(), [
            'language' => 'ru',
            'options' => ['multiple' => false,]
        ]); ?>
        <?php ActiveForm::end(); ?>

    </div>
</div>
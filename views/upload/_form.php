<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use app\models\Upload;

/* @var $this yii\web\View */
/* @var $model app\models\Upload */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="upload-form">

    <?php $form = ActiveForm::begin(['options' => ['entype' => 'multipart/form-data']]); ?>

    <?php echo $form->field($model, 'type')->dropDownList(Upload::$typeNames); ?>

    <?php echo $form->field($model, 'file')->widget(FileInput::classname(), [
        'language' => 'ru',
        'options' => ['multiple' => true,]
    ]); ?>

    <?php ActiveForm::end(); ?>

</div>

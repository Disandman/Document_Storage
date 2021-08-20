<?php

use app\models\Upload;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use app\models\UploadSearch;

/**
 * @var yii\web\View $this
 * @var app\models\Upload $model
 * @var yii\bootstrap4\ActiveForm $form
 */
?>

<div class="text-left">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <?php echo $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?php echo $form->field($model, 'type')->dropDownList(Upload::$typeNames) ?>
    <?php echo '<label class="control-label">Дата создания файла</label>'; ?>
    <?php echo \kartik\daterange\DateRangePicker::widget(
        [
            'model' => $model,
            'attribute' => 'date',
            'convertFormat' => true,
            'presetDropdown' => true,
            'options' => [
                'class' => 'form-control',
            ],
            'pluginOptions' => [
                'locale' => ['format' => 'Y-m-d'],
                'ranges' => [

                    "За сегодня" => ["moment().startOf('day')", "moment()"],

                    "За вчера" => ["moment().startOf('day').subtract(1,'days')", "moment().endOf('day').subtract(1,'days')"],

                    "За неделю" => ["moment().startOf('day').subtract(7, 'days')", "moment()"],

                    "За месяц" => ["moment().startOf('day').subtract(31, 'days')", "moment()"],

                ],
            ],
        ]
    ) ?>
    <div style="text-align: center; margin: 2%">
        <?php echo Html::submitButton(Yii::t('backend', 'Поиск'), ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Сбросить результат поиска', ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>

<?php

use app\models\UploadSearch;
use app\models\UploadCounter;
use miloschuman\highcharts\Highcharts;
use yii\widgets\ActiveForm;
use kartik\daterange\DateRangePicker;

/**
 * @var \yii\web\View $this
 * @var UploadSearch $model
 * @var UploadCounter $modelCounter
 */

$this->title = 'Статистика';
$this->params['breadcrumbs'][] = $this->title;
?>

<body onload="showLoader()">
<div id="loader"></div>
<section id="container" style="display:none;" class="animate-bottom">

    <?php
    $form = ActiveForm::begin([
        'id' => 'statistics-form',
        'method' => 'get',
        'action' => \yii\helpers\Url::to(['index-alt'])
    ]);


    echo $form->field($model, 'date')->widget(DateRangePicker::classname(), [
        'convertFormat' => true,
        'presetDropdown' => true,
        'hideInput' => true,
        'pluginOptions' => [
            'locale' => ['format' => 'Y-m-d'],
            'opens' => 'right',
            'cancelLabel' => true,
            'ranges' => [

                "За сегодня" => ["moment().startOf('day')", "moment()"],

                "За вчера" => ["moment().startOf('day').subtract(1,'days')", "moment().endOf('day').subtract(1,'days')"],

                "За неделю" => ["moment().startOf('day').subtract(7, 'days')", "moment()"],

                "За месяц" => ["moment().startOf('day').subtract(31, 'days')", "moment()"],

            ],
        ],
        'options' => ['placeholder' => 'Выберите интервал времени...'],
        'pluginEvents' => [
            'apply.daterangepicker' => "function(e){
       $(e.target).closest('form').submit();}"
        ]

    ])->label(false);

    $form = ActiveForm::end();

    $nameChart = 'Соотношение публичных, условно-приватных и приватных документов';

    if ($modelCounter->getCountTotal() != 0) {

        echo Highcharts::widget([
            'options' => [
                'credits' => ['enabled' => false],
                'title' => ['text' => $nameChart],
                'tooltip' => [
                    'pointFormat' => '<b>{point.percentage:.1f}%</b>'
                ],
                'plotOptions' => [
                    'pie' => [
                        'allowPointSelect' => true,
                        'cursor' => 'pointer',

                    ],
                ],
                'series' => [
                    [
                        'type' => 'pie',
                        'data' => [
                            ['Публичные', $modelCounter->getPercentPublic()],
                            ['Условно-приватные', $modelCounter->getPercentProtected()],
                            ['Приватные', $modelCounter->getPercentPrivate()],
                        ],
                    ]
                ],
            ],
        ]);
    }

    elseif(empty($modelCounter->getCountTotal())) {
        echo \yii\bootstrap4\Alert::widget([
            'options' => [
                'class' => 'alert-warning',
            ],
            'body' => 'Нет информации удовлетворяющей поиску... (попробуйте ввести другие интервалы времени)',
        ]);

    }
    echo $this->render('index', ['model' => $model]);
    ?>
</section>
</body>

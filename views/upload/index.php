<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Upload;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UploadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Файлы';
$this->params['breadcrumbs'][] = $this->title;
$model = new Upload();
?>

<body onload="showLoader()">
<div id="loader"></div>
<section id="container" style="display:none;" class="animate-bottom">
    <div class="upload-index">
        <h1><?= Html::encode($this->title) ?></h1>
        <p>
            <?php if (!Yii::$app->user->isGuest) {
                echo Html::a('Загрузить файлы', ['create'], ['class' => 'btn btn-success']);
            } ?>
        </p>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'options' => ['class' => 'table-responsive'],

            'columns' => [
                ['attribute' => 'name',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if (!empty($model->name)) {
                            return
                                $model->id ? Html::a($model->name, \yii\helpers\Url::to(['upload/download', 'id' => $model->id])) : '';
                        }
                    }
                ],

                ['attribute' => 'type', 'filter' => Upload::$typeNames,
                    'value' => function ($model) {
                        return Upload::$typeNames[ $model->type ];
                    },
                ],
                ['attribute' => 'user_id', 'value' => function ($model) {
                    if (!empty($model->uploadUsers->username)) {
                        return $model->uploadUsers->username;
                    }
                }],

                [
                    'attribute' => 'date',
                    'format' => 'date',
                    'value' => 'date',
                    'contentOptions' => ['style' => 'width:300px;  min-width:300px;  '],
                    'filter' => \kartik\daterange\DateRangePicker::widget(
                        [
                            'model' => $searchModel,
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
                    )
                ],

                ['class' => \yii\grid\ActionColumn::class,
                    'template' => '{view} {delete} {update}',
                    'visibleButtons' => [
                        'update' => function ($model) {
                            if ($model->user_id === Yii::$app->user->id || Yii::$app->user->can("admin")) {
                                return true;
                            }
                        },
                        'delete' => function ($model) {
                            if ($model->user_id === Yii::$app->user->id || Yii::$app->user->can("admin")) {
                                return Yii::$app->user->id;
                            }
                        },

                    ]

                ],
            ],
        ]); ?>
    </div>
</section>
</body>

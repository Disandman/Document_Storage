<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Upload;

/* @var $this yii\web\View */
/* @var $model app\models\Upload */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Файлы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<body onload="showLoader()">
<div id="loader"></div>
<section id="container" style="display:none;" class="animate-bottom">
<div class="upload-view">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            ['attribute' => 'name',
                'format' => 'raw',
                'value' => function ($model) {
                    if (!empty($model->name)) {
                        return
                            $model->id ? Html::a($model->name, \yii\helpers\Url::to(['upload/download', 'id' => $model->id])) : '';
                    }
                }
            ],
            'size',
            ['attribute' => 'type', 'filter' => Upload::$typeNames,
                'value' => function ($model) {
                    return Upload::$typeNames[ $model->type ];
                },
            ],

            'date',
            ['attribute' => 'user_id', 'value' => function ($model) {
                if (!empty($model->uploadUsers->username)) {
                    return $model->uploadUsers->username;
                }
            }],

        ],
    ]) ?>

    <?php if ($model->user_id === Yii::$app->user->id || Yii::$app->user->can("admin")) {
        echo Html::a('Удалить файл', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger btn-block',
            'data' => [
                'confirm' => 'Точно удалить файл?',
                'method' => 'post',
            ],
        ]);
    } ?>
</div>
</section>
</body>

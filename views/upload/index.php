<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Upload;

/* @var $this yii\web\View */
/* @var $pages app\controllers\UploadController */
/* @var $searchModel app\models\UploadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Файлы';
$this->params['breadcrumbs'][] = $this->title;
$model = new \app\models\UploadSearch();
?>

<body onload="showLoader()">
<div id="loader"></div>
<section id="container" style="display:none;" class="animate-bottom">

    <div class="row">
        <div class="container">
            <div class="row">
                <div class="col text-left">
                    <h3><?= Html::encode($this->title) ?></h3>
                </div>
                <?= \yii\bootstrap4\LinkPager::widget([
                    'pagination' => $pages,
                ]);
                ?>
                <div class="col text-right">
                    <?php if (!Yii::$app->user->isGuest) {
                        echo Html::a('&#10010;', ['create'], ['class' => 'btn btn-success']);
                    } ?>

                    <?php
                    \yii\bootstrap4\Modal::begin([
                        'toggleButton' => [
                            'label' => '&#128270;',
                            'tag' => 'button',
                            'class' => 'btn btn-success',
                        ],
                    ]);
                    ?>

                    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

                    <?php \yii\bootstrap4\Modal::end(); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col"></div>
    <div class="col text-center img-responsive">
        <?php echo \yii\widgets\ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_item',
            'layout' => "{items}",
            'summaryOptions' => ['class' => ['text-muted mb-3']],
        ]) ?>
    </div>
    </div>
    </div>
</section>
</body>

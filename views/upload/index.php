<?php

use yii\helpers\Html;
use rmrevin\yii\fontawesome\FAS;

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
                    'pagination' => $dataProvider->pagination, // Вот тут берем пагинацию из провайдера
                    'hideOnSinglePage' => false // отключаем автоскрытие виджета, если страниц меньше двух
                ]);
                ?>
                <div class="col text-right">
                    <?php if (!Yii::$app->user->isGuest) {
                        echo Html::a(FAS::i('plus'), ['create'], ['class' => 'btn btn-outline-success']);
                    } ?>

                    <?php
                    \yii\bootstrap4\Modal::begin([
                        'title' => '<h2>Поиск файлов</h2>',
                        'toggleButton' => [
                            'label' => FAS::i('search'),
                            'tag' => 'button',
                            'class' => 'btn btn-outline-info',
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

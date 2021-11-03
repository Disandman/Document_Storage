<?php
/**
 * @var $model app\models\Upload
 */

use app\models\Upload;
use yii\helpers\Html;
use rmrevin\yii\fontawesome\FAS;

?>

<div class="wrapper">
    <?php for ($i = 0; $i < 1; $i++) { ?>
    <div class="one_third">
        <div class="card mb-3 " style="width: 10rem;">
            <img class="card-img-top" src="<?php echo $model->getExtensionFile()?>">
            <div class="card-body" style="padding: 4px">
                <h5 class="card-title" style="height: 5.5rem">
                    <b><?php echo \yii\helpers\StringHelper::truncate($model->name, 34, '...'); ?></b></h5>
                <p class="card-text"
                   style="height: 2rem"><?php echo "Тип: " . Upload::$typeNames[$model->type] ?></p>
                <p class="card-text" style="height: 1rem"><?php echo Yii::$app->formatter->asDate($model->date) ?></p>
                <div class="card-body" style="padding: 6px">
                    <?php echo Html::a(FAS::i('eye'), ['view', 'id' => $model->id]) ?>
                    <?php if ($model->user_id === Yii::$app->user->id || Yii::$app->user->can("admin")) {
                        echo Html::a(FAS::i('pencil-alt'), ['update', 'id' => $model->id]);
                    } ?>
                    <?php if ($model->user_id === Yii::$app->user->id || Yii::$app->user->can("admin")) {
                        echo Html::a(FAS::i('trash-alt'), ['delete', 'id' => $model->id], [ 'data-confirm' => Yii::t('backend', 'Точно удалить файл?')]);
                    } ?>
                    <?php if ($model->user_id === Yii::$app->user->id || Yii::$app->user->can("admin")) {
                        echo Html::a(FAS::i('download'), ['upload/download', 'id' => $model->id]);
                    } ?>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
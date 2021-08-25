<?php
/**
 * @var $model app\models\Upload
 */

use yii\helpers\Html;

$eye = '<svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:1.125em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M573 241C518 136 411 64 288 64S58 136 3 241a32 32 0 000 30c55 105 162 177 285 177s230-72 285-177a32 32 0 000-30zM288 400a144 144 0 11144-144 144 144 0 01-144 144zm0-240a95 95 0 00-25 4 48 48 0 01-67 67 96 96 0 1092-71z"/></svg>';
$pencil = '<svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:1em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M498 142l-46 46c-5 5-13 5-17 0L324 77c-5-5-5-12 0-17l46-46c19-19 49-19 68 0l60 60c19 19 19 49 0 68zm-214-42L22 362 0 484c-3 16 12 30 28 28l122-22 262-262c5-5 5-13 0-17L301 100c-4-5-12-5-17 0zM124 340c-5-6-5-14 0-20l154-154c6-5 14-5 20 0s5 14 0 20L144 340c-6 5-14 5-20 0zm-36 84h48v36l-64 12-32-31 12-65h36v48z"/></svg>';
$trash = '<svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:.875em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M32 464a48 48 0 0048 48h288a48 48 0 0048-48V128H32zm272-256a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zM432 32H312l-9-19a24 24 0 00-22-13H167a24 24 0 00-22 13l-9 19H16A16 16 0 000 48v32a16 16 0 0016 16h416a16 16 0 0016-16V48a16 16 0 00-16-16z"/></svg>';
?>

<div class="wrapper">
    <?php for ($i = 0;
    $i < 1;
    $i++) { ?>
    <div class="one_third">
        <div class="card mb-3" style="width: 10rem;">
            <img class="card-img-top" src="<?php
            switch ($model->name) {
                case (preg_match('/\.docx\b/i', $model->name) ? true : false):
                    echo '/img/docx.png';
                    break;
                case (preg_match('/\.doc\b/i', $model->name) ? true : false):
                    echo '/img/doc.png';
                    break;
                case (preg_match('/.pdf/', $model->name) ? true : false):
                    echo '/img/pdf.png';
                    break;
                case (preg_match('/.xls/', $model->name) ? true : false):
                    echo '/img/xls.png';
                    break;
                case (preg_match('/.odt/', $model->name) ? true : false):
                    echo '/img/odt.png';
                    break;
                case (preg_match('/.ods/', $model->name) ? true : false):
                    echo '/img/ods.png';
                    break;
                case (preg_match('/.odp/', $model->name) ? true : false):
                    echo '/img/odp.png';
                    break;
                case (preg_match('/.rtf/', $model->name) ? true : false):
                    echo '/img/rtf.png';
                    break;
                case (preg_match('/.txt/', $model->name) ? true : false):
                    echo '/img/txt.png';
                    break;
            }
            ?>">
            <div class="card-body" style="padding: 4px">
                <h5 class="card-title" style="height: 5.5rem">
                    <b><?php echo \yii\helpers\StringHelper::truncate($model->name, 34, '...'); ?></b></h5>
                <p class="card-text"
                   style="height: 2rem"><?php echo "Тип: " . \app\models\Upload::$typeNames[$model->type] ?></p>
                <p class="card-text" style="height: 1rem"><?php echo Yii::$app->formatter->asDate($model->date) ?></p>
                <div class="card-body" style="padding: 6px">
                    <?php echo Html::a($eye, ['view', 'id' => $model->id]) ?>
                    <?php if ($model->user_id === Yii::$app->user->id || Yii::$app->user->can("admin")) {
                        echo Html::a($pencil, ['update', 'id' => $model->id]);
                    } ?>
                    <?php if ($model->user_id === Yii::$app->user->id || Yii::$app->user->can("admin")) {
                        echo Html::a($trash, ['delete', 'id' => $model->id], ['data' => ['method' => 'post'], 'data-confirm' => Yii::t('backend', 'Точно удалить файл?')]);
                    } ?>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>

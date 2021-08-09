<?php

use app\models\Upload;
use miloschuman\highcharts\Highcharts;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Upload */
/* @var $searchModel app\models\UploadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<hr/>
<h2 style="text-align: center">Загружено файлов</h2>
<hr/>
<table class="table">
    <thead class="thead-dark">
    <tr>
        <th scope="row"></th>
        <?php foreach (Upload::$intervalNames as $intervalName) : ?>
            <th scope="col"><?=$intervalName ?></th>
        <?php endforeach; ?>
    </tr>
    </thead>
    <tbody>
    <?php foreach (Upload::$typeNames as $typeCode => $typeName) : ?>
        <tr>
            <th scope="row"><?=$typeName ?></th>
            <?php foreach (Upload::$intervalNames as $intervalCode => $intervalName) : ?>
                <td>
                    <?=Upload::countFilesByPeriodAndType($intervalCode, $typeCode) ?>
                </td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
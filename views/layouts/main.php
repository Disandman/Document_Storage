<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header>
    <?php
    NavBar::begin([
        'brandLabel' => '<img src="/logo.png" class="pull-left"/>Хранение документов',
        'options' => [
            'class' => 'navbar navbar-light navbar-expand-lg',
            'style' => 'background-color: rgba(243,240,240,0.45);'
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav ml-auto mt-2 mt-lg-0'],
        'items' => [
            ['label' => 'Требования', 'url' => ['/site/index']],
            ['label' => 'Статистика', 'url' => ['/chart/index-alt'],
                'active' => Yii::$app->controller->id === 'chart',
                'visible' => !Yii::$app->user->isGuest,],
            ['label' => 'Файлы', 'url' => ['/upload/index']],
            [
                'label' => 'Пользователи', 'url' => ['/user/admin'],
                'visible' => Yii::$app->user->can('admin'),
                'active' => Yii::$app->controller->id === 'admin',
            ],
            Yii::$app->user->isGuest ?
                ['label' => 'Вход', 'url' => ['/user/security/login']] :

            ['label' => Yii::$app->user->identity->username,
            'items' => [
                ['label' => 'Аккаунт', 'url' => ['/user/settings/account '],
                    Yii::$app->controller->id === 'account'],
                ['label' => 'Выход', 'url' => ['/user/security/logout',],
                    'linkOptions' => ['data-method' => 'post']],
            ]
        ],]
    ]);
    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer class="footer mt-auto py-3 text-muted">
    <div class="container">
        <p class="pull text-center">&copy; <?= date('Y') ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

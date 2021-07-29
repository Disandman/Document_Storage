<?php

/* @var $this yii\web\View */

$this->title = 'Требования';
?>
<body onload="showLoader()">
<div id="loader"></div>
<section id="container" style="display:none;" class="animate-bottom">
<div class="site-index">
    <div class="col text-center">
        <h1 class="display-6">Приложение для хранения документов на PHP</h1>
        <hr>
        <p class="lead">Необходимо разработать приложение, которое позволит организовывать хранение документов с
            различными разрешениями.</p>
        <hr>
        <h3 class="display-6">Требования к функционалу:</h3>
        <ul class="list-group list-group-flush text-left">
            <li class="list-group-item">&#9675; Документы должны делиться на публичные (доступны «гостям»),
                условно-приватные (доступны авторизированным пользователям) и приватные (доступны только загрузившему
                пользователю).
            </li>
            <li class="list-group-item">&#9675; Необходимо предусмотреть функционал авторизации и регистрации.</li>
            <li class="list-group-item">&#9675; Необходимо вести некоторую статистику:</li>
            <li class="list-group-item">&#160;&#160;&#160;&#160;&#8722; Сколько документов загружено в день / месяц /
                год
            </li>
            <li class="list-group-item">&#160;&#160;&#160;&#160;&#8722; Соотношение публичных, условно-приватных и
                приватных документов за выбранный интервал времени.
            </li>
            <li class="list-group-item">&#9675; Бекэнд должен быть написан на Yii2</li>
        </ul>
        <h3 class="display-6">Роли в системе:</h3>
        <ul class="list-group list-group-flush text-left">
            <li class="list-group-item">&#9675; Гость – неавторизованный посетитель системы. Может просматривать
                публичные документы.
            </li>
            <li class="list-group-item">&#9675; Пользователь – посетитель, имеющий учетную запись в системе и
                авторизованный в ней. Может загружать свои документы, настраивать их приватность; просматривать
                публичные, условно-приватные и свои документы.
            </li>
            <li class="list-group-item">&#9675; Администратор – пользователь, имеющий права на просмотр всех файлов и на
                управление другими пользователями.
            </li>
            <hr>
        </ul>
        <h3 class="display-6">Дополнительно (по желанию):</h3>
        <ul class="list-group list-group-flush text-left">
            <li class="list-group-item">&#9675; Написание простых unit или acceptance тестов.</li>
        </ul>

    </div>
</div>
</section>
</body>

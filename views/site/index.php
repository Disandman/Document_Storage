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
                    условно-приватные (доступны авторизированным пользователям) и приватные (доступны только
                    загрузившему
                    пользователю).
                </li>
                <li class="list-group-item">&#9675; Необходимо предусмотреть функционал авторизации и регистрации.</li>
                <li class="list-group-item">&#9675; Необходимо вести некоторую статистику:</li>
                <li class="list-group-item">&#160;&#160;&#160;&#160;&#8722; Сколько документов загружено в день / месяц
                    /
                    год
                </li>
                <li class="list-group-item">&#160;&#160;&#160;&#160;&#8722; Соотношение публичных, условно-приватных и
                    приватных документов за выбранный интервал времени.
                </li>
                <li class="list-group-item">&#9675; Бекэнд должен быть написан на Yii2</li>
            </ul>
            <hr>
            <h3 class="display-6">Роли в системе:</h3>
            <ul class="list-group list-group-flush text-left">
                <li class="list-group-item">&#9675; Гость – неавторизованный посетитель системы. Может просматривать
                    публичные документы.
                </li>
                <li class="list-group-item">&#9675; Пользователь – посетитель, имеющий учетную запись в системе и
                    авторизованный в ней. Может загружать свои документы, настраивать их приватность; просматривать
                    публичные, условно-приватные и свои документы.
                </li>
                <li class="list-group-item">&#9675; Администратор – пользователь, имеющий права на просмотр всех файлов
                    и на
                    управление другими пользователями.
                </li>
                <hr>
            </ul>
            <h3 class="display-6">Дополнительно (по желанию):</h3>
            <ul class="list-group list-group-flush text-left">
                <li class="list-group-item">&#9675; Написание простых unit или acceptance тестов.</li>
            </ul>
            <ul class="list-group list-group-flush text-left">
                <li class="list-group-item">&#9675; Дополнительно реализовано Rest API. Точка входа-> <a
                            href="https://projectsil.ru/api_v1/upload">https://projectsil.ru/api_v1/upload</a></li>
            </ul>
            <ul class="list-group list-group-flush text-left">
                <li class="list-group-item">&#9675; Дополнительно реализована регистрация через свой почтовый сервер. <a
                            href="https://projectsil.ru/user/register">Проверить регистрацию.</a></li>
            </ul>
            <ul class="list-group list-group-flush text-left">
                <li class="list-group-item">&#9675; Дополнительно реализована аутентификация через OAuth. <a
                            href="https://projectsil.ru/user/auth?authclient=github">Проверить авторизацию.</a></li>
            </ul>
            <hr>
            <h3 class="display-6">Прогресс выполнения проекта</h3>
            <div class="py-3">
                <div class="progress">
                    <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 105%"
                         aria-valuenow="105" aria-valuemin="0" aria-valuemax="100">105%
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</body>

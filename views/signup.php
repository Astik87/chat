<?php

use components\helpers\Html;

?>

<div class="container">
    <div class="form">
        <div>
            <header class="form-header">
                <h1>Регистрация</h1>
            </header>
            <form action="http://<?= $_SERVER['SERVER_NAME'] ?>/user/signup" method="POST" class="form_main">
                <input name="name" placeholder="Имя">
                <input type="text" name="surname" placeholder="Фамилия">
                <input type="phone" name="phone" placeholder="Введите Ваш номер телефона">
                <input type="password" name="password" placeholder="Введите пароль">
                <input type="password" name="password_2" placeholder="Введите пароль ещё раз">
                <button type="submit" name="do_signup" class="form-btn">Войти</button>
            </form>
            <footer class="form-footer">
                <a href="./login.html" class="signup">Авторизация</a>
            </footer>
        </div>
    </div>
</div>
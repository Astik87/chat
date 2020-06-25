<div class="container">
    <div class="form">
        <div>
            <header class="form-header">
                <h1>Авторизация</h1>
            </header>
            <form action="http://<?= $_SERVER['SERVER_NAME']?>/user/signin" method="POST" class="form_main">
                <input type="phone" name="phone" placeholder="Введите Ваш номер телефона"><br>
                <input type="password" name="password" placeholder="Введите пароль"><br>
                <button type="submit" name="do_login" class="form-btn">Войти</button>
            </form>
            <footer class="form-footer">
                <a href="http://<?= $_SERVER['SERVER_NAME'] ?>/user/signup" class="signup">Регистрация</a>
                <a href="#" class="reset_password">Забыли пароль?</a>
            </footer>
        </div>
    </div>
</div>
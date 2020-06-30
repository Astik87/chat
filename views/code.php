<div class="form">
    <div>
        <header class="form-header">
            <h1>Подтверждение</h1>
        </header>
        <form action="http://<?= $_SERVER['SERVER_NAME']?>/user/code" method="POST" class="form_main">
            <input type="code" name="code" placeholder="Введите SMS пароль"><br>
            <button type="submit" name="verify" class="form-btn">Подтвердить</button>
        </form>
    </div>
</div>
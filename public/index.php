<?php
// Говорим, что нам нужны эти классы из папки "классы"
require_once '../classes/bdConnection.php';
require_once '../classes/User.php';
require_once '../classes/Department.php';

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Система управления пользователями</title>
    <link rel="stylesheet" href="../css/styles.css"> <!-- путь к CSS стилям, цветам -->
</head>
<body>
    <h1>Система управления пользователями</h1>

    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <h2>Регистрация</h2>
    <form method="POST" action="">
        <input type="text" name="username" placeholder="Имя пользователя" required>
        <input type="email" name="email" placeholder="Электронная почта" required>
        <input type="password" name="password" placeholder="Пароль" required>
        <input type="text" name="fio" placeholder="ФИО" required>
        <button type="submit" name="register">Зарегистрироваться</button>
    </form>

    <h2>Вход</h2>
    <form method="POST" action="">
        <input type="text" name="login_username" placeholder="Имя пользователя" required>
        <input type="password" name="login_password" placeholder="Пароль" required>
        <button type="submit" name="login">Войти</button>
    </form>

    <h2>Выход</h2>
    <form method="GET" action="">
        <button type="submit" name="logout">Выйти</button>
    </form>
</body>
</html>

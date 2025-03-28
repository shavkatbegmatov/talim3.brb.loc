<?php

if (user() !== false) {
    redirect('/');
}

$title = 'Авторизация';

if (!empty($_POST)) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = R::findOne('users', 'username = ?', [$username]);

    if ($user) {
        if ($user['password'] == md5($password)) {
            $_SESSION['user']['id'] = $user['id'];
            $_SESSION['user']['last_activity'] = time();
    
            redirect('/account/v/' . $username);
        }
    }

    $_SESSION['message'] = [
        'type' => 'danger',
        'text' => 'Имя пользователя и/или пароль введены неверно.'
    ];
}
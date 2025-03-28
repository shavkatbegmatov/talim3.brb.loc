<?php

if (user() === false) {
    redirect('/account/login');
}

if (!empty($_POST)) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];

    if (md5($current_password) == user()['password']) {
        $user = R::load('users', user()['id']);
        $user['password'] = md5($new_password);
        R::store($user);

        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Новый пароль сохранен!'
        ];
        
        redirect('/account/settings/password');
    } else {
        $_SESSION['message'] = [
            'type' => 'danger',
            'text' => 'Неверный пароль.'
        ];
    }
}
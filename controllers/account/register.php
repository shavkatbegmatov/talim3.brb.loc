<?php

if (user() !== false) {
    redirect('/');
}

$title = 'Регистрация';

if (!empty($_POST)) {
    if ($_POST['username'] != '' && $_POST['email'] != '' && $_POST['password'] != '') {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = md5($_POST['password']);

        if (!R::findOne('users', 'username = ?', [$username])) {
            if (!R::findOne('users', 'email = ?', [$email])) {
                $user = R::dispense('users');
                $user['username'] = $username;
                $user['email'] = $email;
                $user['password'] = $password;
                $id = R::store($user);
        
                $_SESSION['user']['id'] = $id;
                $_SESSION['user']['logged'] = time();
    
                redirect('/account/v/' . $username);
            } else {
                $_SESSION['message'] = [
                    'type' => 'danger',
                    'text' => 'Этот адрес электронной почты уже занят.'
                ];
            }
        } else {
            $_SESSION['message'] = [
                'type' => 'danger',
                'text' => 'Эта имя пользователя уже занята.'
            ];
        }

    }
}
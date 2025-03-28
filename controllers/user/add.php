<?php

if (user() === false) {
    redirect('/account/login');
}

if (user()['type'] != '1' && user()['type'] != '2') {
    redirect('/');
}

$branches = R::findAll('branches');

if (user()['type'] == '2') {
    $parentBranch = R::findOne('branches', 'id = ?', [user()['branch_id']]);
}

if (!empty($_POST)) {
    if ($_POST['username'] != '' && $_POST['email'] != '' && $_POST['name'] != '' && $_POST['position'] != '' && $_POST['phone'] != '' && $_POST['password'] != '') {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $name = $_POST['name'];
        $position = $_POST['position'];
        $phone = $_POST['phone'];
        $password = md5($_POST['password']);
        $type = '0';

        if ($_POST['type'] == '2') {
            $type = '2';
        } elseif ($_POST['type'] == '3') {
            $type = '3';
        }

        if (user()['type'] == '2') {
            $branch_id = user()['branch_id'];
        } elseif (user()['type'] == '3') {
            $branch_id = '0';
        } else {
            $branch_id = $_POST['branch_id'];
        }

        if (!R::findOne('users', 'username = ?', [$username])) {
            if (!R::findOne('users', 'email = ?', [$email])) {
                $user = R::dispense('users');
                $user['username'] = $username;
                $user['email'] = $email;
                $user['name'] = $name;
                $user['position'] = $position;
                $user['phone'] = $phone;
                $user['branch_id'] = $branch_id;
                $user['type'] = $type;
                $user['password'] = $password;
                $id = R::store($user);
    
                redirect('/user');
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
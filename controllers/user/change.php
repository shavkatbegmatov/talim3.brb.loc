<?php

if (user() === false) {
    redirect('/account/login');
}

if (user()['type'] == '0') {
    redirect('/');
}

$branches = R::findAll('branches');

if ($user = R::findOne('users', 'id = ?', [$data['id']])) {
    if (!empty($_POST)) {
        if ($_POST['username'] != '' && $_POST['email'] != '' && $_POST['name'] != '' && $_POST['position'] != '' && $_POST['phone'] != '') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $name = $_POST['name'];
            $position = $_POST['position'];
            $phone = $_POST['phone'];

            if ($user['type'] != '1') {
                $branch_id = $_POST['branch_id'];
            }

            if (!R::findOne('users', 'username = ? AND id != ?', [$username, $data['id']])) {
                if (!R::findOne('users', 'email = ? AND id != ?', [$email, $data['id']])) {
                    $user['username'] = $username;
                    $user['email'] = $email;
                    $user['name'] = $name;
                    $user['position'] = $position;
                    $user['phone'] = $phone;

                    if ($user['type'] != '1') {
                        $user['branch_id'] = $branch_id;
                    }
                    
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
} else {
    redirect('/user');
}
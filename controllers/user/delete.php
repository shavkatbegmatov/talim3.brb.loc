<?php

if (user() === false) {
    redirect('/account/login');
}

if (user()['type'] == '0') {
    redirect('/');
}

if ($user = R::findOne('users', 'id = ?', [$data['id']])) {
    if (user()['type'] == '2') {
        if ($user['branch_id'] != user()['branch_id']) {
            redirect('/');
        }
    }
    $user['deleted'] = 1;
    R::store($user);
}

redirect('/user');
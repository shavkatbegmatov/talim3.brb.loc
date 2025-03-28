<?php

if (user() === false) {
    redirect('/account/login');
}

if (user()['type'] == '0') {
    redirect('/');
}

$users = array_reverse(R::findAll('users', 'deleted = ?', [0]));

if (user()['type'] == '2') {
    $users = array_reverse(R::findAll('users', 'branch_id = ? AND type = ? AND deleted = ?', [user()['branch_id'], 0, 0]));
}
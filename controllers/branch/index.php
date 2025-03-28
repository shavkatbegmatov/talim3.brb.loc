<?php

if (user() === false) {
    redirect('/account/login');
}

if (user()['type'] != '1') {
    redirect('/');
}

$branches = array_reverse(R::findAll('branches', 'deleted = ?', [0]));
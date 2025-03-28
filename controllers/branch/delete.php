<?php

if (user() === false) {
    redirect('/account/login');
}

if (user()['type'] != '1') {
    redirect('/');
}

if ($branch = R::findOne('branches', 'id = ?', [$data['id']])) {
    $branch['deleted'] = 1;
    R::store($branch);
}

redirect('/branch');
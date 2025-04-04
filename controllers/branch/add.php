<?php

if (user() === false) {
    redirect('/account/login');
}

if (user()['type'] != '1') {
    redirect('/');
}

if (!empty($_POST)) {
    if ($_POST['code'] != '' && $_POST['name'] != '' && $_POST['address'] != '' && $_POST['phone'] != '') {
        $code = $_POST['code'];
        $name = $_POST['name'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];

        if (!R::findOne('branches', 'code = ?', [$code])) {
            $branch = R::dispense('branches');
            $branch['code'] = $code;
            $branch['name'] = $name;
            $branch['address'] = $address;
            $branch['phone'] = $phone;
            $id = R::store($branch);

            redirect('/branch');
        } else {
            $_SESSION['message'] = [
                'type' => 'danger',
                'text' => 'Этот код уже занят.'
            ];
        }

    }
}
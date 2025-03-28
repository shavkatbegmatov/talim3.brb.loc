<?php

if (user() === false) {
    redirect('/account/login');
}

if (user()['type'] == '1') {
    redirect('/');
}

if (!empty($_POST)) {
    if ($application = R::findOne('applications', 'id = ?', [$data['id']])) {
        if ($application['bxo_status'] == 0) {
            $application['bxo_status'] = 2;
            $application['bxo_status_reason'] = $_POST['reason'];
            R::store($application);

            $operation = R::dispense('operations');
            $operation['user_id'] = user()['id'];
            $operation['application_id'] = $data['id'];
            $operation['status'] = 2;
            R::store($operation);
        }
    }
    redirect('/application');
}

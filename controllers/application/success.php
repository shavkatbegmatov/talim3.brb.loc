<?php

if (user() === false) {
    redirect('/account/login');
}

if (user()['type'] == '1') {
    redirect('/');
}

if ($application = R::findOne('applications', 'id = ?', [$data['id']])) {
    if ($application['bxo_status'] == 1) {
        $application['bxo_status'] = 3;
        R::store($application);

        $operation = R::dispense('operations');
        $operation['user_id'] = user()['id'];
        $operation['application_id'] = $data['id'];
        $operation['status'] = 3;
        R::store($operation);
    }
}

redirect('/application');
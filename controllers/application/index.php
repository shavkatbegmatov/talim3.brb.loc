<?php

if (user() === false) {
    redirect('/account/login');
}

if (user()['type'] == '1' || user()['type'] == '3') {
    $applications = array_reverse(R::findAll('applications'));

    foreach ($applications as $application) {
        $statusHistory = R::findAll('operations', 'application_id = ? ORDER BY status', [$application['id']]);

        $application['status_history'] = $statusHistory;
    }
} else {
    $branch_code = R::findOne('branches', 'id = ?', [user()['branch_id']])['code'];
    $applications = array_reverse(R::findAll('applications', 'bank_branch_code = ?', [$branch_code]));

    foreach ($applications as $application) {
        $statusHistory = R::findAll('operations', 'application_id = ? ORDER BY status', [$application['id']]);

        $application['status_history'] = $statusHistory;
    }
}

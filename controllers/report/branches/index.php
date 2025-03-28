<?php

if (user() === false) {
    redirect('/account/login');
}

if (user()['type'] != '1') {
    redirect('/');
}

$date = $data['date'];
$dateStart = date('Y-m-01 00:00:00', strtotime($date));
$dateEnd = date('Y-m-t 23:59:59', strtotime($date));

$branches = R::findAll('branches', 'deleted = ?', [0]);

$statuses = [
    '0' => 'В ожидании',
    '1' => 'Принято',
    '2' => 'Отказано',
    '3' => 'Кредит выдан',
    '4' => 'Отменена',
];

$branchesData = [];

foreach ($branches as $branch) {
    $branchStatuses = [];

    foreach ($statuses as $statusCode => $statusName) {
        $applicationsCount = R::count('applications', 'bank_branch_code = ? AND bxo_status = ? AND created_at BETWEEN ? AND ?', [
            $branch['code'], 
            $statusCode, 
            $dateStart, 
            $dateEnd
        ]);
        $branchStatuses[$statusName] = (int) $applicationsCount;
    }

    $branchesData[] = [
        'name' => $branch['name'] ?? 'Unknown Branch',
        'statuses' => $branchStatuses
    ];
}
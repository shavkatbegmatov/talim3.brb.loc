<?php

set_time_limit(0);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Функция получения данных для запроса (оставляем без изменений)
function getRequestData($operationId)
{
    // 1. Ищем нужную запись в таблице operations
    $operation = R::findOne('operations', 'id = ?', [$operationId]);
    if (!$operation) {
        return ["error" => "Operation с ID $operationId не найдена"];
    }

    // 2. Получаем student_mf_id и bxo_status_reason из таблицы applications по application_id
    $application = R::findOne('applications', 'id = ?', [$operation->application_id]);
    if (!$application) {
        return ["error" => "Application для операции $operationId не найдена"];
    }
    $studentMfId = $application->student_mf_id;
    $reason      = $application->bxo_status_reason;

    // 3. Находим данные в таблице statuses по operations.status_mf
    $statusRow = R::findOne('statuses', 'id_brb = ?', [$operation->status]);
    if (!$statusRow) {
        return ["error" => "Статус для операции $operationId не найден"];
    }

    // 4. Извлекаем name_uz и id_mf
    $statusNameUz = $statusRow->name_uz;
    $idMf         = $statusRow->id_mf;

    // 5. Формируем comment: если reason не пуст и id_mf не равен 4, добавляем его в скобках
    if (!empty($reason) && $idMf != 4) {
        $comment = $statusNameUz . " (" . $reason . ")";
    } else {
        $comment = $statusNameUz;
    }

    // 6. Формируем итоговый массив
    $requestData = [
        "data" => [
            [
                "id"          => $studentMfId,
                "status_code" => $idMf,
                "comment"     => $comment
            ]
        ]
    ];

    return $requestData;
}

// Функция для выполнения cURL-запросов (без изменений)
function performCurlRequest($url, $method = 'GET', $data = null, $headers = [])
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    switch (strtoupper($method)) {
        case 'POST':
            curl_setopt($ch, CURLOPT_POST, true);
            if ($data !== null) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            }
            break;
        case 'GET':
            curl_setopt($ch, CURLOPT_HTTPGET, true);
            break;
        default:
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            if ($data !== null) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            }
            break;
    }

    if (!empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);

    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        return ['status' => 'failed', 'error' => "cURL Error: $error"];
    }

    $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return ['status' => $httpStatus, 'response' => $response];
}

// Аутентификация и получение токена (однократно, ведь токен – наше всё)
$authUrl = "https://talim-krediti.mf.uz/api/auth/signin";
$authData = [
    "username" => "01037",
    "password" => "\$DFg\$hBjK@mU72qOv"
];

$authResponse = performCurlRequest(
    $authUrl,
    'POST',
    json_encode($authData),
    ["Content-Type: application/json"]
);

$authResponseData = json_decode($authResponse['response'], true);
if (!isset($authResponseData["accessToken"])) {
    die("Аутентификация не удалась. Токен не получен.");
}

$token = $authResponseData["accessToken"];

// Задайте диапазон операционных ID, который вам необходим
$startOperId = 453; // Начало диапазона
$endOperId   = 2000; // Конец диапазона

// Цикл по диапазону operId
for ($operId = $startOperId; $operId <= $endOperId; $operId++) {
    echo "Обработка операции с ID: $operId<br>";

    $requestData = getRequestData($operId);
    if (isset($requestData['error'])) {
        echo "Ошибка для операции $operId: " . $requestData['error'] . "<br>";
        continue; // Переходим к следующему ID
    }

    // Выполняем cURL-запрос для обновления статуса
    $url = 'https://talim-krediti.mf.uz/api/student-application-rest/v2/update-app-status';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $token",
        'Content-Type: application/json',
        'Content-Length: ' . strlen(json_encode($requestData))
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'cURL ошибка для операции ' . $operId . ': ' . curl_error($ch) . "<br>";
        curl_close($ch);
        continue;
    }
    curl_close($ch);

    $responseData = json_decode($response, true);

    // Запись в лог
    foreach ($requestData['data'] as $item) {
        $log = R::dispense('log');
        $log->application_id = $item['id'];
        $log->operation_id = $operId;
        $log->status_mf   = $item['status_code'];
        $log->request_json  = json_encode($requestData, JSON_UNESCAPED_UNICODE);
        $log->response_json = json_encode($responseData, JSON_UNESCAPED_UNICODE);
        $log->created_at    = date('Y-m-d H:i:s');
        R::store($log);
    }

    // Запись в таблицу response4
    if (isset($responseData['data']) && is_array($responseData['data'])) {
        foreach ($responseData['data'] as $dataItem) {
            $appResponse = R::dispense('response4');
            $appResponse->agreement  = $dataItem['agreement'] ? 1 : 0;
            $appResponse->comment    = $dataItem['comment'];
            $appResponse->entry_date = date('Y-m-d H:i:s', strtotime($dataItem['entry_date']));
            $appResponse->education_info = json_encode($dataItem['education_info']);
            $appResponse->personal_data  = json_encode($dataItem['personal_data']);
            $appResponse->credit_details = json_encode($dataItem['credit_details']);
            R::store($appResponse);
        }
    }

    echo "Операция $operId успешно обработана!<br><br>";
}

echo "Все операции обработаны!<br>";

// Вывод последней записи из таблицы log
$latestLog = R::findLast('log');
echo "<h3>Последняя запись из таблицы log:</h3>";
echo "<pre>" . json_encode($latestLog, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "</pre>";

// Вывод последней записи из таблицы response4
$latestResponse = R::findLast('response4');
echo "<h3>Последняя запись из таблицы response4:</h3>";
echo "<pre>" . json_encode($latestResponse, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "</pre>";

exit;

redirect('/application');
?>

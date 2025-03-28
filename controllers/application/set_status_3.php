<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function getRequestData($operationId)
{
    // 1. operations jadvalidan kerakli yozuvni topamiz
    $operation = R::findOne('operations', 'id = ?', [$operationId]);
    if (!$operation) {
        return ["error" => "Operation topilmadi"];
    }

    // 2. operations.dagi application_id orqali applications jadvalidan student_mf_id va bxo_status_reason ni olamiz
    $application = R::findOne('applications', 'id = ?', [$operation->application_id]);
    if (!$application) {
        return ["error" => "Application topilmadi"];
    }
    $studentMfId = $application->student_mf_id;
    $reason      = $application->bxo_status_reason; // ( ) ichida yoziladigan sabab

    // 3. operations.status_code orqali statuses jadvalidan id_mf va name_uz ni topamiz
    //    Agar bog‘lanish 'id_brb' bo‘yicha bo‘lsa, 'id_brb = ?' deb yozasiz,
    //    agar 'id_mf' bo‘yicha bo‘lsa, 'id_mf = ?' deb yozasiz.
    //    Masalan, id_brb bo‘yicha:
    $statusRow = R::findOne('statuses', 'id_brb = ?', [$operation->status_code]);
    if (!$statusRow) {
        return ["error" => "Statuses jadvalidan mos keluvchi ma'lumot topilmadi"];
    }

    // 4. name_uz va id_mf qiymatlarini olamiz
    $statusNameUz = $statusRow->name_uz;
    $idMf         = $statusRow->id_mf;

    // 5. comment ni ikki qismda shakllantiramiz: name_uz + " (bxo_status_reason)"
    //    Agar bxo_status_reason bo‘sh bo‘lsa, shunchaki name_uz qaytarish mumkin
    if (!empty($reason)) {
        $comment = $statusNameUz . " (" . $reason . ")";
    } else {
        $comment = $statusNameUz;
    }

    // 6. Yakuniy massivni qaytarish
    $requestData = [
        "data" => [
            [
                // 'id' sifatida student_mf_id beriladi
                "id"          => $studentMfId,
                // status_code -> statuses jadvalidagi id_mf
                "status_code" => $idMf,
                // comment -> "name_uz (bxo_status_reason)"
                "comment"     => $comment
            ]
        ]
    ];

    return $requestData;
}

// Misol uchun:
$data = getRequestData(1);
print_r($data);



// Function to perform cURL requests
function performCurlRequest($url, $method = 'GET', $data = null, $headers = [])
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return response as string

    // Set request method
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

    // Set headers if provided
    if (!empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }

    // Disable SSL certificate verification (not recommended for production)
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    // Execute request
    $response = curl_exec($ch);

    // Check for errors
    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        return ['status' => 'failed', 'error' => "cURL Error: $error"];
    }

    // Get HTTP status code
    $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return ['status' => $httpStatus, 'response' => $response];
}

// 1st Step: Authentication and Token Retrieval
$authUrl = "https://talim-krediti.mf.uz/api/auth/signin";
$authData = [
    "username" => "01037",
    "password" => "\$DFg\$hBjK@mU72qOv" // Escaped special characters
];

// Perform authentication request
$authResponse = performCurlRequest(
    $authUrl,
    'POST',
    json_encode($authData),
    ["Content-Type: application/json"]
);

// Decode authentication response
$authResponseData = json_decode($authResponse['response'], true);
if (!isset($authResponseData["accessToken"])) {
    die("Authentication failed. Token not received.");
}

$token = $authResponseData["accessToken"];

// So'rov ma'lumotlari
$requestData = [
    "data" => [
        [
            "id" => 201292,
            "status_code" => 4,
            "comment" => "Qabul qilindi"
        ]
    ]
];

// cURL so'rovini sozlash
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
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // SSL tekshiruvini o'chirish

// So'rovni yuborish va javobni olish
$response = curl_exec($ch);
if(curl_errno($ch)) {
    die('cURL xatosi: ' . curl_error($ch));
}
curl_close($ch);

// Javobni JSON dan massivga aylantirish
$responseData = json_decode($response, true);

// Log jadvaliga yozish
foreach ($requestData['data'] as $item) {
    $log = R::dispense('log');
    $log->application_id = $item['id'];
    $log->status_code   = $item['status_code'];
    $log->request_json  = json_encode($requestData);
    $log->response_json = json_encode($responseData);
    $log->created_at    = date('Y-m-d H:i:s');
    R::store($log);
}

// Accept_app_response jadvaliga yozish
if (isset($responseData['data']) && is_array($responseData['data'])) {
    foreach ($responseData['data'] as $dataItem) {
        $appResponse = R::dispense('response4');

        // Asosiy maydonlar
        $appResponse->agreement  = $dataItem['agreement'] ? 1 : 0;
        $appResponse->comment    = $dataItem['comment'];
        $appResponse->entry_date = date('Y-m-d H:i:s', strtotime($dataItem['entry_date']));
        
        // JSON maydonlar
        $appResponse->education_info = json_encode($dataItem['education_info']);
        $appResponse->personal_data  = json_encode($dataItem['personal_data']);
        $appResponse->credit_details = json_encode($dataItem['credit_details']);
        
        R::store($appResponse);
    }
}

echo "Ma'lumotlar saqlandi!";

redirect('/application');
?>
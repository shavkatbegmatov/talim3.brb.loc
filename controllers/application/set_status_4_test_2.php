<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// require 'rb.php'; // RedBeanPHP faylini ulash

// MySQL ulanish sozlamalari
// R::setup('mysql:host=localhost;dbname=sizning_bazangiz', 'foydalanuvchi', 'parol');



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
            "id" => 20279,
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
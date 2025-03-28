<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$token = "eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiIwMTAzNyIsImlhdCI6MTc0MDM4MTQ2OCwiZXhwIjoxNzQwMzk1ODY4fQ.FjNjug5v9aWaGMdTmAj5mVBmXmUFGFOxcWgoE6cSFi0RSlSo6n7trAbZewVfXp3hIi2NyZKqlThR6XZ7LtirFw";

$url = 'https://talim-krediti.mf.uz/api/student-application-rest/v2/update-app-status';

$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['error' => 'Noto‘g‘ri yoki bo‘sh JSON payload']);
    exit;
}

$jsonData = json_encode($data);

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token",
    'Content-Type: application/json',
    'Content-Length: ' . strlen($jsonData)
]);

curl_setopt($ch, CURLOPT_USERPWD, "$authUser:$authPass");

// SSL ni o'chirish
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    echo 'cURL xatosi: ' . curl_error($ch);
}

curl_close($ch);

$result = json_decode($response, true);

// Javobni tayyorlash va JSON formatida chiqarish:
header('Content-Type: application/json');

if ($httpCode == 200) {
    if (isset($result['status']) && $result['status'] == 'OK') {
        echo json_encode([
            "status" => "OK",
            "data" => $result
        ]);
    } else {
        echo json_encode([
            "status" => "OK",
            "data" => $result
        ]);
    }
} elseif ($httpCode == 400) {
    echo json_encode([
        "status" => 400,
        "message" => isset($result['message']) ? $result['message'] : "Bad Request"
    ]);
} elseif ($httpCode == 401) {
    echo json_encode([
        "status" => 401,
        "message" => isset($result['message']) ? $result['message'] : "Unauthorized"
    ]);
} else {
    echo json_encode([
        "status" => $httpCode,
        "response" => $response
    ]);
}

?>
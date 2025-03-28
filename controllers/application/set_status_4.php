<?php
// APIga yuboriladigan so'rov ma'lumotlari
$apiUrl = "https://talim-krediti.mf.uz/api/student-application-rest/v2/update-app-status";
$postData = [
    "data" => [
        [
            "id"          => 198512,
            "status_code" => 4,
            "comment"     => "Qabul qilindi"
        ]
    ]
];

// cURL orqali APIga POST so'rov yuborish
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Request Error: ' . curl_error($ch);
    exit;
}
curl_close($ch);

// API javobini JSON formatda o'qib olish
$responseData = json_decode($response, true);

// MySQL ulanish parametrlarini sozlash
$host    = 'localhost';
$db      = 'your_database';
$user    = 'your_username';
$pass    = 'your_password';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Jadvalni yaratish (agar mavjud bo'lmasa)
// Bu jadvalda API dan qaytgan asosiy maydonlar va to'liq JSON saqlanadi
$createTableSql = "
CREATE TABLE IF NOT EXISTS student_applications (
    id INT PRIMARY KEY,
    application_count INT,
    status VARCHAR(10),
    comment TEXT,
    entry_date DATETIME,
    response_json JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";
$pdo->exec($createTableSql);

// API javobidagi ma'lumotlardan kerakli maydonlarni olish
// Misol uchun: 'data' massivining birinchi elementini olamiz
if (!empty($responseData['data'][0])) {
    $data = $responseData['data'][0];

    $id                = $data['id'];
    $comment           = $data['comment'];
    $entry_date        = $data['entry_date'];
    $application_count = $responseData['application-count'];
    $status            = $responseData['status'];
    // To'liq javobni JSON formatida saqlash
    $response_json     = $response;

    // Ma'lumotlarni jadvalga qo'shish (agar id allaqachon mavjud bo'lsa, yangilanadi)
    $insertSql = "
    INSERT INTO student_applications (id, application_count, status, comment, entry_date, response_json)
    VALUES (:id, :application_count, :status, :comment, :entry_date, :response_json)
    ON DUPLICATE KEY UPDATE
        application_count = VALUES(application_count),
        status = VALUES(status),
        comment = VALUES(comment),
        entry_date = VALUES(entry_date),
        response_json = VALUES(response_json)
    ";
    
    $stmt = $pdo->prepare($insertSql);
    $stmt->execute([
        ':id'                => $id,
        ':application_count' => $application_count,
        ':status'            => $status,
        ':comment'           => $comment,
        ':entry_date'        => $entry_date,
        ':response_json'     => $response_json
    ]);

    echo "Ma'lumotlar muvaffaqiyatli saqlandi.";
} else {
    echo "API javobida ma'lumot topilmadi.";
}
?>

<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Ensure user is authenticated and has the correct type
if (user() === false) {
    redirect('/account/login');
}

if (user()['type'] != '1') {
    redirect('/');
}

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

// Function to perform cURL requests with retry mechanism
function performCurlRequestWithRetry($url, $method = 'GET', $data = null, $headers = [], $maxRetries = 3, $retryDelay = 2)
{
    $attempt = 0;
    while ($attempt < $maxRetries) {
        $response = performCurlRequest($url, $method, $data, $headers);
        if ($response['status'] == 200) {
            return $response;
        }
        $attempt++;
        sleep($retryDelay); // Wait before retrying
    }
    return $response; // Return the last response
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

// 2nd Step: Sending Authenticated GET Request
$apiUrl = "https://talim-krediti.mf.uz/api/student-application-rest/v4/students-application-bank/";

$apiResponse = performCurlRequest(
    $apiUrl,
    'GET',
    null,
    [
        "Authorization: Bearer $token",
        "Content-Type: application/json"
    ]
);

// Decode API response
$apiData = json_decode($apiResponse['response'], true);

// Check if data is valid
if (is_array($apiData) && isset($apiData['data'])) {
    $studentMfIds = []; // Array to store student_mf_id values

    foreach ($apiData['data'] as $application) {
        // Collect student_mf_id for later use
        if (isset($application['id'])) {
            $studentMfIds[] = $application['id'];
        }

        // Check if record exists by student_mf_id
        $existing = R::findOne('applications', 'student_mf_id = ?', [$application['id']]);

        // If it exists, update the record; otherwise, create a new one
        if ($existing) {
            $applicationBean = $existing;
        } else {
            $applicationBean = R::dispense('applications');
        }

        // Populate the fields (ensure all necessary fields are handled)
        $applicationBean->student_mf_id = $application['id'];
        $applicationBean->mf_created_at = $application['created_at'];
        $applicationBean->pinfl = $application['pinfl'];
        $applicationBean->serial_number = $application['serial_number'];
        $applicationBean->additional_person_pinfl = $application['additional_person_pinfl'] ?? null;
        $applicationBean->additional_person_series = $application['additional_person_series'] ?? null;
        $applicationBean->additional_person_number = $application['additional_person_number'] ?? null;

        // Handle date fields with proper formatting
        $applicationBean->additional_person_birth_date = !empty($application['additional_person_birth_date'])
            ? DateTime::createFromFormat('d.m.Y', $application['additional_person_birth_date'])->format('Y-m-d')
            : null;

        $applicationBean->co_borrower_pinfl = $application['co_borrower_pinfl'] ?? null;
        $applicationBean->co_borrower_series = $application['co_borrower_series'] ?? null;
        $applicationBean->co_borrower_number = $application['co_borrower_number'] ?? null;
        $applicationBean->co_borrower_birth_date = !empty($application['co_borrower_birth_date'])
            ? DateTime::createFromFormat('d.m.Y', $application['co_borrower_birth_date'])->format('Y-m-d')
            : null;

        $applicationBean->pdf_link = $application['pdf_link'];
        $applicationBean->credit_sum = $application['credit_sum'];
        $applicationBean->phone_number = $application['phone_number'];
        $applicationBean->course = $application['course'];
        $applicationBean->education_year = $application['education_year'] ?? null;
        $applicationBean->bank_branch_name = $application['bank_branch_name'];
        $applicationBean->bank_branch_code = $application['bank_branch_code'];
        $applicationBean->single_register = (int)$application['single_register'];
        $applicationBean->university_name = $application['university_name'];
        $applicationBean->nationality_code = $application['nationality_code'];
        $applicationBean->nationality_name = $application['nationality_name'];
        $applicationBean->gender_name = $application['gender_name'];
        $applicationBean->birthday = $application['birthday'];
        $applicationBean->firstname = $application['firstname'];
        $applicationBean->lastname = $application['lastname'];
        $applicationBean->fathername = $application['fathername'];
        $applicationBean->region = $application['region'] ?? null;
        $applicationBean->district = $application['district'] ?? null;
        $applicationBean->address = $application['address'] ?? null;
        $applicationBean->address_current = $application['address_current'] ?? null;
        $applicationBean->edu_cours_id = $application['edu_cours_id'];
        $applicationBean->count_edu_year = $application['count_edu_year'] ?? null;
        $applicationBean->edu_period = $application['edu_period'];
        $applicationBean->mfo = $application['mfo'];
        $applicationBean->is_resident = $application['is_resident'];
        $applicationBean->given_date = !empty($application['given_date'])
            ? DateTime::createFromFormat('d.m.Y', $application['given_date'])->format('Y-m-d')
            : null;
        $applicationBean->citizenship_id = $application['citizenship_id'] ?? null;
        $applicationBean->gpa = $application['gpa'] ?? null;
        $applicationBean->status_id = $application['status_id'];
        $applicationBean->updated_at = R::isoDateTime();

        // Save the record
        R::store($applicationBean);
    }

    // 3rd Step: Inform API about fetched student_mf_ids
    if (!empty($studentMfIds)) {
        $informFetchedUrl = "https://talim-krediti.mf.uz/api/student-application-rest/inform-fetched";
        $informData = json_encode($studentMfIds);

        // Perform the send with retry mechanism
        $informResponse = performCurlRequestWithRetry(
            $informFetchedUrl,
            'POST',
            $informData,
            [
                "Authorization: Bearer $token", // Assuming the same token is required
                "Content-Type: application/json"
            ],
            1, // maxRetries
            0  // retryDelay in seconds
        );

        if ($informResponse['status'] == 200) {
            // Successfully informed the API
            // Record each send action with 'success' status
            foreach ($studentMfIds as $id) {
                $fetchedIdBean = R::dispense('fetchedids');
                $fetchedIdBean->student_mf_id = $id;
                $fetchedIdBean->sent_at = R::isoDateTime(); // Current timestamp
                $fetchedIdBean->send_status = 'success';
                $fetchedIdBean->error_message = null;
                R::store($fetchedIdBean);
            }
        } else {
            // Failed to inform the API after retries
            $errorMessage = "Failed to inform API about fetched IDs. HTTP Status: " . $informResponse['status'];
            if (isset($informResponse['error'])) {
                $errorMessage .= " | Error: " . $informResponse['error'];
            }

            // Record each send action with 'failed' status and error message
            foreach ($studentMfIds as $id) {
                $fetchedIdBean = R::dispense('fetchedids');
                $fetchedIdBean->student_mf_id = $id;
                $fetchedIdBean->sent_at = R::isoDateTime(); // Current timestamp
                $fetchedIdBean->send_status = 'failed';
                $fetchedIdBean->error_message = $errorMessage;
                R::store($fetchedIdBean);
            }

            // Optionally, you can log the error to a file or monitoring system here
            // For example:
            // error_log($errorMessage);

            die($errorMessage);
        }
    }

    // Redirect after processing
    redirect('/application');
}

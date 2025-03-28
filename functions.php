<?php

function debug($data) {
    echo '<pre>' . $data . '</pre>';
}

function redirect($url) {
    header('Location: ' . $url);
    exit();
}

function user() {
    if (isset($_SESSION['user'])) {
        $timestamp = $_SESSION['user']['last_activity'];
        $currentTimestamp = time();
        $secondsDifference = abs($currentTimestamp - $timestamp);

        if ($secondsDifference < (1800)) {
            $_SESSION['user']['last_activity'] = time();
            return R::findOne('users', 'id = ?', [$_SESSION['user']['id']]);
        } else {
            unset($_SESSION['user']);
            return false;
        }
    } else {
        return false;
    }
}
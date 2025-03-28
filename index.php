<?php

session_start();

const BASE_URL = __DIR__;

define('DEV_MODE', false); // Set to false for production

if (DEV_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

require 'connect/db.php';
require 'functions.php';
require 'router.php';

<?php

$db = require 'conf.php';
require 'rb4.php';

R::setup($db['dsn'], $db['user'], $db['pass']);
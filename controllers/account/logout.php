<?php

if (user() !== false) {
    unset($_SESSION['user']);
}

redirect('/account/login');
<?php

if (user() === false) {
    redirect('/account/login');
}
<?php

get('/', function () {
    render('index');
});

get('/application', function () {
    render('application/index');
});

get('/application/refresh', function () {
    render('application/refresh_application');
});

get('/application/set_status_4_test', function () {
    render('application/set_status_4_test');
});

get('/application/set_status_4_test_2', function () {
    render('application/set_status_4_test_2');
});

get('/application/set_status_3', function () {
    render('application/set_status_3');
});

get('/application/oracle', function () {
    render('application/test_conn_oracle_2');//--
});

get('/application/excel', function () {
    render('application/excel');
});

get('/application/accept/:id', function ($id) {
    render('application/accept', ['id' => $id]);
});

get('/application/success/:id', function ($id) {
    render('application/success', ['id' => $id]);
});

get('/application/cancel/:id', function ($id) {
    render('application/cancel', ['id' => $id]);
});

get('/application/reject/:id', function ($id) {
    render('application/reject', ['id' => $id]);
});

get('/branch', function () {
    render('branch/index');
});

get('/branch/add', function () {
    render('branch/add');
});

get('/branch/change/:id', function ($id) {
    render('branch/change', ['id' => $id]);
});

get('/branch/delete/:id', function ($id) {
    render('branch/delete', ['id' => $id]);
});

get('/report/branches/:date', function ($date) {
    render('report/branches/index', ['date' => $date]);
});

get('/user', function () {
    render('user/index');
});

get('/user/add', function () {
    render('user/add');
});

get('/user/change/:id', function ($id) {
    render('user/change', ['id' => $id]);
});

get('/user/delete/:id', function ($id) {
    render('user/delete', ['id' => $id]);
});

get('/account/v/:username', function ($username) {
    render('account/index', ['username' => $username]);
});

get('/account/login', function () {
    render('account/login', 'auth');
});

get('/account/settings/password', function () {
    render('account/settings/password');
});

get('/account/logout', function () {
    render('account/logout');
});

get('*', function () {
    render('404', 'blank');
});

dispatch();

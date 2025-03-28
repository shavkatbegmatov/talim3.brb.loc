<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRB<?php if (!empty($title)) echo (' | ' . $title) ?></title>

    <link rel="icon" type="image/png" href="/assets/static/logo-small.png">

    <link rel="stylesheet" href="/assets/dist/css/tabler.min.css">
    <link rel="stylesheet" href="/assets//dist/css/tabler-flags.min.css">
    <link rel="stylesheet" href="/assets//dist/css/tabler-payments.min.css">
    <link rel="stylesheet" href="/assets//dist/css/tabler-vendors.min.css">
    <link rel="stylesheet" href="/assets//dist/css/demo.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap');

        * {
            border-radius: 0px !important;
            font-family: "IBM Plex Mono", monospace !important;
        }
    </style>
</head>

<body class="d-flex flex-column theme-dark">
    <script src="/assets//dist/js/demo-theme.min.js"></script>

    <?php echo $content; ?>

    <script src="/assets//dist/js/tabler.min.js"></script>
    <script src="/assets//dist/js/demo.min.js"></script>
</body>

</html>
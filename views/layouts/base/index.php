<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>BRB<?php if (!empty($title)) echo (' | ' . $title) ?></title>
        <link rel="icon" type="image/png" href="/assets/static/logo-small.png">
        <link rel="stylesheet" href="/assets/dist/css/tabler.min.css">
        <link rel="stylesheet" href="/assets/dist/css/tabler-flags.min.css">
        <link rel="stylesheet" href="/assets/dist/css/tabler-payments.min.css">
        <link rel="stylesheet" href="/assets/dist/css/tabler-vendors.min.css">
        <link rel="stylesheet" href="/assets/dist/css/demo.min.css">

        <style>
            @import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap');

            * {
                border-radius: 0px !important;
                font-family: "IBM Plex Mono", monospace !important;
            }

            body {
                overflow-y: scroll;
            }

            .table-hover tr {
                cursor: pointer;
            }

            .text-c {
                text-transform: lowercase;
            }

            .text-c:first-letter,
            .text-c:first-line {
                text-transform: uppercase;
            }

            .navbar svg {
                color: #fff !important;
            }
        </style>
    </head>
    <body class="layout-fluid" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 16px, #ededed 16px, #ededed 32px); background-color: #ffffff;">
        <script src="/assets/dist/js/demo-theme.min.js"></script>
        <div class="page">
            <!-- Sidebar -->
            <aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark" style="background-image: repeating-linear-gradient(45deg, hsla(207,0%,63%,0.05) 0px, hsla(207,0%,63%,0.05) 1px,transparent 1px, transparent 11px,hsla(207,0%,63%,0.05) 11px, hsla(207,0%,63%,0.05) 12px,transparent 12px, transparent 32px),repeating-linear-gradient(0deg, hsla(207,0%,63%,0.05) 0px, hsla(207,0%,63%,0.05) 1px,transparent 1px, transparent 11px,hsla(207,0%,63%,0.05) 11px, hsla(207,0%,63%,0.05) 12px,transparent 12px, transparent 32px),repeating-linear-gradient(135deg, hsla(207,0%,63%,0.05) 0px, hsla(207,0%,63%,0.05) 1px,transparent 1px, transparent 11px,hsla(207,0%,63%,0.05) 11px, hsla(207,0%,63%,0.05) 12px,transparent 12px, transparent 32px),repeating-linear-gradient(90deg, hsla(207,0%,63%,0.05) 0px, hsla(207,0%,63%,0.05) 1px,transparent 1px, transparent 11px,hsla(207,0%,63%,0.05) 11px, hsla(207,0%,63%,0.05) 12px,transparent 12px, transparent 32px),linear-gradient(90deg, rgb(17, 17, 17),rgb(66, 66, 66));">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu" aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="navbar-brand">
                        <a href="/">
                            <img src="/assets/static/logo.png" class="navbar-brand-image" style="width: 180px; height: 50px;" alt="logo">
                        </a>
                    </div>
                    <div class="navbar-nav flex-row d-lg-none">
                        <div class="nav-item d-none d-lg-flex me-3">
                            <div class="btn-list">
                                <a href="https://github.com/tabler/tabler" class="btn" target="_blank" rel="noreferrer">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/brand-github -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M9 19c-4.3 1.4 -4.3 -2.5 -6 -3m12 5v-3.5c0 -1 .1 -1.4 -.5 -2c2.8 -.3 5.5 -1.4 5.5 -6a4.6 4.6 0 0 0 -1.3 -3.2a4.2 4.2 0 0 0 -.1 -3.2s-1.1 -.3 -3.5 1.3a12.3 12.3 0 0 0 -6.2 0c-2.4 -1.6 -3.5 -1.3 -3.5 -1.3a4.2 4.2 0 0 0 -.1 3.2a4.6 4.6 0 0 0 -1.3 3.2c0 4.6 2.7 5.7 5.5 6c-.6 .6 -.6 1.2 -.5 2v3.5"></path>
                                    </svg>
                                    Source code
                                </a>
                                <a href="https://github.com/sponsors/codecalm" class="btn" target="_blank" rel="noreferrer">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/heart -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-pink">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572"></path>
                                    </svg>
                                    Sponsor
                                </a>
                            </div>
                        </div>
                        <div class="d-none d-lg-flex">
                            <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" data-bs-toggle="tooltip" data-bs-placement="bottom" aria-label="Enable dark mode" data-bs-original-title="Enable dark mode">
                                <!-- Download SVG icon from http://tabler-icons.io/i/moon -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z"></path>
                                </svg>
                            </a>
                            <a href="?theme=light" class="nav-link px-0 hide-theme-light" data-bs-toggle="tooltip" data-bs-placement="bottom" aria-label="Enable light mode" data-bs-original-title="Enable light mode">
                                <!-- Download SVG icon from http://tabler-icons.io/i/sun -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                                    <path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7"></path>
                                </svg>
                            </a>
                            <div class="nav-item dropdown d-none d-md-flex me-3">
                                <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1" aria-label="Show notifications">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/bell -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6"></path>
                                        <path d="M9 17v1a3 3 0 0 0 6 0v-1"></path>
                                    </svg>
                                    <span class="badge bg-red"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Last updates</h3>
                                        </div>
                                        <div class="list-group list-group-flush list-group-hoverable">
                                            <div class="list-group-item">
                                                <div class="row align-items-center">
                                                    <div class="col-auto"><span class="status-dot status-dot-animated bg-red d-block"></span></div>
                                                    <div class="col text-truncate">
                                                        <a href="#" class="text-body d-block">Example 1</a>
                                                        <div class="d-block text-secondary text-truncate mt-n1">
                                                            Change deprecated html tags to text decoration classes (#29604)
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <a href="#" class="list-group-item-actions">
                                                            <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-muted">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                <path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="list-group-item">
                                                <div class="row align-items-center">
                                                    <div class="col-auto"><span class="status-dot d-block"></span></div>
                                                    <div class="col text-truncate">
                                                        <a href="#" class="text-body d-block">Example 2</a>
                                                        <div class="d-block text-secondary text-truncate mt-n1">
                                                            justify-content:between ⇒ justify-content:space-between (#29734)
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <a href="#" class="list-group-item-actions show">
                                                            <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-yellow">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                <path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="list-group-item">
                                                <div class="row align-items-center">
                                                    <div class="col-auto"><span class="status-dot d-block"></span></div>
                                                    <div class="col text-truncate">
                                                        <a href="#" class="text-body d-block">Example 3</a>
                                                        <div class="d-block text-secondary text-truncate mt-n1">
                                                            Update change-version.js (#29736)
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <a href="#" class="list-group-item-actions">
                                                            <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-muted">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                <path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="list-group-item">
                                                <div class="row align-items-center">
                                                    <div class="col-auto"><span class="status-dot status-dot-animated bg-green d-block"></span></div>
                                                    <div class="col text-truncate">
                                                        <a href="#" class="text-body d-block">Example 4</a>
                                                        <div class="d-block text-secondary text-truncate mt-n1">
                                                            Regenerate package-lock.json (#29730)
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <a href="#" class="list-group-item-actions">
                                                            <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-muted">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                <path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="collapse navbar-collapse" id="sidebar-menu">
                        <ul class="navbar-nav pt-lg-3">
                            <li class="nav-item">
                                <a class="nav-link" href="/">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M19 8.71l-5.333 -4.148a2.666 2.666 0 0 0 -3.274 0l-5.334 4.148a2.665 2.665 0 0 0 -1.029 2.105v7.2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-7.2c0 -.823 -.38 -1.6 -1.03 -2.105" />
                                            <path d="M16 15c-2.21 1.333 -5.792 1.333 -8 0" />
                                        </svg>
                                    </span>

                                    <span class="nav-link-title">
                                        Главная страница
                                    </span>
                                </a>
                            </li>
                            <?php if (user()): ?>
                                <?php if (user()['type'] == '1'): ?>
                                    <li class="nav-item dropdown show">
                                        <a class="nav-link dropdown-toggle show" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="true">
                                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <path d="M3 21h18" />
                                                    <path d="M19 21v-4" />
                                                    <path d="M19 17a2 2 0 0 0 2 -2v-2a2 2 0 1 0 -4 0v2a2 2 0 0 0 2 2z" />
                                                    <path d="M14 21v-14a3 3 0 0 0 -3 -3h-4a3 3 0 0 0 -3 3v14" />
                                                    <path d="M9 17v4" />
                                                    <path d="M8 13h2" />
                                                    <path d="M8 9h2" />
                                                </svg>
                                            </span>

                                            <span class="nav-link-title">
                                                Филиал
                                            </span>
                                        </a>
                                        <div class="dropdown-menu show">
                                            <div class="dropdown-menu-columns">
                                                <div class="dropdown-menu-column">
                                                    <a class="dropdown-item" href="/branch">
                                                        Таблица филиалов
                                                    </a>
                                                    <a class="dropdown-item" href="/branch/add">
                                                        Добавить филиал
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                <?php endif; ?>
                                <?php if (user()['type'] != '0'): ?>
                                    <?php if (user()['type'] != '3'): ?>
                                        <li class="nav-item dropdown show">
                                            <a class="nav-link dropdown-toggle show" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="true">
                                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                                        <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                                        <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                                                    </svg>
                                                </span>

                                                <span class="nav-link-title">
                                                    Администрация
                                                </span>
                                            </a>
                                            <div class="dropdown-menu show">
                                                <div class="dropdown-menu-columns">
                                                    <div class="dropdown-menu-column">
                                                        <a class="dropdown-item" href="/user">
                                                            Таблица админов
                                                        </a>
                                                        <a class="dropdown-item" href="/user/add">
                                                            Добавить админа
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="/application">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M4 3h16" />
                                                <path d="M4 9h16" />
                                                <path d="M4 15h16" />
                                                <path d="M4 21h16" />
                                            </svg>
                                        </span>

                                        <span class="nav-link-title">
                                            Таблица заявок
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item dropdown show">
                                    <a class="nav-link dropdown-toggle show" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="true">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                                                <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                                <path d="M9 12h6" />
                                                <path d="M9 16h6" />
                                            </svg>
                                        </span>

                                        <span class="nav-link-title">
                                            Отчет
                                        </span>
                                    </a>
                                    <div class="dropdown-menu show">
                                        <div class="dropdown-menu-columns">
                                            <div class="dropdown-menu-column">
                                                <a class="dropdown-item" href="/report/branches/<?php echo date('Y-m'); ?>">
                                                    Отчет по месяцам
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/account/v/<?php echo user()['username']; ?>">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                                <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                            </svg>
                                        </span>

                                        <span class="nav-link-title">
                                            Учетная запись
                                        </span>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="/account/login">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                                <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                            </svg>
                                        </span>

                                        <span class="nav-link-title">
                                            Авторизация
                                        </span>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <li class="nav-item mt-auto">
                                <a class="nav-link">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                                            <path d="M12 9h.01" />
                                            <path d="M11 12h1v4h1" />
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">
                                        Версия 2.0
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </aside>
            <header class="navbar navbar-expand-md d-none d-lg-flex d-print-none">
                <div class="container-xl">
                    <?php if (user()['type'] != '1'): ?>
                        <?php
                            $branch = R::findOne('branches', 'id = ?', [user()['branch_id']]);
                        ?>
                        <div class="navbar-nav me-auto flex-row">
                            <div class="nav-item mr-1">
                                <div class="nav-link d-flex lh-1 text-reset p-0">
                                    <div class="d-none d-xl-block ps-2">
                                        <div><?php echo $branch['name']; ?></div>
                                        <div class="mt-1 small text-secondary"><?php echo $branch['code']; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="navbar-nav me-auto flex-row">
                            <div class="nav-item mr-1">
                                <div class="nav-link d-flex lh-1 text-reset p-0">
                                    <div class="d-none d-xl-block ps-2">
                                        <div>Biznesni Rivojlantirish Banki</div>
                                        <div class="mt-1 small text-secondary">Super Admin</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (DEV_MODE === true): ?>
                        <div class="nav-item">
                            <span class="badge bg-blue-lt">Режим разработки</span>
                        </div>
                    <?php endif; ?>
                    <div class="navbar-nav ms-auto flex-row">
                        <div class="nav-item ml-1">
                            <div class="nav-link d-flex lh-1 text-reset p-0">
                                <div class="d-none d-xl-block ps-2">
                                    <div><?php echo user()['name']; ?></div>
                                    <div class="mt-1 small text-secondary"><?php echo user()['username']; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="container-xl">
                        <?php echo $content; ?>
                    </div>
                </div>
                <footer class="footer footer-transparent d-print-none">
                    <div class="container-xl">
                        <div class="row text-center align-items-center flex-row-reverse">
                            <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                                <ul class="list-inline list-inline-dots mb-0">
                                    <li class="list-inline-item">
                                        Copyright
                                        <a href="https" class="link-secondary">Biznesni Rivojlantirish Banki</a>.
                                        Все права защищены.
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="/assets/dist/js/tabler.min.js"></script>
        <script src="/assets/dist/js/demo.min.js"></script>
    </body>
</html>
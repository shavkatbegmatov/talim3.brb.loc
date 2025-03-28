<?php

$routes = [];

function get($route, $callback) {
    global $routes;

    $routes[] = [
        'pattern' => $route,
        'callback' => $callback,
    ];
}

function dispatch() {
    global $routes;

    // Get the URI and remove query parameters
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // Remove trailing slashes except for the root
    if ($uri !== '/') {
        $uri = rtrim($uri, '/');
    }

    foreach ($routes as $route) {
        // Convert route pattern to regex
        if ($route['pattern'] === '*') {
            $pattern = '.*'; 
        } else {
            $pattern = str_replace('/', '\/', $route['pattern']);
            $pattern = preg_replace('/:(\w+)/', '([^\/]+)', $pattern);
        }

        // Match the URI against the pattern
        if (preg_match('/^' . $pattern . '$/', $uri, $matches)) {
            array_shift($matches);

            call_user_func_array($route['callback'], $matches);
            return;
        }
    }
}

function render($path, $data = [], $layout = 'base') {
    if (is_string($data)) {
        $layout = $data;
        $data = [];
    }
    
    if (file_exists('controllers/' . $path . '.php')) {
        include 'controllers/' . $path . '.php';
    }

    ob_start();
    
    if (file_exists('views/' . $path . '.php')) {
        include 'views/' . $path . '.php';
    }
    
    $content = ob_get_clean();

    if (isset($title)) {
        $data['title'] = $title;
    }
    
    include 'views/layouts/' . $layout . '/index.php';
}

require 'routes.php';

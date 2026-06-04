<?php
// router.php for PHP built-in web server
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$ext = pathinfo($path, PATHINFO_EXTENSION);

if ($ext && file_exists(__DIR__ . $path)) {
    return false; // serve the requested resource as-is
}

$_SERVER['SCRIPT_NAME'] = '/index.php';
include __DIR__ . '/index.php';

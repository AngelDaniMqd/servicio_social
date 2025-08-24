<?php
// server-https.php

// Forzar HTTPS para Laravel
$_SERVER['HTTPS'] = 'on';
$_SERVER['SERVER_PORT'] = 8443;
$_SERVER['REQUEST_SCHEME'] = 'https';

// Reenviar al index.php público
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false;
}

require_once __DIR__.'/public/index.php';

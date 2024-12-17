<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Authorization");
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Gestisci le richieste OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Per le richieste API
$requestUri = $_SERVER['REQUEST_URI'];

if (strpos($requestUri, '/api/') === 0) {
    header('Content-Type: application/json');
    require_once __DIR__ . '/back/routes/api.php';
} else {
    // Gestisci le richieste non API (ad esempio, pagine HTML)
    require_once __DIR__ .'/front/view/top.php';
    require_once __DIR__ .'/front/view/footer.php';
}
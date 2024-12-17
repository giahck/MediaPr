<?php

require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

$authController = new AuthController();

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];
/* header('Content-Type: application/json'); */

switch (true) {
    case ($requestUri === '/api/register' && $requestMethod === 'POST'):
        $data = json_decode(file_get_contents('php://input'), true);
        error_log("Dati ricevuti: " . print_r($data, true));
        $authController->register($data);
        break;
    case ($requestUri === '/api/login' && $requestMethod === 'POST'):
        $data = json_decode(file_get_contents('php://input'), true);
       /*  error_log("Dati ricevuti: " . print_r($data, true)); */
        $authController->login($data['email'], $data['password']);
        break;

    case ($requestUri === '/api/logout' && $requestMethod === 'POST'):
        $headers = getallheaders();
        $account = AuthMiddleware::verifyToken($headers);
        $authController->logout($account );
        break;
    
    case ($requestUri === '/api/protected' && $requestMethod === 'GET'):
        $headers = getallheaders();
        $account = AuthMiddleware::verifyToken($headers);
        echo json_encode(['id'=>(int)$account['id'],'token'=>$account['token']]);
        break;

    case ($requestUri === '/'):
        echo json_encode(['message' => 'Benvenuto nella mia API']);
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => 'Endpoint non trovato']);
        break;
}
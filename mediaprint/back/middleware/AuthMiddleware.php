<?php

require_once __DIR__ . '/../models/Account.php';
require_once __DIR__ . '/token.php';

class AuthMiddleware {
    public static function verifyToken($headers) {
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Autorizzazione mancante']);
            exit;
        }

        $authHeader = $headers['Authorization'];
        list($type, $token) = explode(' ', $authHeader);

        if (strcasecmp($type, 'Bearer') != 0) {
            http_response_code(401);
            echo json_encode(['error' => 'Tipo di autorizzazione non valido']);
            exit;
        }

        // Verifica il token usando TokenService
        $tokenService = new TokenService();
        $result = $tokenService->verifyJWT($token);

        if (!$result['valid']) {
            http_response_code(403);
            echo json_encode(['error' => $result['error']]);
            exit;
        }
     
        $userModel = new Account();
        $user = $userModel->findByToken($token);
        if (!$user) {
            http_response_code(403);
            echo json_encode(['err or' => 'Token non valido']);
            exit;
        }

        // Ritorna l'utente autenticato
        return $user;
    }
}
<?php

require_once __DIR__ . '/../models/Account.php';
require_once __DIR__ . '/../middleware/token.php';

class AuthController {
    private $userModel;
    private $tokenService;
    public function __construct() {
        $this->userModel = new Account();
        $this->tokenService = new TokenService();
    }

    public function login($email, $password) {
        try {
            $user = $this->userModel->findByEmailandAnagrafica($email);
         
            if ($user && password_verify($password, $user['password'])) {
                $token = $this->tokenService->createJWT($user['account_id']);




                $this->userModel->updateToken($user['account_id'], $token);
                echo json_encode([
                    'token' => $token,
                    'id' => $user['account_id'],
                    'anagrafica' => [
                        'nome' => $user['nome'],
                        'cognome' => $user['cognome'],
                        'data_nascita' => $user['data_nascita'],
                        'telefono' => $user['telefono'],
                        'indirizzo' => $user['indirizzo']
                    ]
                ]);
            } else {
                http_response_code(401);
                echo json_encode(['error' => 'Invalid credentials']);
            }
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function logout($account) {
        
      
        if ($account) {
            $this->userModel->updateToken($account['id'], null);
            echo json_encode(['message' => 'Logout effettuato']);
        } else {
            http_response_code(401);
            echo json_encode(['error' => 'Token non valido']);
        }
    }
    public function register($data) {
        try {
            // Inizia una transazione
            $this->userModel->beginTransaction();
    
            // Controlla se l'email è già registrata
            $existingUser = $this->userModel->findByEmail($data['email']);
            if ($existingUser) {
                http_response_code(400);
                echo json_encode(['error' => 'Email già registrata']);
                return;
            }
           // error_log("Dati ricevuti: " . print_r($data, true));
            
            // Crea un nuovo account nella tabella `account`
            $accountId = $this->userModel->createAccount($data['email'], $data['password']);
            if (!$accountId) {
                throw new Exception("Errore durante la registrazione dell'account");
            }
            
            
          //  error_log("Dati accountId " . print_r( $accountId, true));
            // Crea i dati dell'anagrafica nella tabella `anagrafica`
            $anagraficaCreated = $this->userModel->createAnagrafica($accountId, $data);
            if (!$anagraficaCreated) {
                throw new Exception("Errore durante l'inserimento dei dati anagrafici");
            }
    
            // Conferma la transazione
            $this->userModel->commit();
          //  error_log("Dati accountId " . print_r( $accountId, true));
            echo json_encode(['message' => 'Registrazione avvenuta con successo']);
        } catch (Exception $e) {
            // Annulla la transazione in caso di errore
            $this->userModel->rollback();
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    
}
    
}
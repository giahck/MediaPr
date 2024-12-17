<?php

require_once __DIR__ . '/../config/database.php';

class Account {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    public function beginTransaction() {
        $this->db->beginTransaction();
    }
    public function commit() {
        $this->db->commit();
    }

    public function rollback() {
        $this->db->rollBack();
    }
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM account WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function findByEmailandAnagrafica($email) {
        $stmt = $this->db->prepare("
            SELECT 
                account.id AS account_id, account.email, account.password, account.token,anagrafica.nome,anagrafica.cognome,anagrafica.data_nascita,anagrafica.telefono,anagrafica.indirizzo
            FROM 
                account
            LEFT JOIN 
                anagrafica ON account.id = anagrafica.account_id
            WHERE 
                account.email = :email
        ");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Restituisce un array associativo con i dati
    }
    public function createAccount($email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO account (email, password) VALUES (?, ?)");
        if ($stmt->execute([$email, $hashedPassword])) {
            return $this->db->lastInsertId(); 
        }
        return false; 
    }
    public function createAnagrafica( $accountId, $data) {
        $stmt = $this->db->prepare("INSERT INTO anagrafica (account_id, nome, cognome, data_nascita, telefono, indirizzo) 
        VALUES (:account_id, :nome, :cognome, :data_nascita, :telefono, :indirizzo)");
    $stmt->bindParam(':account_id', $accountId);
    $stmt->bindParam(':nome', $data['nome']);
    $stmt->bindParam(':cognome', $data['cognome']);
    $stmt->bindParam(':data_nascita', $data['data_nascita']);
    $stmt->bindParam(':telefono', $data['telefono']);
    $stmt->bindParam(':indirizzo', $data['indirizzo']);
    
    return $stmt->execute();
    }


    public function updateToken($id, $token) {
        $stmt = $this->db->prepare("UPDATE account SET token = ?, token_expiry = ? WHERE id = ?");
        $stmt->execute([$token, date('Y-m-d H:i:s', strtotime('+1 hour')), $id]);
    }

    public function findByToken($token) {
        $stmt = $this->db->prepare("SELECT * FROM account WHERE token = ? ");
        $stmt->execute([$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
<?php



class DatabaseSetup {
    private $db;
    private $tableQueries;

    public function __construct($db) {
        $this->db = $db;
        $this->createDatabase();

        // Definizione delle query per la creazione delle tabelle
        $this->tableQueries = [
            'account' => "
                CREATE TABLE IF NOT EXISTS account (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    email VARCHAR(255) NOT NULL UNIQUE,
                    password VARCHAR(255) NOT NULL,
                    token VARCHAR(255) DEFAULT NULL,
                    token_expiry DATETIME DEFAULT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            ",
            'anagrafica' => "
                CREATE TABLE IF NOT EXISTS anagrafica (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    account_id INT NOT NULL,
                    nome VARCHAR(100) NOT NULL,
                    cognome VARCHAR(100) NOT NULL,
                    data_nascita DATE NOT NULL,
                    telefono VARCHAR(15),
                    indirizzo TEXT,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (account_id) REFERENCES account(id) ON DELETE CASCADE
                )
            "
        ];

        // Creazione automatica delle tabelle
        foreach ($this->tableQueries as $tableName => $query) {
            $this->execTable($tableName, $query);
        }
    }

    private function execTable($tableName, $createTableSql) {
        if (!$this->tableExists($tableName)) {
            try {
                $this->db->exec($createTableSql);
                echo "Tabella '$tableName' creata con successo.\n";
            } catch (PDOException $e) {
                die("Errore durante la creazione della tabella '$tableName': " . $e->getMessage());
            }
        } else {
            echo "La tabella '$tableName' esiste già.\n";
        }
    }
    private function createDatabase() {
        try {
            $this->db->exec("CREATE DATABASE IF NOT EXISTS app_database");
            echo "Database 'app_database' creato (se non esisteva già).\n";
        } catch (PDOException $e) {
            echo "Errore durante la creazione del database: " . $e->getMessage() . "\n";
        }
    }
    private function tableExists($tableName) {
        try {
            $result = $this->db->query("SELECT 1 FROM $tableName LIMIT 1");
        } catch (PDOException $e) {
            return false;
        }
        return $result !== false;
    }
}
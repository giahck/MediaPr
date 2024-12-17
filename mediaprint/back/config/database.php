<?php
require_once 'DatabaseSetup.php';

class Database {
    private static $instance = null;
    private $connection;

    private $host = 'mysql-db'; // Cambia con l'host del tuo database
    private $dbName = 'app_database'; // Cambia con il nome del tuo database
    private $username = 'root'; // Cambia con il tuo username
    private $password = 'rootpassword'; // Cambia con la tua password

    /**
     * Costruttore privato per evitare istanziazioni multiple.
     */
    private function __construct() {
        try {
            $this->connection = new PDO(
                "mysql:host={$this->host};dbname={$this->dbName};charset=utf8",
                $this->username,
                $this->password
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $setup = new DatabaseSetup($this->connection);
        } catch (PDOException $e) {
            die("Errore di connessione al database: " . $e->getMessage());
        }
    }

    /**
     * Ottiene l'istanza Singleton della classe `Database`.
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Restituisce la connessione PDO al database.
     */
    public function getConnection() {
        return $this->connection;
    }

    /**
     * Impedisce la clonazione dell'istanza.
     */
    private function __clone() {}

    /**
     * Impedisce la serializzazione dell'istanza.
     */
    public function __wakeup() {}
}

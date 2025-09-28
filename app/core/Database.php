<?php
declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private ?PDO $pdo = null;

    public function __construct(
        private string $driver = 'mysql',
        private string $host = 'localhost',
        private string $name = 'database',
        private ?string $user = null,
        private ?string $password = null
    ) {
    }

    public function getConnection(): PDO
    {
        if ($this->pdo === null) {
            $dbName = !empty($this->name) ? $this->name : "database";
            if ($this->driver === 'mysql') {
                $dsn = "mysql:host={$this->host};dbname={$dbName};charset=utf8;port=3306";
                $this->pdo = new PDO($dsn, $this->user, $this->password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } elseif ($this->driver === 'sqlite') {
                $dbFile = STORAGE_DIR . "db/{$dbName}.sqlite";
                $dsn = "sqlite:" . $dbFile;
                $this->pdo = new PDO($dsn, null, null, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);

            } else {
                throw new \InvalidArgumentException("Unsupported driver: {$this->driver}");
            }
        }

        return $this->pdo;
    }

    public function checkConnection(): bool
    {
        try {
            $pdo = $this->getConnection();
            if ($this->driver === 'mysql') {
                $pdo->query("SELECT 1");
            } elseif ($this->driver === 'sqlite') {
                $pdo->query("SELECT sqlite_version()");
            }
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}

<?php
try {
    if (empty($_ENV['DB_DRIVER']) || $_ENV['DB_DRIVER'] === 'sqlite') {
        // Create (connect to) SQLite database in file
        $pdo = new PDO('sqlite:' . $_ENV['DB_NAME']);

        // Set error mode to exceptions
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Enable foreign key constraints
        $pdo->exec('PRAGMA foreign_keys = ON;');
    }

    if ($_ENV['DB_DRIVER'] === 'mysql') {
        $mysqlHost = $_ENV['MYSQL_HOST'];
        $mysqlDb = $_ENV['MYSQL_DATABASE'];
        $mysqlUser = $_ENV['MYSQL_USER'];
        $mysqlPass = $_ENV['MYSQL_PASSWORD'];
        $pdo = new PDO("mysql:host=$mysqlHost;dbname=$mysqlDb;charset=utf8mb4", $mysqlUser, $mysqlPass);

        // Set the MySQL timezone to UTC
        $pdo->exec("SET time_zone = '+00:00'");

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    if ($_ENV['DB_DRIVER'] === 'mysql') {
        $pdo->query("SELECT 1");
        return true;
    } elseif ($_ENV['DB_DRIVER'] === 'sqlite') {
        $pdo->query("SELECT sqlite_version()");
        return true;
    } else {
        error_log("Unknown database driver: " . $_ENV['DB_DRIVER']);
        return false;
    }
} catch (PDOException $e) {
    error_log("Connection failed: " . $e->getMessage());
    return false;
}

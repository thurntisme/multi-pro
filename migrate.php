<?php

global $pdo;
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/functions/db.php';

try {

    // Get all migration files
    $migrations = glob(__DIR__ . '/migrations/*.php');

    // Apply each migration
    foreach ($migrations as $migration) {
        $migrate = require $migration;
        $migrate($pdo);
    }
} catch (PDOException $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}

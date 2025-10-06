<?php

declare(strict_types=1);

use App\Core\Database;

require __DIR__ . '/../vendor/autoload.php';

// Load constants
require __DIR__ . '/../config/constants.php';

$dotenv = new App\Core\Dotenv();
$dotenv->load();

// Connect to the database
$db = (new Database(
    $_ENV['DB_DRIVER'],
    $_ENV['DB_HOST'],
    $_ENV['DB_NAME'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASSWORD']
))->getConnection();

// Create the migrations status table if it does not exist
$db->exec("CREATE TABLE IF NOT EXISTS migrations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    migration VARCHAR(255) NOT NULL,
    batch INTEGER NOT NULL,
    migrated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

echo "🔄 Running migrations...\n";

// Get the list of already executed migration files
$doneMigrations = $db->query("SELECT migration FROM migrations")
    ->fetchAll(PDO::FETCH_COLUMN);

// Get all files in the migrations/ directory
$migrationFiles = glob(__DIR__ . '/migrations/*.php');
sort($migrationFiles); // run in filename order

$batch = (int) $db->query("SELECT MAX(batch) FROM migrations")->fetchColumn() + 1;

foreach ($migrationFiles as $file) {
    $name = basename($file);

    if (in_array($name, $doneMigrations, true)) {
        echo "⏩ Skipping $name (already migrated)\n";
        continue;
    }

    echo "▶ Running $name ... ";
    try {
        require $file;
        require __DIR__ . '/execute.php';

        $stmt = $db->prepare("INSERT INTO migrations (migration, batch) VALUES (?, ?)");
        $stmt->execute([$name, $batch]);
    } catch (Throwable $e) {
        echo "❌ Failed: " . $e->getMessage() . "\n";
        exit(1);
    }
}

echo "✅ All migrations completed.\n";
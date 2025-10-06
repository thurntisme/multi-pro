<?php

if (empty($table)) {
    echo "❌ Table $table is empty.\n";
    exit(1);
}

if (empty($mysql_attributes) || empty($sqlite_attributes)) {
    echo "❌ Attributes for $table are empty.\n";
    exit(1);
}

$driver = $_ENV['DB_DRIVER'] ?? 'mysql';

if ($driver === 'mysql') {
    $sql = <<<SQL
        CREATE TABLE IF NOT EXISTS $table (
            $mysql_attributes
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        SQL;
} elseif ($driver === 'sqlite') {
    $sql = <<<SQL
        CREATE TABLE IF NOT EXISTS $table (
            $sqlite_attributes
        );
        SQL;
} else {
    throw new \Exception("Unsupported driver: $driver");
}

try {
    $db->exec($sql);
    echo "✔ $table table created.\n";
} catch (PDOException $e) {
    echo "❌ Failed to create $table table: " . $e->getMessage() . "\n";
    exit(1);
}

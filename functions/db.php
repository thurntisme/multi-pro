<?php

function convertSqliteToMysql($attributes): array
{
    return array_map(function ($attribute) {
        $typeMapping = [
            'INTEGER PRIMARY KEY AUTOINCREMENT' => 'INT AUTO_INCREMENT PRIMARY KEY',
            'INTEGER' => 'INT',
            'DATETIME DEFAULT CURRENT_TIMESTAMP' => 'DATETIME DEFAULT CURRENT_TIMESTAMP',
        ];
        foreach ($typeMapping as $sqliteType => $mysqlType) {
            if (str_contains($attribute, $sqliteType)) {
                return str_replace($sqliteType, $mysqlType, $attribute);
            }
        }
        return $attribute;
    }, $attributes);
}

/**
 * @param array $attributes
 * @param string $table_name
 * @param PDO $pdo
 * @return void
 */
function convertTheAttributesArrayIntoAString(array $attributes, string $table_name, PDO $pdo): void
{
    if ($_ENV['DB_DRIVER'] === 'mysql') {
        $attributes = convertSqliteToMysql($attributes);
    }
    // Convert the attributes array into a string
    $attributes_sql = implode(",\n", $attributes);

    // Construct the SQL statement without IF NOT EXISTS
    $sql = "CREATE TABLE $table_name (
                $attributes_sql
            )";

    try {
        // Execute the SQL statement
        $pdo->exec($sql);
        echo "Migrated: create_" . $table_name . "_table\n\n";
    } catch (PDOException $e) {
        echo "Error: The table '$table_name': " . $e->getMessage() . "\n\n";
    }
}
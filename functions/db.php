<?php

/**
 * @param array $attributes
 * @param string $table_name
 * @param PDO $pdo
 * @return void
 */
function convertTheAttributesArrayIntoAString(array $attributes, string $table_name, PDO $pdo): void
{
    // Convert the attributes array into a string
    $attributes_sql = implode(",\n", $attributes);

    // Construct the SQL statement without IF NOT EXISTS
    $sql = "CREATE TABLE $table_name (
                $attributes_sql
            )";

    try {
        // Execute the SQL statement
        $pdo->exec($sql);
        echo "Migrated: create_" . $table_name . "_table\n";
    } catch (PDOException $e) {
        echo "Error: The table '$table_name': " . $e->getMessage() . "\n";
    }
}
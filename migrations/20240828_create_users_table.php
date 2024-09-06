<?php

return function (PDO $pdo) {
  // Define the table name and attributes
  $table_name = 'users';
  $attributes = [
    'id INTEGER PRIMARY KEY AUTOINCREMENT',
    'first_name TEXT',
    'last_name TEXT',
    'username TEXT NOT NULL',
    'email TEXT NOT NULL UNIQUE',
    'password TEXT NOT NULL',
    'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
    'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP',
  ];

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
    echo "Error: The table '$table_name': " . $e->getMessage() . " \n";
  }
};

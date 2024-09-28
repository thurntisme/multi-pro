<?php

return function (PDO $pdo) {
  // Define the table name and attributes
  $table_name = 'budget';
  $attributes = [
    'id INTEGER PRIMARY KEY AUTOINCREMENT',
    'title TEXT NOT NULL',
    'amount REAL NOT NULL',
    'description TEXT',
    'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
    'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP',
    'user_id INTEGER NOT NULL',
    'FOREIGN KEY(user_id) REFERENCES users(id)'
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
    echo "Error: The table '$table_name': " . $e->getMessage();
  }
};

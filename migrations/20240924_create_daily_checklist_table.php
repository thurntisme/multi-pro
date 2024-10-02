<?php

return function (PDO $pdo) {
  // Define the table name and attributes
  $table_name = 'daily_checklist';
  $attributes = [
    'id INTEGER PRIMARY KEY AUTOINCREMENT',
    'task_id INTEGER NOT NULL',
    "status TEXT CHECK( status IN ('revoked', 'completed') ) DEFAULT 'completed'",
    'user_id INTEGER NOT NULL',
    'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
    'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP',
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

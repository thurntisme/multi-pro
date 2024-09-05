<?php

return function (PDO $pdo) {
  // Define the table name and attributes
  $table_name = 'projects';
  $attributes = [
    'id INTEGER PRIMARY KEY AUTOINCREMENT',
    'title TEXT NOT NULL',
    'description TEXT',
    'start_date DATE',
    'end_date DATE',
    'status TEXT CHECK(status IN ("not_started", "in_progress", "completed", "on_hold", "cancelled")) DEFAULT "not_started"',
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
    echo "Error: The table '$table_name': " . $e->getMessage();
  }
};

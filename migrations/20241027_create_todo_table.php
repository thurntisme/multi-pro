<?php

return function (PDO $pdo) {
  // Define the table name and attributes
  $table_name = 'todos';
  $attributes = [
    'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT',
    'title' => 'TEXT NOT NULL',
    'content' => 'TEXT',
    'tags' => 'TEXT',
    'due_date' => 'DATE',
    'status' => 'TEXT NOT NULL DEFAULT "not_started"',
    'priority' => 'TEXT NOT NULL DEFAULT "low"',
    'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
    'updated_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
    'user_id' => 'INTEGER NOT NULL',
    'FOREIGN KEY(user_id)' => 'REFERENCES users(id)'
  ];
  // Convert the attributes array into a string
  $columns = [];
  foreach ($attributes as $name => $type) {
    $columns[] = "$name $type";
  }
  $attributes_sql = implode(", ", $columns);

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
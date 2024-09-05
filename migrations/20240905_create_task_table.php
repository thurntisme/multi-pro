<?php

return function (PDO $pdo) {
  // Define the table name and attributes
  $table_name = 'tasks';
  $attributes = [
    'id INTEGER PRIMARY KEY AUTOINCREMENT',
    'project_id INTEGER',
    'title TEXT NOT NULL',
    'description TEXT',
    'status TEXT CHECK(status IN ("pending", "in_progress", "completed", "on_hold", "cancelled", "review", "reopened")) DEFAULT "pending"',
    'due_date DATE',
    'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
    'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP',
    'FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE'
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

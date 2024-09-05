<?php

return function (PDO $pdo) {
  // Define the table name and attributes
  $table_name = 'task_assignments';
  $attributes = [
    'id INTEGER PRIMARY KEY AUTOINCREMENT',
    'task_id INTEGER',
    'user_id INTEGER',
    'assigned_at TEXT DEFAULT CURRENT_TIMESTAMP',
    'role TEXT CHECK(role IN ("assignee", "reviewer")) DEFAULT "assignee"',
    'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
    'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP',
    'FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE',
    'FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE'
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

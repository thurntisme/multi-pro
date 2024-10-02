<?php

return function (PDO $pdo) {
  // Define the table name and attributes
  $table_name = 'packages';
  $attributes = [
    'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT',
    'name' => 'TEXT NOT NULL UNIQUE',
    'label' => 'TEXT',
    'price' => 'TEXT NOT NULL',
    'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
    'updated_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
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

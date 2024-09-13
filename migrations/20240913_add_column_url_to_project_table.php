<?php

return function (PDO $pdo) {

  // Add columns to the 'projects' table
  $queries = [
    "ALTER TABLE projects ADD COLUMN dev_url TEXT",
    "ALTER TABLE projects ADD COLUMN staging_url TEXT",
    "ALTER TABLE projects ADD COLUMN production_url TEXT",
    "ALTER TABLE projects ADD COLUMN source_url TEXT"
  ];

  try {
    // Execute each query
    foreach ($queries as $query) {
      $pdo->exec($query);
    }

    echo "Columns added successfully.";
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
};

<?php

return function (PDO $pdo) {

  // Add columns to the 'projects' table
  $queries = [
    "ALTER TABLE blogs ADD COLUMN category VARCHAR(255) NOT NULL DEFAULT 'uncategorized'"
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

<?php
try {
  // Create (connect to) SQLite database in file
  $pdo = new PDO('sqlite:' . $_ENV['DB_NAME']);

  // Set error mode to exceptions
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Enable foreign key constraints
  $pdo->exec('PRAGMA foreign_keys = ON;');

  // echo "Connected to the SQLite database successfully!";
} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}

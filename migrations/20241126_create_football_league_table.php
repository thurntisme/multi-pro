<?php

return function (PDO $pdo) {
    // Define the table name and attributes
    $table_name = 'football_league';
    $attributes = [
        'id INTEGER PRIMARY KEY AUTOINCREMENT',  // League ID (primary key)
        'name TEXT NOT NULL',  // Name of the league (e.g., Premier League, La Liga)
        'season TEXT NOT NULL',  // Season of the league (e.g., 2024/2025)
        'start_date TIMESTAMP NOT NULL',  // Date when the league season starts
        'end_date TIMESTAMP NOT NULL',  // Date when the league season ends
        'win_amount INTEGER NOT NULL DEFAULT 0',  // The prize money or win amount for the league
        'status TEXT DEFAULT "active"',  // Status of the league (active, completed, etc.)
        'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',  // Timestamp when the record is created
        'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',  // Timestamp when the record is updated
    ];

    // Convert the attributes array into a string
    convertTheAttributesArrayIntoAString($attributes, $table_name, $pdo);
};

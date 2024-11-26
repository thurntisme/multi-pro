<?php

return function (PDO $pdo) {
    // Define the table name and attributes
    $table_name = 'football_player_injury';
    $attributes = [
        'id INTEGER PRIMARY KEY AUTOINCREMENT',
        'player_id INTEGER NOT NULL',
        'injury_type TEXT NOT NULL',  // Type of injury (e.g., ACL tear, hamstring, etc.)
        'severity TEXT NOT NULL',  // Severity of injury (minor, major)
        'start_date TIMESTAMP NOT NULL',  // Date of injury
        'recovery_time INTEGER NOT NULL',  // Estimated recovery time (in days)
        'status TEXT DEFAULT "recovering"',  // Injury status (recovering, healed, etc.)
        'FOREIGN KEY(player_id) REFERENCES football_player(id)',
    ];

    // Convert the attributes array into a string
    convertTheAttributesArrayIntoAString($attributes, $table_name, $pdo);
};

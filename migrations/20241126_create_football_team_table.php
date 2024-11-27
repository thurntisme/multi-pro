<?php

return function (PDO $pdo) {
    // Define the table name and attributes
    $table_name = 'football_team';
    $attributes = [
        'id INTEGER PRIMARY KEY AUTOINCREMENT',
        'team_name TEXT',
        'team_logo TEXT',
        'status TEXT DEFAULT "active"',
        'formation TEXT DEFAULT "433"',
        'performance_rating INTEGER DEFAULT 0',
        'league_position INTEGER DEFAULT 0',
        'points INTEGER DEFAULT 0',
        'total_wins INTEGER DEFAULT 0',
        'total_losses INTEGER DEFAULT 0',
        'total_draws INTEGER DEFAULT 0',
        'budget INTEGER DEFAULT 20241126',
        'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'manager_id TEXT NOT NULL',
        'FOREIGN KEY(manager_id) REFERENCES users(id)'
    ];

    // Convert the attributes array into a string
    convertTheAttributesArrayIntoAString($attributes, $table_name, $pdo);
};

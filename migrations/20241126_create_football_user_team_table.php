<?php

return function (PDO $pdo) {
    // Define the table name and attributes
    $table_name = 'football_user_team';
    $attributes = [
        'id INTEGER PRIMARY KEY AUTOINCREMENT',
        'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'user_id INTEGER NOT NULL',
        'team_id TEXT NOT NULL',
        'FOREIGN KEY(user_id) REFERENCES users(user_id)',
        'FOREIGN KEY(team_id) REFERENCES football_team(team_id)'
    ];

    // Convert the attributes array into a string
    convertTheAttributesArrayIntoAString($attributes, $table_name, $pdo);
};

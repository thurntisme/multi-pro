<?php

return function (PDO $pdo) {
    // Define the table name and attributes
    $table_name = 'football_favorite_player';
    $attributes = [
        'id INTEGER PRIMARY KEY AUTOINCREMENT',
        'team_name TEXT NOT NULL',
        'budget INTEGER DEFAULT 20241126',
        'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'player_id INTEGER NOT NULL',
        'manager_id INTEGER NOT NULL',
        'FOREIGN KEY(player_id) REFERENCES football_player(id)',
        'FOREIGN KEY(manager_id) REFERENCES users(id)'
    ];

    // Convert the attributes array into a string
    convertTheAttributesArrayIntoAString($attributes, $table_name, $pdo);
};

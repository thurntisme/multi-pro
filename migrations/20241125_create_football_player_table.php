<?php

return function (PDO $pdo) {
    // Define the table name and attributes
    $table_name = 'football_player';
    $attributes = [
        'id INTEGER PRIMARY KEY AUTOINCREMENT',
        'player_uuid TEXT NOT NULL',
        'starting_order INTEGER DEFAULT 0',
        'status TEXT',
        'level INTEGER DEFAULT 100',
        'injury_end_date TIMESTAMP',
        'joining_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'contract_end_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'team_id INTEGER NOT NULL',
        'FOREIGN KEY(team_id) REFERENCES football_team(id)'
    ];

    // Convert the attributes array into a string
    convertTheAttributesArrayIntoAString($attributes, $table_name, $pdo);
};

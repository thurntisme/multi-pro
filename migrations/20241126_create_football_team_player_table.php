<?php

return function (PDO $pdo) {
    // Define the table name and attributes
    $table_name = 'football_team_player';
    $attributes = [
        'id INTEGER PRIMARY KEY AUTOINCREMENT',
        'team_id INTEGER NOT NULL',
        'player_id INTEGER NOT NULL',
        'joining_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'loan BOOLEAN DEFAULT 0',  // Indicates if the player is on loan
        'FOREIGN KEY(team_id) REFERENCES football_team(id)',
        'FOREIGN KEY(player_id) REFERENCES football_player(id)',
    ];

    // Convert the attributes array into a string
    convertTheAttributesArrayIntoAString($attributes, $table_name, $pdo);
};

<?php

return function (PDO $pdo) {
    // Define the table name and attributes
    $table_name = 'football_player_statistics';
    $attributes = [
        'id INTEGER PRIMARY KEY AUTOINCREMENT',
        'player_id INTEGER NOT NULL',
        'match_id INTEGER NOT NULL',
        'goals INTEGER DEFAULT 0',
        'assists INTEGER DEFAULT 0',
        'yellow_cards INTEGER DEFAULT 0',
        'red_cards INTEGER DEFAULT 0',
        'minutes_played INTEGER DEFAULT 0',
        'FOREIGN KEY(player_id) REFERENCES football_player(id)',
        'FOREIGN KEY(match_id) REFERENCES football_match(id)',
    ];

    // Convert the attributes array into a string
    convertTheAttributesArrayIntoAString($attributes, $table_name, $pdo);
};

<?php

return function (PDO $pdo) {
    // Define the table name and attributes
    $table_name = 'football_player';
    $attributes = [
        'id INTEGER PRIMARY KEY AUTOINCREMENT',
        'player_uuid TEXT NOT NULL',
        'starting_order INTEGER DEFAULT 99',  // Tracks the player's starting order in the lineup
        'status TEXT DEFAULT "active"',  // Player's status (active, injured, etc.)
        'level INTEGER DEFAULT 100',  // Player's level (default 100)
        'card_level INTEGER DEFAULT 0',  // Player's card level (+0, +1, +2, etc.)
        'injury_end_date TIMESTAMP NULL',  // Nullable injury end date
        'joining_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP',  // Date the player joined
        'contract_end_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP',  // Date the player's contract ends
        'goals_scored INTEGER DEFAULT 0',  // Tracks goals scored
        'assists INTEGER DEFAULT 0',  // Tracks assists
        'yellow_cards INTEGER DEFAULT 0',  // Tracks yellow cards
        'red_cards INTEGER DEFAULT 0',  // Tracks red cards
        'age_in_team_days INTEGER DEFAULT 0',  // Tracks the number of days the player has been in the team
        'match_played INTEGER DEFAULT 0',
        'player_stamina INTEGER DEFAULT 100',
        'shirt_number INTEGER',
        'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'team_id INTEGER NOT NULL',
        'FOREIGN KEY(team_id) REFERENCES football_team(id)'
    ];

    // Convert the attributes array into a string
    convertTheAttributesArrayIntoAString($attributes, $table_name, $pdo);
};

<?php

return function (PDO $pdo) {
    // Define the table name and attributes
    $table_name = 'football_match';
    $attributes = [
        'id INTEGER PRIMARY KEY AUTOINCREMENT',  // Match ID (primary key)
        'team_home_id INTEGER NOT NULL',  // ID of the home team
        'team_away_id INTEGER NOT NULL',  // ID of the away team
        'home_score INTEGER DEFAULT 0',  // Score of the home team
        'away_score INTEGER DEFAULT 0',  // Score of the away team
        'match_date TIMESTAMP NOT NULL',  // Date and time of the match
        'status TEXT DEFAULT "scheduled"',  // Match status (scheduled, ongoing, finished, etc.)
        'venue TEXT',  // Venue or location of the match (optional)
        'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',  // Date and time when the record is created
        'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',  // Date and time when the record is updated
        'FOREIGN KEY(team_home_id) REFERENCES football_team(id)',  // Foreign key to the teams table (home team)
        'FOREIGN KEY(team_away_id) REFERENCES football_team(id)',  // Foreign key to the teams table (away team)
    ];

    // Convert the attributes array into a string
    convertTheAttributesArrayIntoAString($attributes, $table_name, $pdo);
};

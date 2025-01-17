<?php

return function (PDO $pdo) {
    // Define the table name and attributes
    $table_name = 'football_match';
    $attributes = [
        'id INTEGER PRIMARY KEY AUTOINCREMENT',
        'team_id INTEGER NOT NULL',
        'match_uuid TEXT NOT NULL',
        'home_team TEXT',
        'away_team TEXT',
        'home_score INTEGER DEFAULT 0',
        'away_score INTEGER DEFAULT 0',
        'draft_home_score INTEGER DEFAULT 0',
        'draft_away_score INTEGER DEFAULT 0',
        'is_home TINYINT(1) DEFAULT 1',
        'status TEXT DEFAULT "scheduled"',
        'draft_budget INTEGER DEFAULT 0',
        'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'played_at TIMESTAMP',
        'FOREIGN KEY(team_id) REFERENCES football_team(id)',
    ];

    // Convert the attributes array into a string
    convertTheAttributesArrayIntoAString($attributes, $table_name, $pdo);
};

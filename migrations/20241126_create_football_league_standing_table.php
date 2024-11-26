<?php

return function (PDO $pdo) {
    // Define the table name and attributes
    $table_name = 'football_league_standing';
    $attributes = [
        'id INTEGER PRIMARY KEY AUTOINCREMENT',
        'league_id INTEGER NOT NULL',
        'team_id INTEGER NOT NULL',
        'position INTEGER NOT NULL',  // The team's position in the league
        'points INTEGER NOT NULL',  // Points accumulated by the team
        'played INTEGER DEFAULT 0',  // Number of matches played
        'won INTEGER DEFAULT 0',  // Number of wins
        'drawn INTEGER DEFAULT 0',  // Number of draws
        'lost INTEGER DEFAULT 0',  // Number of losses
        'goals_for INTEGER DEFAULT 0',  // Goals scored
        'goals_against INTEGER DEFAULT 0',  // Goals conceded
        'goal_difference INTEGER DEFAULT 0',  // Goal difference
        'FOREIGN KEY(league_id) REFERENCES football_league(id)',
        'FOREIGN KEY(team_id) REFERENCES football_team(id)',
    ];

    // Convert the attributes array into a string
    convertTheAttributesArrayIntoAString($attributes, $table_name, $pdo);
};

<?php

return function (PDO $pdo) {
    // Define the table name and attributes
    $table_name = 'football_player';
    $attributes = [
        'id INTEGER PRIMARY KEY AUTOINCREMENT',
        'player_uuid TEXT NOT NULL',
        'age INTEGER',
        'injury INTEGER',
        'recovery_time INTEGER',
        'ability INTEGER',
        'passing INTEGER',
        'dribbling INTEGER',
        'first_touch INTEGER',
        'crossing INTEGER',
        'finishing INTEGER',
        'long_shots INTEGER',
        'free_kick_accuracy INTEGER',
        'heading INTEGER',
        'tackling INTEGER',
        'handling INTEGER',
        'marking INTEGER',
        'decision INTEGER',
        'vision INTEGER',
        'leadership INTEGER',
        'work_rate INTEGER',
        'positioning INTEGER',
        'composure INTEGER',
        'aggression INTEGER',
        'anticipation INTEGER',
        'concentration INTEGER',
        'off_the_ball INTEGER',
        'flair INTEGER',
        'pace INTEGER',
        'strength INTEGER',
        'stamina INTEGER',
        'agility INTEGER',
        'balance INTEGER',
        'jumping_reach INTEGER',
        'natural_fitness INTEGER',
        'salary BIGINT',
        'market_value BIGINT',
        'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'team_id INTEGER NOT NULL',
        'FOREIGN KEY(team_id) REFERENCES football_team(id)'
    ];

    // Convert the attributes array into a string
    convertTheAttributesArrayIntoAString($attributes, $table_name, $pdo);
};

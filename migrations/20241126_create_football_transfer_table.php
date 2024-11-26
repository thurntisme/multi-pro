<?php

return function (PDO $pdo) {
    // Define the table name and attributes
    $table_name = 'football_transfer';
    $attributes = [
        'id INTEGER PRIMARY KEY AUTOINCREMENT',
        'type TEXT NOT NULL',
        'status TEXT NOT NULL',
        'amount BIGINT NOT NULL',
        'user_id INTEGER NOT NULL',
        'player_id INTEGER NOT NULL',
        'FOREIGN KEY(user_id) REFERENCES users(user_id)',
        'FOREIGN KEY(player_id) REFERENCES football_player(player_id)',
        'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
    ];

    // Convert the attributes array into a string
    convertTheAttributesArrayIntoAString($attributes, $table_name, $pdo);
};

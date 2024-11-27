<?php

return function (PDO $pdo) {
    // Define the table name and attributes
    $table_name = 'football_transfer';
    $attributes = [
        'id INTEGER PRIMARY KEY AUTOINCREMENT',
        'type TEXT NOT NULL',
        'status TEXT NOT NULL DEFAULT "pending"',
        'amount BIGINT NOT NULL',
        'manager_id INTEGER NOT NULL',
        'player_id INTEGER NOT NULL',
        'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'FOREIGN KEY(manager_id) REFERENCES users(id)',
        'FOREIGN KEY(player_id) REFERENCES football_player(id)',
    ];

    // Convert the attributes array into a string
    convertTheAttributesArrayIntoAString($attributes, $table_name, $pdo);
};

<?php

return function (PDO $pdo) {
    // Define the table name and attributes
    $table_name = 'football_favorite_player';
    $attributes = [
        'id INTEGER PRIMARY KEY AUTOINCREMENT',
        'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'player_uuid TEXT NOT NULL',
        'manager_id INTEGER NOT NULL',
        'FOREIGN KEY(manager_id) REFERENCES users(id)'
    ];

    // Convert the attributes array into a string
    convertTheAttributesArrayIntoAString($attributes, $table_name, $pdo);
};

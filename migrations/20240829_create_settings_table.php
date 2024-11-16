<?php

return function (PDO $pdo) {
    // Define the table name and attributes
    $table_name = 'settings';
    $attributes = [
        'id INTEGER PRIMARY KEY AUTOINCREMENT',
        'key TEXT NOT NULL UNIQUE',
        'value TEXT NOT NULL',
        'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'user_id INTEGER NOT NULL',
        'FOREIGN KEY(user_id) REFERENCES users(id)'
    ];

    // Convert the attributes array into a string
    convertTheAttributesArrayIntoAString($attributes, $table_name, $pdo);
};

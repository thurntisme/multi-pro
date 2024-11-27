<?php

return function (PDO $pdo) {
    // Define the table name and attributes
    $table_name = 'user_point';
    $attributes = [
        'id INTEGER PRIMARY KEY AUTOINCREMENT',
        'point BIGINT DEFAULT 20241126',
        'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'user_id INTEGER NOT NULL',
        'FOREIGN KEY(user_id) REFERENCES users(id)'
    ];

    // Convert the attributes array into a string
    convertTheAttributesArrayIntoAString($attributes, $table_name, $pdo);
};

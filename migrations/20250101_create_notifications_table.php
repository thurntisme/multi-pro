<?php

return function (PDO $pdo) {
    // Define the table name and attributes
    $table_name = 'notifications';
    $attributes = [
        'id INTEGER PRIMARY KEY AUTOINCREMENT',
        'title TEXT NOT NULL',
        'type VARCHAR(50) DEFAULT "general"',
        'message TEXT NOT NULL',
        'is_read TINYINT(1) DEFAULT 0',
        'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'user_id INTEGER NOT NULL',
        'FOREIGN KEY(user_id) REFERENCES users(id)'
    ];

    // Convert the attributes array into a string
    convertTheAttributesArrayIntoAString($attributes, $table_name, $pdo);
};
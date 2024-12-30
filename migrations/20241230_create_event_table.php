<?php

return function (PDO $pdo) {
    // Define the table name and attributes
    $table_name = 'events';
    $attributes = [
        'id INTEGER PRIMARY KEY AUTOINCREMENT',
        'type TEXT NOT NULL',
        'title TEXT NOT NULL',
        'start_date DATE NOT NULL',
        'end_date DATE NOT NULL',
        'start_time TIME',
        'end_time TIME',
        'location TEXT',
        'description TEXT',
        'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'user_id INTEGER NOT NULL',
        'FOREIGN KEY(user_id) REFERENCES users(id)'
    ];

    // Convert the attributes array into a string
    convertTheAttributesArrayIntoAString($attributes, $table_name, $pdo);
};

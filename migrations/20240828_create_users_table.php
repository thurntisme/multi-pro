<?php

return function (PDO $pdo) {
    // Define the table name and attributes
    $table_name = 'users';
    $attributes = [
        'id INTEGER PRIMARY KEY AUTOINCREMENT',
        'first_name TEXT',
        'last_name TEXT',
        'username TEXT NOT NULL',
        'email TEXT NOT NULL UNIQUE',
        'password TEXT NOT NULL',
        'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
        'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP',
    ];

    // Convert the attributes array into a string
    convertTheAttributesArrayIntoAString($attributes, $table_name, $pdo);
};

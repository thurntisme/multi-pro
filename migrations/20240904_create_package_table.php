<?php

return function (PDO $pdo) {
    // Define the table name and attributes
    $table_name = 'packages';
    $attributes = [
        'id INTEGER PRIMARY KEY AUTOINCREMENT',
        'name TEXT NOT NULL UNIQUE',
        'label TEXT',
        'price TEXT NOT NULL',
        'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
    ];

    // Convert the attributes array into a string
    convertTheAttributesArrayIntoAString($attributes, $table_name, $pdo);
};

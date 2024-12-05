<?php

return function (PDO $pdo) {
    // Define the table name and attributes
    $table_name = 'projects';
    $attributes = [
        'id INTEGER PRIMARY KEY AUTOINCREMENT',
        'title TEXT NOT NULL',
        'description TEXT',
        'start_date DATE',
        'end_date DATE',
        'user_id INTEGER',
        'status TEXT DEFAULT "not_started"',
        'type TEXT DEFAULT "owner"',
        'dev_url TEXT',
        'staging_url TEXT',
        'production_url TEXT',
        'source_url TEXT',
        'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
        'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP',
        'FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE'
    ];

    // Convert the attributes array into a string
    convertTheAttributesArrayIntoAString($attributes, $table_name, $pdo);
};

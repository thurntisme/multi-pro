<?php

return function (PDO $pdo) {
    // Define the table name and attributes
    $table_name = 'books';
    $attributes = [
        'id INTEGER PRIMARY KEY AUTOINCREMENT',
        'title TEXT NOT NULL',
        'content TEXT',
        'tags TEXT',
        'url TEXT',
        'view_url TEXT',
        'view_page INTEGER DEFAULT 0',
        'status TEXT NOT NULL DEFAULT "not_started"',
        'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'user_id INTEGER NOT NULL',
        'FOREIGN KEY(user_id) REFERENCES users(id)'
    ];

    // Convert the attributes array into a string
    convertTheAttributesArrayIntoAString($attributes, $table_name, $pdo);
};

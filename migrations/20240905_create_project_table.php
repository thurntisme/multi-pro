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
        'status TEXT CHECK(status IN ("not_started", "in_progress", "completed", "on_hold", "cancelled", "archive")) DEFAULT "not_started"',
        'type TEXT CHECK(type IN ("owner", "freelancer")) DEFAULT "owner"',
        'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
        'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP',
        'FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE'
    ];
    
    // Convert the attributes array into a string
    convertTheAttributesArrayIntoAString($attributes, $table_name, $pdo);
};

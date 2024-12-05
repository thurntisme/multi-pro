<?php

return function (PDO $pdo) {
    // Define the table name and attributes
    $table_name = 'daily_checklist';
    $attributes = [
        'id INTEGER PRIMARY KEY AUTOINCREMENT',
        'task_id INTEGER NOT NULL',
        "status TEXT DEFAULT 'completed'",
        'user_id INTEGER NOT NULL',
        'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
        'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP',
        'FOREIGN KEY(user_id) REFERENCES users(id)'
    ];
    // Convert the attributes array into a string
    convertTheAttributesArrayIntoAString($attributes, $table_name, $pdo);
};

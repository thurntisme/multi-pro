<?php

return function (PDO $pdo) {
    // Define the table name and attributes
    $table_name = 'comments';
    $attributes = [
        'id INTEGER PRIMARY KEY AUTOINCREMENT',
        'task_id INTEGER',
        'user_id INTEGER',
        'comment TEXT NOT NULL',
        'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
        'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP',
        'FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE',
        'FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE'
    ];

    // Convert the attributes array into a string
    convertTheAttributesArrayIntoAString($attributes, $table_name, $pdo);
};

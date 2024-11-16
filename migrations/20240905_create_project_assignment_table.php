<?php

return function (PDO $pdo) {
    // Define the table name and attributes
    $table_name = 'project_assignments';
    $attributes = [
        'id INTEGER PRIMARY KEY AUTOINCREMENT',
        'project_id INTEGER',
        'user_id INTEGER',
        'role TEXT CHECK(role IN ("owner", "member")) DEFAULT "member"',
        'assigned_at TEXT DEFAULT CURRENT_TIMESTAMP',
        'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
        'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP',
        'FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE',
        'FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE',
    ];

    // Convert the attributes array into a string
    convertTheAttributesArrayIntoAString($attributes, $table_name, $pdo);
};

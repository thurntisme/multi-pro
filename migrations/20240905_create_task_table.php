<?php

return function (PDO $pdo) {
    // Define the table name and attributes
    $table_name = 'tasks';
    $attributes = [
        'id INTEGER PRIMARY KEY AUTOINCREMENT',
        'project_id INTEGER',
        'title TEXT NOT NULL',
        'description TEXT',
        'status TEXT DEFAULT "not_started"',
        'due_date DATE',
        'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
        'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP',
        'FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE'
    ];
    // Convert the attributes array into a string
    convertTheAttributesArrayIntoAString($attributes, $table_name, $pdo);
};

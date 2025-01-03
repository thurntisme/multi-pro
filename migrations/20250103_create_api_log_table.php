<?php

return function (PDO $pdo) {
    // Define the table name and attributes
    $table_name = 'api_logs';
    $attributes = [
        'id INTEGER PRIMARY KEY AUTOINCREMENT',
        'status VARCHAR(50) DEFAULT "success"',
        'method VARCHAR(50) DEFAULT "get"',
        'route TEXT NOT NULL',
        'message TEXT NOT NULL',
        'ip_address TEXT NOT NULL',
        'result_code VARCHAR(50) DEFAULT "200"',
        'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
    ];

    // Convert the attributes array into a string
    convertTheAttributesArrayIntoAString($attributes, $table_name, $pdo);
};

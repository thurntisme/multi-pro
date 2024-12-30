<?php

return function (PDO $pdo) {
    // Define the table name and attributes
    $table_name = 'subscriptions';
    $attributes = [
        'id INTEGER PRIMARY KEY AUTOINCREMENT',
        'service_name TEXT NOT NULL',
        'description TEXT',
        'amount REAL NOT NULL',
        'currency TEXT NOT NULL',
        'payment_date INTEGER NOT NULL',
        'payment_month INTEGER',
        'payment_type TEXT NOT NULL DEFAULT "monthly"',
        'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'user_id INTEGER NOT NULL',
        'FOREIGN KEY(user_id) REFERENCES users(id)'
    ];

    // Convert the attributes array into a string
    convertTheAttributesArrayIntoAString($attributes, $table_name, $pdo);
};

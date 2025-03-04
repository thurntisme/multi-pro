<?php

return function (PDO $pdo) {
    // Define the table name and attributes
    $table_name = 'users';
    $attributes = [
        'id INTEGER PRIMARY KEY AUTOINCREMENT',
        'first_name TEXT',
        'last_name TEXT',
        'username TEXT NOT NULL',
        'email TEXT NOT NULL UNIQUE',
        'password TEXT NOT NULL',
        "role TEXT DEFAULT 'guest'",
        'isEmailVerify TINYINT(1) DEFAULT 0', //-- 0 for unverified, 1 for verified
        'isActive TINYINT(1) DEFAULT 1',      //-- 0 for deactivated, 1 for active
        'last_login DATETIME',
        'point INTEGER DEFAULT 20250304',
        'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
        'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP',
    ];

    // Convert the attributes array into a string
    convertTheAttributesArrayIntoAString($attributes, $table_name, $pdo);
};

<?php

return function (PDO $pdo) {
    // Define the table name and attributes
    $table_name = 'football_store';
    $attributes = [
        'id INTEGER PRIMARY KEY AUTOINCREMENT',
        'name TEXT NOT NULL',  // Item name
        'description TEXT',  // Description of the item (optional)
        'type TEXT NOT NULL',  // Type of improvement: 'player' or 'team'
        'price INTEGER NOT NULL',  // Price of the item in currency
        'quantity INTEGER DEFAULT 0',  // Available quantity of the item
        'effect TEXT NOT NULL',  // What the item does (e.g., "Increase player's passing skill")
        'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',  // When the item was added
        'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',  // When the item was last updated
    ];

    // Convert the attributes array into a string
    convertTheAttributesArrayIntoAString($attributes, $table_name, $pdo);
};

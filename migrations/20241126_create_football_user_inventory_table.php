<?php

return function (PDO $pdo) {
    // Define the table name and attributes
    $table_name = 'football_user_inventory';
    $attributes = [
        'id INTEGER PRIMARY KEY AUTOINCREMENT',
        'user_id INTEGER NOT NULL',  // User who purchased the item
        'item_id INTEGER NOT NULL',  // Item purchased (from the football_store table)
        'quantity INTEGER DEFAULT 1',  // Quantity of the item the user has
        'inventory_uuid TEXT NOT NULL UNIQUE',
        'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',  // When the item was purchased
        'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',  // When the item was last updated
        'FOREIGN KEY(user_id) REFERENCES users(id)',  // Link to the users table
    ];

    // Convert the attributes array into a string
    convertTheAttributesArrayIntoAString($attributes, $table_name, $pdo);
};

<?php

return function (PDO $pdo) {
    // Define the table name and attributes
    $table_name = 'football_user_inventory';
    $attributes = [
        'id INTEGER PRIMARY KEY AUTOINCREMENT',
        'user_id INTEGER NOT NULL',  // User who purchased the item
        'item_id INTEGER NOT NULL',  // Item purchased (from the football_store table)
        'quantity INTEGER DEFAULT 1',  // Quantity of the item the user has
        'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',  // When the item was purchased
        'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',  // When the item was last updated
        'FOREIGN KEY(user_id) REFERENCES users(user_id)',  // Link to the users table
        'FOREIGN KEY(item_id) REFERENCES football_store(id)'  // Link to the football_store table
    ];

    // Convert the attributes array into a string
    convertTheAttributesArrayIntoAString($attributes, $table_name, $pdo);
};

<?php

$table = 'expenses';
$mysql_attributes = 'id INT AUTO_INCREMENT PRIMARY KEY, title VARCHAR(255) NOT NULL, amount DECIMAL(10,2) NOT NULL, description TEXT, category VARCHAR(255), date_expense DATE, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, user_id INT NOT NULL, FOREIGN KEY(user_id) REFERENCES users(id)';
$sqlite_attributes = 'id INTEGER PRIMARY KEY AUTOINCREMENT, title TEXT NOT NULL, amount REAL NOT NULL, description TEXT, category TEXT, date_expense DATE, created_at DATETIME DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP, user_id INTEGER NOT NULL, FOREIGN KEY(user_id) REFERENCES users(id)';
<?php

$table = "settings";

$mysql_attributes = "
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    option_key TEXT NOT NULL UNIQUE,
    option_value TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INTEGER NOT NULL,
    FOREIGN KEY(user_id) REFERENCES users(id)
";

$sqlite_attributes = "
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    option_key TEXT NOT NULL UNIQUE,
    option_value TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INTEGER NOT NULL,
    FOREIGN KEY(user_id) REFERENCES users(id)
";
<?php

$table = "tokens";

$mysql_attributes = "
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    token  TEXT NOT NULL UNIQUE,
    device_name  TEXT,
    device_type  TEXT,
    ip_address  TEXT,
    last_time_login  DATETIME,
    expires_at  DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
";

$sqlite_attributes = "
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    token  TEXT NOT NULL UNIQUE,
    device_name  TEXT,
    device_type  TEXT,
    ip_address  TEXT,
    last_time_login  DATETIME,
    expires_at  DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(user_id) REFERENCES users(id)
";
<?php

$table = 'users';

$mysql_attributes = "
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    first_name TEXT,
    last_name TEXT,
    username TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    role TEXT DEFAULT 'guest',
    isEmailVerify TINYINT(1) DEFAULT 0,
    isActive TINYINT(1) DEFAULT 1,     
    last_login DATETIME,
    point INTEGER DEFAULT 20250304,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
";

$sqlite_attributes = "
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    first_name TEXT,
    last_name TEXT,
    username TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    role TEXT DEFAULT 'guest',
    isEmailVerify TINYINT(1) DEFAULT 0,
    isActive TINYINT(1) DEFAULT 1,    
    last_login DATETIME,
    point INTEGER DEFAULT 20250304,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
";
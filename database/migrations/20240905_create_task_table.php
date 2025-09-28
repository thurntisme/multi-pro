<?php

$table = 'tasks';
$mysql_attributes = 'id INT AUTO_INCREMENT PRIMARY KEY, project_id INT, title VARCHAR(255) NOT NULL, description TEXT, status ENUM("not_started", "in_progress", "completed") DEFAULT "not_started", due_date DATE, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE';
$sqlite_attributes = 'id INTEGER PRIMARY KEY AUTOINCREMENT, project_id INTEGER, title TEXT NOT NULL, description TEXT, status TEXT DEFAULT "not_started", due_date TEXT, created_at TEXT DEFAULT CURRENT_TIMESTAMP, updated_at TEXT DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE';
<?php

// Paths
define('APP_URL', 'http://localhost:8000');
define('DIR_ROOT', dirname(__DIR__, 1));
define('VIEWS_DIR', DIR_ROOT . '/app/views/');
define('LAYOUTS_DIR', DIR_ROOT . '/app/views/layouts/');
define('COMPONENTS_DIR', DIR_ROOT . '/app/views/components/');
define('ASSETS_DIR', DIR_ROOT . '/public/assets/');
define('STORAGE_DIR', DIR_ROOT . '/storage/');
define('LOGS_DIR', DIR_ROOT . '/storage/logs/');
define('UPLOADS_DIR', DIR_ROOT . '/storage/app/uploads/');
define("MOCK_DIR", STORAGE_DIR . '/mock/');

// App info
define('APP_NAME', 'MultiPro');
define('APP_VERSION', '1.0.0');

// Roles & permissions
define('ROLE_ADMIN', 'admin');
define('ROLE_USER', 'user');

// Upload config
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024);   // 5 MB
define('ALLOWED_FILE_TYPES', serialize(['pdf']));

// Session keys
define('SESSION_USER', 'auth_user');

// DateTime
define('DEFAULT_DATE_FORMAT', 'Y-m-d');
define('DEFAULT_TIME_FORMAT', 'H:i:s');
define('DEFAULT_DATETIME_FORMAT', 'Y-m-d H:i:s');
define('DEFAULT_TIMEZONE', 'Asia/Ho_Chi_Minh');

// View Engine
define('VIEW_EXTENSION', '.php');
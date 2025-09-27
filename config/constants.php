<?php

define('DEFAULT_HOME_PATH', '');
define('DEFAULT_SITE_NAME', 'MultiPro');
define('DEFAULT_DATE_FORMAT', 'Y-m-d');
define('DEFAULT_TIME_FORMAT', 'H:i:s');
define('DEFAULT_DATETIME_FORMAT', 'Y-m-d H:i:s');

// Paths
define('BASE_PATH', 'http://localhost:8000');
define('APP_ROOT', dirname(__DIR__, 1));
define('PUBLIC_PATH', APP_ROOT . '/public/');
define('UPLOAD_PATH', APP_ROOT . '/public/uploads/');
define('VIEWS_PATH', APP_ROOT . '/app/views/');
define('LAYOUTS_PATH', APP_ROOT . '/app/views/layouts/');
define('COMPONENTS_PATH', APP_ROOT . '/app/views/components/');
define('DIR_ASSETS_PATH', APP_ROOT . '/public/assets/');
define('ASSETS_PATH', BASE_PATH . '/public/assets/');
define('STORAGE_PATH', APP_ROOT . '/storage/');
define('LOGS_PATH', APP_ROOT . '/storage/logs/');
define('DIR_UPLOADS_PATH', APP_ROOT . '/storage/app/uploads/');
define('APP_UPLOADS_PATH', BASE_PATH . '/storage/app/uploads/');

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

// Misc
define('DEFAULT_TIMEZONE', 'Asia/Ho_Chi_Minh');

// View Engine
define('VIEW_EXTENSION', '.php');
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
define('ASSETS_PATH', BASE_PATH . '/public/assets/');
define('STORAGE_PATH', APP_ROOT . '/storage/');
define('LOGS_PATH', APP_ROOT . '/storage/logs/');
define('DIR_UPLOADS_PATH', APP_ROOT . '/storage/app/uploads/');
define('APP_UPLOADS_PATH', BASE_PATH . '/storage/app/uploads/');
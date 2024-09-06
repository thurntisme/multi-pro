<?php

define('DEFAULT_HOME_PATH', 'multi-pro');
define('DEFAULT_TIMEZONE', 'Asia/Ho_Chi_Minh');
define('DEFAULT_SITE_NAME', 'MultiPro');

$months = [
  '01' => 'January',
  '02' => 'February',
  '03' => 'March',
  '04' => 'April',
  '05' => 'May',
  '06' => 'June',
  '07' => 'July',
  '08' => 'August',
  '09' => 'September',
  '10' => 'October',
  '11' => 'November',
  '12' => 'December'
];
define("MONTHS", $months);

$settings = [
  'site_name' => 'My Website',
  'admin_email' => 'admin@example.com',
  'timezone' => 'UTC',
  'language' => 'en'
];
define("INIT_SETTINGS", $settings);
$default_settings = [];
foreach ($settings as $key => $value) {
  $default_settings[] = [
    'key' => $key,
    'value' => $value
  ];
}
define("DEFAULT_SETTINGS", $default_settings);

$projectStatus = array(
  array('not_started', 'Not Started'),
  array('in_progress', 'Inprogress'),
  array('completed', 'Completed'),
  array('on_hold', 'On Hold'),
  array('cancelled', 'Cancelled'),
);
define('DEFAULT_PROJECT_STATUS', $projectStatus);

<?php

define('DEFAULT_HOME_PATH', '');
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

$projectTypes = array(
  array('owner', 'Owner'),
  array('freelancer', 'Freelancer'),
);
define('DEFAULT_PROJECT_TYPES', $projectTypes);

define('DEFAULT_FILTER_DATE_OPTIONS', [
  "" => "All",
  "today" => "Today",
  "yesterday" => "Yesterday",
  "this_week" => "This Week",
  "this_month" => "This Month",
  "this_year" => "This Year",
  "last_7days" => "Last 7 Days",
  "last_week" => "Last Week",
  "last_month" => "Last Month",
  "last_year" => "Last Year",
]);

$expense_categories = [
  ["id" => 1, "name" => "Groceries"],
  ["id" => 2, "name" => "Bills"],
  ["id" => 3, "name" => "Entertainment"],
  ["id" => 4, "name" => "Transportation"],
  ["id" => 5, "name" => "Other"]
];
define("DEFAULT_EXPENSE_CATEGORIES", $expense_categories);

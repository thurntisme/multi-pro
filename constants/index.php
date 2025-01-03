<?php

define('DEFAULT_HOME_PATH', '');
define('DEFAULT_SITE_NAME', 'MultiPro');
define('DEFAULT_DATE_FORMAT', 'Y-m-d');
define('DEFAULT_TIME_FORMAT', 'H:i:s');
define('DEFAULT_DATETIME_FORMAT', 'Y-m-d H:i:s');

include "version.php";
include "bookmarks.php";
include "network.php";
include "dashboard.php";
include "football-manager.php";
include "user.php";

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

$timezones = [];
foreach (DateTimeZone::listIdentifiers() as $zone) {
    $timezones[$zone] = $zone;
};
$support_languages = ['en' => 'English', 'vi' => 'Vietnamese'];

$support_fonts = [
    "Tahoma" => "Tahoma",
    "Verdana" => "Verdana",
    "Roboto" => "Roboto",
    "Segoe UI" => "Segoe UI",
    "Helvetica" => "Helvetica",
    "Georgia" => "Georgia",
    "Courier New" => "Courier New",
    "Open Sans" => "Open Sans",
    "Lato" => "Lato",
    "Montserrat" => "Montserrat",
    "Poppins" => "Poppins",
    "Raleway" => "Raleway",
    "Bitter" => "Bitter",
    "Work Sans" => "Work Sans",
    "Inter" => "Inter",
    "Oswald" => "Oswald",
    "Noto" => "Noto",
    "Nunito" => "Nunito",
];

$user_settings = [
    // General Settings
    'general' => [
        [
            'field' => 'timezone',
            'value' => 'UTC',
            'type' => 'select',
            'col' => 'col-6',
            'options' => $timezones,
        ],
        [
            'field' => 'language',
            'value' => 'en',
            'type' => 'select',
            'col' => 'col-6',
            'options' => $support_languages,
        ],
        [
            'field' => 'date_format',
            'value' => 'Y-m-d',
            'type' => 'select',
            'col' => 'col-6',
            'options' => [
                'Y-m-d',
                'd/m/Y',
                'm/d/Y',
                'd-m-Y'
            ],
        ],
        [
            'field' => 'time_format',
            'value' => 'H:i:s',
            'type' => 'select',
            'col' => 'col-6',
            'options' => [
                'H:i:s',
                'h:i A',
                'H:i',
                'h:i A',
            ],
        ]
    ],
    // Appearance
    'appearance' => [
        [
            'field' => 'theme',
            'value' => 'default',
            'type' => 'select', // Dropdown select for theme options
            'col' => 'col-6',
            'options' => ['default', 'dark', 'light'] // Dropdown options
        ],
        [
            'field' => 'font-family',
            'value' => 'Helvetica',
            'type' => 'select',
            'col' => 'col-6',
            'options' => $support_fonts
        ],
    ],
    // Notifications
    'notifications' => [
        [
            'field' => 'email_notifications',
            'value' => true,
            'type' => 'checkbox',
            'col' => 'col-12',
        ],
        [
            'field' => 'sms_notifications',
            'value' => false,
            'type' => 'checkbox',
            'col' => 'col-12',
        ]
    ],
    // Privacy Settings
    'privacy' => [
        [
            'field' => 'show_email',
            'value' => false,
            'type' => 'checkbox',
            'col' => 'col-12',
        ],
        [
            'field' => 'show_phone',
            'value' => false,
            'type' => 'checkbox',
            'col' => 'col-12',
        ],
        [
            'field' => 'data_sharing',
            'value' => false,
            'type' => 'checkbox',
            'col' => 'col-12',
        ]
    ],
    // Account Settings
    'account' => [
        [
            'field' => 'account_privacy',
            'value' => 'private',
            'type' => 'select',
            'col' => 'col-6',
            'options' => ['private', 'public'],
        ],
        [
            'field' => 'two_factor_auth',
            'value' => true,
            'type' => 'checkbox',
            'col' => 'col-12',
        ],
        [
            'field' => 'email_visibility',
            'value' => false,
            'type' => 'checkbox',
            'col' => 'col-12',
        ],
        [
            'field' => 'sms_visibility',
            'value' => false,
            'type' => 'checkbox',
            'col' => 'col-12',
        ]
    ],
];
define("DEFAULT_USER_SETTINGS", $user_settings);
$system_settings = [
    // General Settings
    'general' => [
        [
            'field' => 'site_name',
            'value' => 'My Website',
            'type' => 'text',
            'col' => 'col-12'
        ],
        [
            'field' => 'site_description',
            'value' => 'My Website is best website',
            'type' => 'textarea',
            'col' => 'col-12'
        ],
        [
            'field' => 'site_keywords',
            'value' => 'website, best, services, online',
            'type' => 'tags',
            'col' => 'col-12'
        ]
    ],
    // Appearance
    'appearance' => [
        [
            'field' => 'logo_url',
            'value' => '/assets/images/logo.png',
            'type' => 'text', // Text input for the URL
            'col' => 'col-12'
        ],
        [
            'field' => 'favicon_url',
            'value' => '/assets/images/favicon.ico',
            'type' => 'text', // Text input for the URL
            'col' => 'col-12'
        ]
    ],
    // Email Settings
    'email' => [
        [
            'field' => 'smtp_host',
            'value' => 'smtp.example.com',
            'type' => 'text', // Text input for SMTP host
            'col' => 'col-12'
        ],
        [
            'field' => 'smtp_port',
            'value' => 587,
            'type' => 'number', // Numeric input for SMTP port
            'col' => 'col-6'
        ],
        [
            'field' => 'smtp_encryption',
            'value' => 'tls',
            'type' => 'select', // Dropdown select for encryption options
            'col' => 'col-6',
            'options' => ['tls', 'ssl', 'none'] // Encryption options
        ],
        [
            'field' => 'smtp_user',
            'value' => 'smtp_user@example.com',
            'type' => 'email', // Email input type
            'col' => 'col-12'
        ],
        [
            'field' => 'smtp_password',
            'value' => 'secure_password',
            'type' => 'password', // Password input field
            'col' => 'col-12'
        ],
    ],
    // Performance
    'performance' => [
        [
            'field' => 'cache_enabled',
            'value' => true,
            'type' => 'checkbox',
            'col' => 'col-12'
        ],
        [
            'field' => 'cache_lifetime',
            'value' => 86400,
            'type' => 'number',
            'col' => 'col-6'
        ],
        [
            'field' => 'compression',
            'value' => true,
            'type' => 'checkbox',
            'col' => 'col-12'
        ],
        [
            'field' => 'lazy_loading_enabled',
            'value' => true,
            'type' => 'checkbox',
            'col' => 'col-12'
        ],
        [
            'field' => 'gzip_compression',
            'value' => true,
            'type' => 'checkbox',
            'col' => 'col-12'
        ]
    ],
    // Analytics
    'analytics' => [
        [
            'field' => 'google_analytics_id',
            'value' => 'UA-XXXXXX-X',
            'type' => 'text',
            'col' => 'col-12'
        ],
        [
            'field' => 'enable_tracking',
            'value' => true,
            'type' => 'checkbox',
            'col' => 'col-12'
        ],
        [
            'field' => 'facebook_pixel_id',
            'value' => '1234567890',
            'type' => 'text',
            'col' => 'col-12'
        ],
        [
            'field' => 'tracking_code',
            'value' => '',
            'type' => 'textarea',
            'col' => 'col-12'
        ]
    ],
    // User Management
    'user_management' => [
        [
            'field' => 'default_user_role',
            'value' => 'subscriber',
            'type' => 'select', // Dropdown for user role selection
            'col' => 'col-6',
            'options' => array_keys($user_roles) // Role options
        ],
        [
            'field' => 'allow_user_registration',
            'value' => true,
            'type' => 'checkbox', // Checkbox for allowing user registration
            'col' => 'col-12'
        ],
        [
            'field' => 'multi_language_support',
            'value' => false,
            'type' => 'checkbox', // Checkbox for enabling/disabling multi-language support
            'col' => 'col-12'
        ]
    ],
    // API
    'api' => [
        [
            'field' => 'api_enabled',
            'value' => true,
            'type' => 'checkbox', // Checkbox for enabling/disabling API
            'col' => 'col-12'
        ],
        [
            'field' => 'api_key_lifetime',
            'value' => 3600,
            'type' => 'number', // Numeric input for API key lifetime in seconds
            'col' => 'col-6'
        ],
        [
            'field' => 'allowed_api_origins',
            'value' => 'https://example.com,https://api.example.com',
            'type' => 'tags', // Text input for allowed API origins (comma-separated or array)
            'col' => 'col-12'
        ],
        [
            'field' => 'api_rate_limit',
            'value' => 1000,
            'type' => 'number',
            'col' => 'col-6'
        ]
    ],
    // File Uploads
    'file_uploads' => [
        [
            'field' => 'max_upload_size',
            'value' => 10485760,
            'type' => 'number', // Numeric input for max upload size in bytes
            'col' => 'col-6'
        ],
        [
            'field' => 'allowed_file_types',
            'value' => 'jpg, png, gif, pdf, docx',
            'type' => 'tags', // Text input for allowed file types (comma-separated or array)
            'col' => 'col-12'
        ]
    ],
    // Maintenance
    'maintenance' => [
        [
            'field' => 'maintenance_mode',
            'value' => false,
            'type' => 'checkbox', // Checkbox for enabling/disabling maintenance mode
            'col' => 'col-12'
        ],
        [
            'field' => 'maintenance_message',
            'value' => 'The site is currently under maintenance. Please check back later.',
            'type' => 'textarea', // Textarea for the maintenance message
            'col' => 'col-12'
        ]
    ],
    // Advanced Features
    'advanced' => [
        [
            'field' => 'custom_css',
            'value' => '',
            'type' => 'textarea', // Textarea for custom CSS
            'col' => 'col-12'
        ],
        [
            'field' => 'custom_js',
            'value' => '',
            'type' => 'textarea', // Textarea for custom JS
            'col' => 'col-12'
        ]
    ],
];
define("DEFAULT_SYSTEM_SETTINGS", $system_settings);

$projectStatus = array(
    array('not_started', 'Not Started'),
    array('in_progress', 'Inprogress'),
    array('completed', 'Completed'),
    array('on_hold', 'On Hold'),
    array('cancelled', 'Cancelled'),
);
define('DEFAULT_PROJECT_STATUS', $projectStatus);

$priorities = [
    'low' => 'Low',
    'medium' => 'Medium',
    'high' => 'High',
    'critical' => 'Critical',
];
$status = [
    'not_started' => 'Not Started',
    'todo' => 'Todo',
    'backlog' => 'Backlog',
    'in_progress' => 'Inprogress',
    'completed' => 'Completed',
    'on_hold' => 'On Hold',
    'cancelled' => 'Cancelled',
];

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

$dailyChecklist = [
    ['id' => 1, 'name' => 'Wake up at your preferred time'],
    ['id' => 2, 'name' => 'Make your bed'],
    ['id' => 3, 'name' => 'Drink a glass of water'],
    ['id' => 4, 'name' => 'Exercise or stretch (15-30 mins)'],
    ['id' => 5, 'name' => 'Take a shower and get dressed'],
    ['id' => 6, 'name' => 'Eat a healthy breakfast'],
    ['id' => 7, 'name' => 'Review your daily plan or schedule'],
    ['id' => 8, 'name' => 'Check and reply to important emails or messages'],
    ['id' => 9, 'name' => 'Prioritize tasks for the day (set 3-5 main goals)'],
    ['id' => 10, 'name' => 'Focus on top-priority task for 1-2 hours'],
    ['id' => 11, 'name' => 'Take a short break (5-10 mins)'],
    ['id' => 12, 'name' => 'Attend meetings or calls'],
    ['id' => 13, 'name' => 'Review and update your task progress'],
    ['id' => 14, 'name' => 'Wash dishes after meals'],
    ['id' => 15, 'name' => 'Clean up a room or area (e.g., living room or kitchen)'],
    ['id' => 16, 'name' => 'Do laundry (if needed)'],
    ['id' => 17, 'name' => 'Organize clutter or tidy up workspaces'],
    ['id' => 18, 'name' => 'Meditate for 5-10 minutes'],
    ['id' => 19, 'name' => 'Read or learn something new for 30 minutes'],
    ['id' => 20, 'name' => 'Practice a hobby or skill (e.g., drawing, writing, coding)'],
    ['id' => 21, 'name' => 'Take breaks to relax and reset throughout the day'],
    ['id' => 22, 'name' => 'Plan the next day’s tasks or goals'],
    ['id' => 23, 'name' => 'Spend time with family or friends'],
    ['id' => 24, 'name' => 'Prepare dinner or enjoy a healthy meal'],
    ['id' => 25, 'name' => 'Unwind (read, watch a show, etc.)'],
    ['id' => 26, 'name' => 'Reflect on the day (what went well, what to improve)'],
    ['id' => 27, 'name' => 'Get ready for bed (brush teeth, wash face)'],
    ['id' => 28, 'name' => 'Sleep at a consistent time (get 7-8 hours of rest)'],
    ['id' => 29, 'name' => 'Drink 8 glasses of water'],
    ['id' => 30, 'name' => 'Eat 3 servings of vegetables'],
    ['id' => 31, 'name' => 'Walk 10,000 steps or exercise for 30 minutes'],
    ['id' => 32, 'name' => 'Take vitamins or medications'],
    ['id' => 33, 'name' => 'Review project deadlines'],
    ['id' => 34, 'name' => 'Focus on a deep work session (2-3 hours)'],
    ['id' => 35, 'name' => 'Attend any scheduled meetings'],
    ['id' => 36, 'name' => 'Follow up with clients or colleagues'],
    ['id' => 37, 'name' => 'Review course material for 1-2 hours'],
    ['id' => 38, 'name' => 'Practice exercises or take notes'],
    ['id' => 39, 'name' => 'Prepare for upcoming tests or exams'],
    ['id' => 40, 'name' => 'Review previous study sessions'],
    ['id' => 41, 'name' => 'Track your spending for the day'],
];
define("DEFAULT_DAILY_CHECKLISTS", $dailyChecklist);

$Currency_symbols = [
    'USD' => '$',
    'EUR' => '€',
    'GBP' => '£',
    'JPY' => '¥',
    'CAD' => '$',
    'AUD' => '$',
    'CHF' => 'CHF',
    'CNY' => '¥',
    'INR' => '₹',
    'KRW' => '₩',
    'BRL' => 'R$',
    'RUB' => '₽',
    'VND' => '₫'
];
define("DEFAULT_CURRENCY", $Currency_symbols);

const DEFAULT_BLOG_CATEGORIES = [
    'develop' => 'Develop',
    'wordpress' => 'Wordpress',
    'reactjs' => 'ReactJS',
    'salesforce' => 'Salesforce',
    'php' => 'PHP',
    'mysql' => 'MySql',
    'english' => 'English',
    'other' => 'Other',
];

$nations = [
    "Afghanistan",
    "Albania",
    "Algeria",
    "Andorra",
    "Angola",
    "Argentina",
    "Armenia",
    "Australia",
    "Austria",
    "Azerbaijan",
    "Bahamas",
    "Bahrain",
    "Bangladesh",
    "Barbados",
    "Belarus",
    "Belgium",
    "Belize",
    "Benin",
    "Bhutan",
    "Bolivia",
    "Bosnia and Herzegovina",
    "Botswana",
    "Brazil",
    "Brunei",
    "Bulgaria",
    "Burkina Faso",
    "Burundi",
    "Cabo Verde",
    "Cambodia",
    "Cameroon",
    "Canada",
    "Central African Republic",
    "Chad",
    "Chile",
    "China",
    "Colombia",
    "Comoros",
    "Congo (Congo-Brazzaville)",
    "Costa Rica",
    "Croatia",
    "Cuba",
    "Cyprus",
    "Czechia (Czech Republic)",
    "Denmark",
    "Djibouti",
    "Dominica",
    "Dominican Republic",
    "Ecuador",
    "Egypt",
    "El Salvador",
    "Equatorial Guinea",
    "Eritrea",
    "Estonia",
    "Eswatini (fmr. Swaziland)",
    "Ethiopia",
    "Fiji",
    "Finland",
    "France",
    "Gabon",
    "Gambia",
    "Georgia",
    "Germany",
    "Ghana",
    "Greece",
    "Grenada",
    "Guatemala",
    "Guinea",
    "Guinea-Bissau",
    "Guyana",
    "Haiti",
    "Holy See",
    "Honduras",
    "Hungary",
    "Iceland",
    "India",
    "Indonesia",
    "Iran",
    "Iraq",
    "Ireland",
    "Israel",
    "Italy",
    "Jamaica",
    "Japan",
    "Jordan",
    "Kazakhstan",
    "Kenya",
    "Kiribati",
    "Kuwait",
    "Kyrgyzstan",
    "Laos",
    "Latvia",
    "Lebanon",
    "Lesotho",
    "Liberia",
    "Libya",
    "Liechtenstein",
    "Lithuania",
    "Luxembourg",
    "Madagascar",
    "Malawi",
    "Malaysia",
    "Maldives",
    "Mali",
    "Malta",
    "Marshall Islands",
    "Mauritania",
    "Mauritius",
    "Mexico",
    "Micronesia",
    "Moldova",
    "Monaco",
    "Mongolia",
    "Montenegro",
    "Morocco",
    "Mozambique",
    "Myanmar (formerly Burma)",
    "Namibia",
    "Nauru",
    "Nepal",
    "Netherlands",
    "New Zealand",
    "Nicaragua",
    "Niger",
    "Nigeria",
    "North Korea",
    "North Macedonia",
    "Norway",
    "Oman",
    "Pakistan",
    "Palau",
    "Palestine State",
    "Panama",
    "Papua New Guinea",
    "Paraguay",
    "Peru",
    "Philippines",
    "Poland",
    "Portugal",
    "Qatar",
    "Romania",
    "Russia",
    "Rwanda",
    "Saint Kitts and Nevis",
    "Saint Lucia",
    "Saint Vincent and the Grenadines",
    "Samoa",
    "San Marino",
    "Sao Tome and Principe",
    "Saudi Arabia",
    "Senegal",
    "Serbia",
    "Seychelles",
    "Sierra Leone",
    "Singapore",
    "Slovakia",
    "Slovenia",
    "Solomon Islands",
    "Somalia",
    "South Africa",
    "South Korea",
    "South Sudan",
    "Spain",
    "Sri Lanka",
    "Sudan",
    "Suriname",
    "Sweden",
    "Switzerland",
    "Syria",
    "Tajikistan",
    "Tanzania",
    "Thailand",
    "Timor-Leste",
    "Togo",
    "Tonga",
    "Trinidad and Tobago",
    "Tunisia",
    "Turkey",
    "Turkmenistan",
    "Tuvalu",
    "Uganda",
    "Ukraine",
    "United Arab Emirates",
    "United Kingdom",
    "United States of America",
    "Uruguay",
    "Uzbekistan",
    "Vanuatu",
    "Venezuela",
    "Vietnam",
    "Yemen",
    "Zambia",
    "Zimbabwe",
];
define("DEFAULT_NATIONALITY", $nations);

$player_cards = [
    'legend',
    'normal',
    'young',
    'demand'
];
define('DEFAULT_PLAYER_CARD', $player_cards);

$finance_categories = [
    [
        'slug' => 'rent',
        'icon' => '🏠',
        'title' => 'Rent',
        'amount' => 0, // Amount spent in this category
    ],
    [
        'slug' => 'utilities',
        'icon' => '💡',
        'title' => 'Utilities',
        'amount' => 0,
    ],
    [
        'slug' => 'groceries',
        'icon' => '🥕',
        'title' => 'Groceries',
        'amount' => 0,
    ],
    [
        'slug' => 'transportation',
        'icon' => '🚗',
        'title' => 'Transportation',
        'amount' => 0,
    ],
    [
        'slug' => 'healthcare',
        'icon' => '💊',
        'title' => 'Healthcare',
        'amount' => 0,
    ],
    [
        'slug' => 'insurance',
        'icon' => '🛡️',
        'title' => 'Insurance',
        'amount' => 0,
    ],
    [
        'slug' => 'entertainment',
        'icon' => '🎮',
        'title' => 'Entertainment',
        'amount' => 0,
    ],
    [
        'slug' => 'dining_out',
        'icon' => '🍽️',
        'title' => 'Dining Out',
        'amount' => 0,
    ],
    [
        'slug' => 'subscriptions',
        'icon' => '📅',
        'title' => 'Subscriptions',
        'amount' => 0,
    ],
    [
        'slug' => 'loans_and_debts',
        'icon' => '💳',
        'title' => 'Loans and Debts',
        'amount' => 0,
    ],
    [
        'slug' => 'savings',
        'icon' => '💰',
        'title' => 'Savings',
        'amount' => 0,
    ],
    [
        'slug' => 'education',
        'icon' => '🎓',
        'title' => 'Education',
        'amount' => 0,
    ],
    [
        'slug' => 'gifts',
        'icon' => '🎁',
        'title' => 'Gifts',
        'amount' => 0,
    ],
    [
        'slug' => 'charity',
        'icon' => '🤝',
        'title' => 'Charity',
        'amount' => 0,
    ],
    [
        'slug' => 'clothing',
        'icon' => '👗',
        'title' => 'Clothing',
        'amount' => 0,
    ],
    [
        'slug' => 'home_improvement',
        'icon' => '🔨',
        'title' => 'Home Improvement',
        'amount' => 0,
    ],
    [
        'slug' => 'hobbies',
        'icon' => '🎨',
        'title' => 'Hobbies',
        'amount' => 0,
    ],
    [
        'slug' => 'personal_care',
        'icon' => '💅',
        'title' => 'Personal Care',
        'amount' => 0,
    ],
    [
        'slug' => 'other',
        'icon' => '❓',
        'title' => 'Other',
        'amount' => 0,
    ],
];

$months = [
    1 => "January",
    2 => "February",
    3 => "March",
    4 => "April",
    5 => "May",
    6 => "June",
    7 => "July",
    8 => "August",
    9 => "September",
    10 => "October",
    11 => "November",
    12 => "December",
];

$payment_types = [
    'monthly' => 'Monthly',
    'yearly' => 'Yearly',
];

$event_types = [
    "primary" => "Primary",
    "success" => "Success",
    "danger" => "Danger",
    "info" => "Info",
    "dark" => "Dark",
    "warning" => "Warning"
];

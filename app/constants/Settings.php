<?php

namespace App\Constants;

class Settings
{
    public const SUPPORT_FONTS = [
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

    public const SUPPORT_LANGUAGES = [
        'en' => 'English',
        'vi' => 'Vietnamese'
    ];

    public const USER_SETTINGS = [
        // General Settings
        'general' => [
            [
                'field' => 'timezone',
                'value' => 'UTC',
                'type' => 'select',
                'col' => 'col-6',
                'options' => [],
            ],
            [
                'field' => 'language',
                'value' => 'en',
                'type' => 'select',
                'col' => 'col-6',
                'options' => self::SUPPORT_LANGUAGES,
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
                'options' => []
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

    public const SYSTEM_SETTINGS = [
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
                'options' => [] // Role options
                // 'options' => array_keys($user_roles) // Role options
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
}
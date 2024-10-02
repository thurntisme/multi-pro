<?php

$versionIcons = ["stack-line", "vip-diamond-line", "gift-line", "shield-star-line", "user-smile-line", "fire-line"];
define("DEFAULT_VERSION_ICONS", $versionIcons);

$projectVersions = [
    '1.0.0' => [
        'release_date' => '2024-10-01',
        'features' => [
            'Initial Project',
            'User authentication',
            'Basic dashboard',
            'Basic Project Management',
            'Basic Finance Project'
        ]
    ],
    '1.0.1' => [
        'release_date' => '2024-10-02',
        'features' => [
            'Initial Version Timeline',
        ]
    ],
];
define("DEFAULT_VERSION_TIMELINE", $projectVersions);

<?php

$checklist = array(
    array(
        'isDone' => true,
        'title' => 'Finish crud tech stack - using blog list template',
        'deadline' => '2024-11-20'
    ),
    array(
        'isDone' => false,
        'title' => 'Load json data',
        'deadline' => '2024-11-23'
    ),
    array(
        'isDone' => false,
        'title' => 'Export data Maintain Web',
        'deadline' => '2024-11-24'
    ),
    array(
        'isDone' => false,
        'title' => 'Load json data on Seo Checklist',
        'deadline' => '2024-11-25'
    ),
    array(
        'isDone' => false,
        'title' => 'Load json data on Web Dev Checklist',
        'deadline' => '2024-11-26'
    ),
    array(
        'isDone' => false,
        'title' => 'Load json data on Web Secure Checklist',
        'deadline' => '2024-11-27'
    ),
    array(
        'isDone' => false,
        'title' => 'Update user profile',
        'deadline' => '2024-11-29'
    ),
    array(
        'isDone' => false,
        'title' => 'Feature User Activity',
        'deadline' => '2024-11-30'
    )
);
$event = array(
    array(
        'date' => '2024-12-01',
        'title' => 'Release version 1.1.0',
        'description' => 'Release version with basic feature',
    )
);
$links = array(
    array(
        'title' => 'Facebook',
        'url' => 'https://www.facebook.com',
    ),
    array(
        'title' => 'Gmail',
        'url' => 'https://www.gmail.com',
    ),
    array(
        'title' => 'Chat GPT',
        'url' => 'https://www.chatgpt.com',
    ),
);

$dashboard = array(
    'checklist' => $checklist,
    'event' => $event,
    'links' => DEFAULT_BOOKMARKS['Featured'],
);
define('DEFAULT_DASHBOARD_OPTIONS', $dashboard);

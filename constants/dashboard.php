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
        'date' => ['mon', 'wed', 'fri'],
        'start_time' => '20:00',
        'end_time' => '21:00',
        'content' => 'Learning Wordpress',
    ),
    array(
        'date' => ['tue', 'thu', 'sat',],
        'start_time' => '20:00',
        'end_time' => '21:00',
        'content' => 'Learning reactjs',
    ),
    array(
        'date' => ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'],
        'start_time' => '21:00',
        'end_time' => '22:00',
        'content' => 'Learning English',
    ),
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
    'links' => DEFAULT_BOOKMARKS[0]['links'],
);
define('DEFAULT_DASHBOARD_OPTIONS', $dashboard);

$quickApps = array(
    array(
        'title' => 'To Do',
        'slug' => 'todo',
    ),
    array(
        'title' => 'Note',
        'slug' => 'note',
    ),
    array(
        'title' => 'Finance',
        'slug' => 'finance',
    ),
    array(
        'title' => 'Calendar',
        'slug' => 'calendar',
    ),
    array(
        'title' => 'Website',
        'slug' => 'website',
    ),
    array(
        'title' => 'Book',
        'slug' => 'book',
    ),
    array(
        'title' => 'Course',
        'slug' => 'course',
    ),
    array(
        'title' => 'Blog',
        'slug' => 'blog',
    ),
    array(
        'title' => 'Game',
        'slug' => 'football-manager',
    )
);
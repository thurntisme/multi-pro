<?php

namespace App\Constants;

class Common
{
    public const MONTHS = [
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

    public const STATUS = [
        'not_started' => 'Not Started',
        'todo' => 'Todo',
        'backlog' => 'Backlog',
        'in_progress' => 'Inprogress',
        'completed' => 'Completed',
        'on_hold' => 'On Hold',
        'cancelled' => 'Cancelled',
    ];

    public const PRIORITIES = [
        'low' => 'Low',
        'medium' => 'Medium',
        'high' => 'High',
        'critical' => 'Critical',
    ];

    public const PROJECT_TYPES = [
        'owner' => 'Owner',
        'freelancer' => 'Freelancer',
    ];

    public const DATE_FILTER_OPTIONS = [
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
    ];
}
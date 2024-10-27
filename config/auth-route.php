<?php

function getPageData($slug)
{
    $routes = [
        '' => ['url' => DIR . '/views/dashboard.php', 'title' => 'Dashboard'],
        'profile' => ['url' => DIR . '/views/profile.php', 'title' => 'Profile'],
        'settings' => ['url' => DIR . '/views/settings.php', 'title' => 'Settings'],
        'devices' => ['url' => DIR . '/views/devices.php', 'title' => 'Devices'],
        'logout' => ['url' => DIR . '/views/logout.php', 'title' => 'Logout'],
        'projects' => ['url' => DIR . '/views/project-overview.php', 'title' => 'Project Overview'],
        'projects/overview' => ['url' => DIR . '/views/project-overview.php', 'title' => 'Project Overview'],
        'projects/list' => ['url' => DIR . '/views/project-list.php', 'title' => 'Project List'],
        'projects/create' => ['url' => DIR . '/views/project-modify.php', 'title' => 'Create Project'],
        'projects/edit' => ['url' => DIR . '/views/project-modify.php', 'title' => 'Edit Project'],
        'projects/detail' => ['url' => DIR . '/views/project-detail.php', 'title' => 'Project Detail'],
        'projects/report' => ['url' => DIR . '/views/report-working.php', 'title' => 'Project Report'],
        'finance' => ['url' => DIR . '/views/finance.php', 'title' => 'Finance'],
        'finance/budget' => ['url' => DIR . '/views/budget.php', 'title' => 'Budget'],
        'finance/income' => ['url' => DIR . '/views/income.php', 'title' => 'Income'],
        'finance/expenses' => ['url' => DIR . '/views/expenses.php', 'title' => 'Expenses'],
        'version' => ['url' => DIR . '/views/version.php', 'title' => 'Version'],
        'todo' => ['url' => DIR . '/views/todo.php', 'title' => 'To-Do'],
        'daily-checklist' => ['url' => DIR . '/views/daily-checklist.php', 'title' => 'Daily Checklist'],
        'bookmark' => ['url' => DIR . '/views/bookmark.php', 'title' => 'Bookmark'],
        'subscription' => ['url' => DIR . '/views/subscription.php', 'title' => 'Subscription'],
        'subscription/new' => ['url' => DIR . '/views/subscription-adjust.php', 'title' => 'New Subscription'],
        'subscription/edit' => ['url' => DIR . '/views/subscription-adjust.php', 'title' => 'Edit Subscription'],
        'subscription/detail' => ['url' => DIR . '/views/subscription-detail.php', 'title' => 'Subscription Detail'],
        'blog' => ['url' => DIR . '/views/blog.php', 'title' => 'Blog'],
        'blog/new' => ['url' => DIR . '/views/blog-adjust.php', 'title' => 'New Blog'],
        'blog/edit' => ['url' => DIR . '/views/blog-adjust.php', 'title' => 'Edit Blog'],
        'blog/detail' => ['url' => DIR . '/views/blog-detail.php', 'title' => 'Blog Detail'],
        'course' => ['url' => DIR . '/views/course.php', 'title' => 'Course'],
        'course/new' => ['url' => DIR . '/views/course-adjust.php', 'title' => 'New Course'],
        'course/edit' => ['url' => DIR . '/views/course-adjust.php', 'title' => 'Edit Course'],
        'course/detail' => ['url' => DIR . '/views/course-detail.php', 'title' => 'Course Detail'],
        'git' => ['url' => DIR . '/views/git.php', 'title' => 'Git'],
        'git/new' => ['url' => DIR . '/views/git-adjust.php', 'title' => 'New Git'],
        'git/edit' => ['url' => DIR . '/views/git-adjust.php', 'title' => 'Edit Git'],
        'git/detail' => ['url' => DIR . '/views/git-detail.php', 'title' => 'Git Detail'],
        'book' => ['url' => DIR . '/views/book.php', 'title' => 'Book'],
        'book/new' => ['url' => DIR . '/views/book-adjust.php', 'title' => 'New Book'],
        'book/edit' => ['url' => DIR . '/views/book-adjust.php', 'title' => 'Edit Book'],
        'book/detail' => ['url' => DIR . '/views/book-detail.php', 'title' => 'Book Detail'],
        'tip' => ['url' => DIR . '/views/tip.php', 'title' => 'Tip'],
        'tip/new' => ['url' => DIR . '/views/tip-adjust.php', 'title' => 'New Tip'],
        'tip/edit' => ['url' => DIR . '/views/tip-adjust.php', 'title' => 'Edit Tip'],
        'tip/detail' => ['url' => DIR . '/views/tip-detail.php', 'title' => 'Tip Detail'],
        'code' => ['url' => DIR . '/views/code.php', 'title' => 'Code'],
        'code/new' => ['url' => DIR . '/views/code-adjust.php', 'title' => 'New Code'],
        'code/edit' => ['url' => DIR . '/views/code-adjust.php', 'title' => 'Edit Code'],
        'code/detail' => ['url' => DIR . '/views/code-detail.php', 'title' => 'Code Detail'],
        // redirectUser
        'dashboard' => ['url' => DIR . '/functions/redirectUser.php', 'title' => 'Redirect'],
        'login' => ['url' => DIR . '/functions/redirectUser.php', 'title' => 'Redirect'],
        'register' => ['url' => DIR . '/functions/redirectUser.php', 'title' => 'Redirect'],
        'forgot-password' => ['url' => DIR . '/functions/redirectUser.php', 'title' => 'Redirect']
    ];

    // If the slug exists as a key in the routes array, return the corresponding page data
    return $routes[$slug] ?? ['url' => DIR . '/views/404.php', 'title' => 'Page Not Found'];
}
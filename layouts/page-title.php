<?php

function createBreadcrumbs($url)
{
    $parsedUrl = parse_url($url);
    $path = trim($parsedUrl['path'], '/');
    $segments = explode('/', $path);
    $baseUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . (isset($parsedUrl['port']) ? ':' . $parsedUrl['port'] : '');

    // Add the "Dashboard" as the first breadcrumb
    $breadcrumbs = [
        [
            'label' => 'Dashboard',
            'link' => $baseUrl // Link to your dashboard page
        ]
    ];

    $currentPath = '';

    foreach ($segments as $key => $segment) {
        $currentPath .= '/' . $segment;
        $crumbPath = $baseUrl . $currentPath;

        if ($key === count($segments) - 1 && isset($parsedUrl['query'])) {
            $crumbPath .= '?' . $parsedUrl['query'];
        }

        $breadcrumbs[] = [
            'label' => ucfirst($segment),
            'link' => ($key === count($segments) - 1) ? null : $crumbPath
        ];
    }

    return $breadcrumbs;
}

// Get the current URL
$currentUrl = getCurrentUrl();

// Create breadcrumbs
$breadcrumbs = createBreadcrumbs($currentUrl);


?>

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="mb-3">
        </div>
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <?php
                    // Display breadcrumbs
                    foreach ($breadcrumbs as $key => $breadcrumb) {
                        if (!is_null($breadcrumb['link'])) {
                            echo '<li class="breadcrumb-item"><a href="' . $breadcrumb['link'] . '">' . $breadcrumb['label'] . '</a></li>';
                        } else {
                            if (!empty($breadcrumb['label']))
                                echo '<li class="breadcrumb-item active">' . $breadcrumb['label'] . '</li>';
                        }
                    }
                    ?>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->
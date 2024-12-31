<?php

function getDayWithSuffix($day)
{
    if (!in_array(($day % 100), [11, 12, 13])) {
        switch ($day % 10) {
            case 1:
                return $day . 'st';
            case 2:
                return $day . 'nd';
            case 3:
                return $day . 'rd';
        }
    }
    return $day . 'th';
}

function home_url($path = '')
{
    // Get the scheme (http or https)
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';

    // Get the host (e.g., example.com)
    $host = $_SERVER['HTTP_HOST'];

    // Get the base URL
    $base_url = $scheme . '://' . $host;

    if (!empty(HOME_PATH)) {
        $base_url .= '/' . HOME_PATH;
    }

    // Append the path if provided
    if ($path) {
        $base_url .= '/' . ltrim($path, '/');
    }

    return $base_url;
}

function timeAgo($timestamp)
{
    $timeDifference = time() - strtotime($timestamp);

    if ($timeDifference < 60) {
        return $timeDifference . 's ago';
    } elseif ($timeDifference < 3600) {
        return round($timeDifference / 60) . ' minutes ago';
    } elseif ($timeDifference < 86400) {
        return round($timeDifference / 3600) . ' hours ago';
    } elseif ($timeDifference < 604800) {
        return round($timeDifference / 86400) . ' days ago';
    } else {
        return date("Y-m-d", strtotime($timestamp));
    }
}

function getCurrentDaySchedule($schedule)
{
    $currentDay = date('l'); // Get the current day of the week

    foreach ($schedule as $daySchedule) {
        if ($daySchedule['day'] === $currentDay) {
            return $daySchedule['events'];
        }
    }

    return null; // Return null if no schedule is found for the current day
}

function getTimeLeft($timeString)
{
    // Get the current date and time
    $currentDate = new DateTime();

    // Define the target date and time
    $targetDate = new DateTime();
    list($hours, $mins) = explode(':', $timeString);
    $targetDate->setTime($hours, $mins, 0);

    // Calculate the difference between the target date and the current date
    $interval = $currentDate->diff($targetDate);

    // Convert interval to total seconds
    $totalSeconds = ($interval->days * 24 * 60 * 60) + ($interval->h * 3600) + ($interval->i * 60) + $interval->s;

    // Determine the appropriate time unit
    if ($totalSeconds < 60) {
        return $totalSeconds . "s left";
    } elseif ($totalSeconds < 3600) { // Less than 60 minutes
        $minutes = floor($totalSeconds / 60);
        return $minutes . "m left";
    } else { // More than or equal to 60 minutes
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        return $hours . "h, " . $minutes . "m left";
    }
}

function convertToTitleCase($string)
{
    // Replace underscores with spaces
    $string = str_replace('_', ' ', $string);

    // Capitalize the first letter of each word
    $string = ucwords($string);

    return $string;
}

function getLastSegmentFromUrl()
{
    $url = $_SERVER['REQUEST_URI'];

    // Parse the URL to get the path
    $parsedUrl = parse_url($url, PHP_URL_PATH);

    // Remove the leading slash if it exists
    $trimmedPath = ltrim($parsedUrl, '/');

    // Split the path into segments by "/"
    $segments = explode('/', $trimmedPath);

    // Get the last segment
    return end($segments);
}

function generateBreadcrumbs($pageTitle, $separator = ' > ')
{
    // Get the current URL path
    $path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

    // Explode the path into an array
    $pathArray = explode('/', $path);

    // Initialize an empty array to store the breadcrumbs
    $breadcrumbs = [];

    if (count($pathArray) > 1) {
        // Loop through each path segment
        foreach ($pathArray as $key => $value) {
            // Build the breadcrumb URL
            $breadcrumbUrl = home_url($key === 0 ? "/" : implode('/', array_slice($pathArray, 1, $key)));

            // Capitalize and replace hyphens with spaces for better readability
            $crumbName = $key === 0 ? 'Dashboard' : ucwords(str_replace('-', ' ', $value));

            // Add each breadcrumb to the array
            $breadcrumbs[] = $key === count($pathArray) - 1 ? "<span>$crumbName</span>" : "<a href='$breadcrumbUrl'>$crumbName</a>";
        }

        // Join the breadcrumbs using the separator and return the result
        return implode($separator, $breadcrumbs);
    } else {
        return $pageTitle;
    }
}

// Define the badge classes based on the status
$badgeClasses = [
    'not_started' => 'light text-body',
    'todo' => 'light text-body',
    'backlog' => 'dark',
    'complete' => 'success',
    'in_progress' => 'primary',
    'on_hold' => 'warning',
    'cancelled' => 'danger',
];
function renderStatusBadge($status, $element = 'span', $fontSize = '10')
{
    global $badgeClasses;
    // Default badge class if status is not found
    $badgeClass = isset($badgeClasses[$status]) ? $badgeClasses[$status] : 'secondary';

    // Return the badge HTML
    return "<$element class='badge bg-$badgeClass fs-$fontSize'>" . ucfirst(str_replace("_", " ", $status)) . "</$element>";
}

// Define the badge classes based on priority levels
$priorityClasses = [
    'low' => 'success',
    'medium' => 'warning',
    'high' => 'danger',
    'critical' => 'dark',
];
// Function to render priority badge
function renderPriorityBadge($priority, $element = 'span', $fontSize = '10')
{
    global $priorityClasses;
    // Default badge class if priority is not found
    $badgeClass = isset($priorityClasses[$priority]) ? $priorityClasses[$priority] : 'secondary';

    // Return the badge HTML
    return "<$element class='badge bg-$badgeClass fs-$fontSize'>" . ucfirst($priority) . "</$element>";
}

function getStatusBadge($status)
{
    global $badgeClasses;
    // Default badge class if status is not found
    $badgeClassName = isset($badgeClasses[$status]) ? $badgeClasses[$status] : 'secondary';
    $badgeLabel = ucfirst(str_replace("_", " ", $status));

    // Return the badge HTML
    return ["className" => $badgeClassName, "label" => $badgeLabel];
}

function activeClassName($url, $className = 'active', $isParent = false)
{
    $currentUrl = getFirstParamInUrl();
    return $currentUrl === $url ? $className : '';
}

function includeFileWithVariables($filePath, $variables = array(), $print = true)
{
    global $commonController;
    $output = NULL;
    if (file_exists($filePath)) {
        // Extract the variables to a local namespace
        extract($variables);

        // Start output buffering
        ob_start();

        // Include the template file
        include $filePath;

        // End buffering and return its contents
        $output = ob_get_clean();
    }
    if ($print) {
        print $output;
    }
    return $output;
}

function generatePageUrl($params = [])
{
    // Get the current URL
    $currentUrl = getCurrentUrl();

    // Parse the URL to get its components
    $urlParts = parse_url($currentUrl);
    $queryParams = $_GET;

    // Parse the query string into an associative array
    if (isset($urlParts['query'])) {
        parse_str($urlParts['query'], $queryParams);
    }

    // Replace or add the new parameters
    foreach ($params as $key => $value) {
        $queryParams[$key] = $value;
    }

    // Build the new query string
    $newQuery = http_build_query($queryParams);

    // Build the new URL with the updated query string
    $newUrl = home_url($urlParts['path'] . (($newQuery != "") ? ('?' . $newQuery) : ''));

    return $newUrl;
}

function getCurrentUrl()
{
    // Determine the protocol (http or https)
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

    // Get the host
    $host = $_SERVER['HTTP_HOST'];

    // Get the URI path without query parameters
    $uri = $_SERVER['REQUEST_URI'];
    $uriWithoutParams = parse_url($uri, PHP_URL_PATH);

    // Return the full URL without query parameters
    return $protocol . $host . $uriWithoutParams;
}

$dateRanges = [
    "" => [null, null], // All (no filtering)
    "today" => [date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')],
    "yesterday" => [date('Y-m-d 00:00:00', strtotime('-1 day')), date('Y-m-d 23:59:59', strtotime('-1 day'))],
    "this_week" => [date('Y-m-d 00:00:00', strtotime('monday this week')), date('Y-m-d 23:59:59', strtotime('sunday this week'))],
    "this_month" => [date('Y-m-01 00:00:00'), date('Y-m-t 23:59:59')],
    "this_year" => [date('Y-01-01 00:00:00'), date('Y-12-31 23:59:59')],
    "last_7days" => [date('Y-m-d 00:00:00', strtotime('-7 days')), date('Y-m-d 23:59:59')],
    "last_week" => [date('Y-m-d 00:00:00', strtotime('monday last week')), date('Y-m-d 23:59:59', strtotime('sunday last week'))],
    "last_month" => [date('Y-m-01 00:00:00', strtotime('-1 month')), date('Y-m-t 23:59:59', strtotime('-1 month'))],
    "last_year" => [date('Y-01-01 00:00:00', strtotime('-1 year')), date('Y-12-31 23:59:59', strtotime('-1 year'))],
];
function getDateRange($range)
{
    global $dateRanges;
    return isset($dateRanges[$range]) ? $dateRanges[$range] : [null, null];
}

function getTimezoneOffset()
{
    $localTimezone = date_default_timezone_get();

    // Create DateTime objects for UTC and local timezones
    $utc = new DateTime('now', new DateTimeZone('UTC'));
    $local = new DateTime('now', new DateTimeZone($localTimezone));

    // Calculate the difference between the two timezones
    $offset = $local->getOffset() - $utc->getOffset();

    // Convert offset to hours and format it
    $hours = $offset / 3600;
    $formattedOffset = ($hours >= 0 ? '+' : '-') . str_pad(abs($hours), 2, '0', STR_PAD_LEFT) . ' hours';

    return $formattedOffset;
}

function getFirstParamInUrl()
{
    // Get the full request URI
    $uri = $_SERVER['REQUEST_URI'];

    // Remove any query parameters if present
    $cleanUri = strtok($uri, '?');

    // Split the URI by "/"
    $uriParts = explode('/', trim($cleanUri, '/'));

    // Get specific parts of the URI
    $firstPart = $uriParts[1] ?? null;
    return $firstPart;
}

function extractPathFromCurrentUrl()
{
    // Build the current URL from the server variables
    $currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") .
        "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    // Parse the URL to get the path
    $parsedUrl = parse_url($currentUrl, PHP_URL_PATH);

    // Remove the leading slash
    return ltrim($parsedUrl, '/');
}

function convertAmount($amount)
{
    return number_format((int)$amount);
}

function truncateString($string, $maxLength = 30)
{
    // Check if the string length exceeds the maximum length
    if (strlen($string) > $maxLength) {
        // Truncate the string to the maximum length and add ellipsis
        return substr($string, 0, $maxLength) . '...';
    }
    return $string; // Return original string if it's within the limit
}

function getJsonFileData($filePath): array
{
    if (file_exists($filePath)) {
        return array_reverse(json_decode(file_get_contents($filePath), true) ?? []);
    } else {
        file_put_contents($filePath, json_encode([]));
        return [];
    }
}

function generateFormControl($id, $name, $value = '', $placeholder = '', $type = 'text', $label = '', $options = [])
{
    // Start the label if provided
    $labelHtml = '';
    if ($label) {
        $labelHtml = '<label class="form-label" for="' . htmlspecialchars($id) . '">' . htmlspecialchars($label) . '</label>';
    }

    // Handle different input types
    switch ($type) {
        case 'text':
        case 'number':
        case 'email':
        case 'password':
            // Generate regular input field
            $inputHtml = '<input type="' . htmlspecialchars($type) . '" class="form-control" id="' . htmlspecialchars($id) . '" name="' . htmlspecialchars($name) . '" placeholder="' . htmlspecialchars($placeholder) . '" value="' . htmlspecialchars($value) . '">';
            break;

        case 'textarea':
            // Generate textarea field
            $inputHtml = '<textarea class="form-control" id="' . htmlspecialchars($id) . '" name="' . htmlspecialchars($name) . '" placeholder="' . htmlspecialchars($placeholder) . '">' . htmlspecialchars($value) . '</textarea>';
            break;

        case 'select':
            // Generate select dropdown
            $inputHtml = '<select class="form-control" id="' . htmlspecialchars($id) . '" name="' . htmlspecialchars($name) . '" data-choices data-choices-search-' . (count($options) < 10 ? 'false' : 'true') . 'false data-choices-sorting-false >';
            foreach ($options as $key => $optionValue) {
                $selected = ($value == $key) ? ' selected' : ''; // Keep the selected value
                $inputHtml .= '<option value="' . htmlspecialchars($key) . '"' . $selected . '>' . htmlspecialchars(ucwords(str_replace('_', ' ', $optionValue))) . '</option>';
            }
            $inputHtml .= '</select>';
            break;

        case 'checkbox':
            // Generate checkbox input
            $checked = ($value) ? ' checked' : '';
            $inputHtml = '<input type="checkbox" class="form-check-input me-1" id="' . htmlspecialchars($id) . '" name="' . htmlspecialchars($name) . '" value="1"' . $checked . '>';
            break;

        case 'tags':
            // Generate tags input (using Choices.js or similar)
            $inputHtml = '<input class="form-control" id="' . htmlspecialchars($id) . '" name="' . htmlspecialchars($name) . '" value="' . htmlspecialchars($value) . '" placeholder="' . htmlspecialchars($placeholder) . '" type="text" data-choices data-choices-removeItem';
            break;

        default:
            // Handle other input types or invalid ones
            $inputHtml = '<input type="' . htmlspecialchars($type) . '" class="form-control" id="' . htmlspecialchars($id) . '" name="' . htmlspecialchars($name) . '" placeholder="' . htmlspecialchars($placeholder) . '" value="' . htmlspecialchars($value) . '">';
            break;
    }

    // Return the full HTML
    return $type === 'checkbox' ? $inputHtml . $labelHtml : $labelHtml . $inputHtml;
}

function csrfInput(): void
{
    echo '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">';
}

function generateShortDescription($content, $length = 200): array|string|null
{
    // Decode HTML entities to convert them to normal characters
    $decodedContent = html_entity_decode($content, ENT_QUOTES | ENT_HTML5);

    // Strip HTML tags
    $plainText = strip_tags($decodedContent);

    // Replace multiple spaces with a single space
    $cleanedContent = preg_replace('/\s+/', ' ', $plainText);

    // Trim content to the specified length and append ellipsis if necessary
    if (strlen($cleanedContent) > $length) {
        return substr($cleanedContent, 0, $length) . '...';
    }

    return $cleanedContent;
}

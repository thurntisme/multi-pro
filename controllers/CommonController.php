<?php

class CommonController
{

    public function __construct()
    {
        global $pdo;
    }

    public function getToken()
    {
        return $_SESSION['token'] ?? "";
    }

    public function removeToken()
    {
        unset($_SESSION['token']);
    }

    public function getDeviceDetails()
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $deviceName = 'Unknown Device';
        $deviceType = 'Unknown Type';

        if (stripos($userAgent, 'iPhone') !== false) {
            $deviceName = 'iPhone';
            $deviceType = 'Mobile';
        } elseif (stripos($userAgent, 'iPad') !== false) {
            $deviceName = 'iPad';
            $deviceType = 'Tablet';
        } elseif (stripos($userAgent, 'Android') !== false) {
            if (preg_match('/Android.*; (.+) Build/', $userAgent, $matches)) {
                $deviceName = $matches[1];
            } else {
                $deviceName = 'Android Device';
            }
            $deviceType = 'Mobile';
        } elseif (stripos($userAgent, 'Windows NT') !== false) {
            $deviceName = 'Windows PC';
            $deviceType = 'Desktop';
        } elseif (stripos($userAgent, 'Macintosh') !== false) {
            $deviceName = 'Macintosh';
            $deviceType = 'Desktop';
        } elseif (stripos($userAgent, 'Linux') !== false) {
            $deviceName = 'Linux PC';
            $deviceType = 'Desktop';
        }

        return [
            'device_name' => $deviceName,
            'device_type' => $deviceType
        ];
    }

    function getCurrencySymbol($currencyCode)
    {
        return DEFAULT_CURRENCY[$currencyCode] ?? $currencyCode;
    }

    function paginateResources(array $resources): array
    {
        // Get values from $_GET with defaults
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 10;
        $keyword = $_GET['s'] ?? '';
        $selectedTag = $_GET['tag'] ?? '';

        // Extract unique tags
        $allTags = array_map(function ($resource) {
            return $resource['tags'] ?? '';
        }, $resources);
        $uniqueTags = array_unique(array_merge(...$allTags));

        // Filter resources by keyword if provided
        if (!empty($keyword)) {
            $resources = array_filter($resources, function ($resource) use ($keyword) {
                return stripos($resource['name'], $keyword) !== false ||
                    stripos($resource['description'], $keyword) !== false ||
                    in_array(strtolower($keyword), array_map('strtolower', $resource['tags']));
            });
        }

        // Filter resources by selected tag if provided
        if (!empty($selectedTag)) {
            $resources = array_filter($resources, function ($resource) use ($selectedTag) {
                return in_array($selectedTag, $resource['tags']);
            });
        }

        // Calculate total pages and offset
        $totalItems = count($resources);
        $totalPages = ceil($totalItems / $perPage);
        $offset = ($page - 1) * $perPage;

        // Get the current page items
        $pagedResources = array_slice($resources, $offset, $perPage) ?? [];

        return [
            'current_page' => $page,
            'per_page' => $perPage,
            'total_items' => $totalItems,
            'total_pages' => $totalPages,
            'resources' => $pagedResources,
            'tags' => $uniqueTags
        ];
    }

    function convertResources(array $resources, $perPage = 10): array
    {
        // Get values from $_GET with defaults
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $keyword = $_GET['s'] ?? '';
        $sortBy = $_GET['sort_by'] ?? '';
        $sortOrder = $_GET['sort_order'] ?? 'asc';

        // Filter resources by keyword if provided
        if (!empty($keyword)) {
            $resources = array_filter($resources, function ($resource) use ($keyword) {
                return stripos($resource['name'], $keyword) !== false;
            });
        }

        if (!empty($sortBy)) {
            usort($resources, function ($a, $b) use ($sortBy, $sortOrder) {
                $valueA = $a[$sortBy] ?? null;
                $valueB = $b[$sortBy] ?? null;

                if ($valueA == $valueB) {
                    return 0;
                }

                $comparison = $valueA < $valueB ? -1 : 1;
                return $sortOrder === 'desc' ? -$comparison : $comparison;
            });
        }

        // Calculate total pages and offset
        $totalItems = count($resources);
        $totalPages = ceil($totalItems / $perPage);
        $offset = ($page - 1) * $perPage;

        // Get the current page items
        $pagedResources = array_slice($resources, $offset, $perPage) ?? [];

        return [
            'current_page' => $page,
            'per_page' => $perPage,
            'total_items' => $totalItems,
            'total_pages' => $totalPages,
            'resources' => $pagedResources,
        ];
    }
}

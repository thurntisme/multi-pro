<?php

namespace App\Helpers;

class NetworkHelper
{
    public static function extractPathFromCurrentUrl(): string
    {
        // Build the current URL from the server variables
        $currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") .
            "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        // Parse the URL to get the path
        $parsedUrl = parse_url($currentUrl, PHP_URL_PATH);

        // Remove the leading slash
        return ltrim($parsedUrl, '/');
    }

    public static function home_url($path = ''): string
    {
        // Get the scheme (http or https)
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';

        // Get the host (e.g., example.com)
        $host = $_SERVER['HTTP_HOST'];

        // Get the base URL
        $base_url = $scheme . '://' . $host;

        // Append the path if provided
        if ($path) {
            $base_url .= '/' . ltrim($path, '/');
        }

        return $base_url;
    }

    public static function getRequestMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    private static function getFirstParamInUrl()
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

    public static function activeClassName($url, $className = 'active', $isParent = false)
    {
        $currentUrl = self::getFirstParamInUrl();
        return $currentUrl === $url ? $className : '';
    }

    public static function getCurrentUrl()
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

    public static function generatePageUrl($params = [])
    {
        // Get the current URL
        $currentUrl = self::getCurrentUrl();

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
        $newUrl = self::home_url($urlParts['path'] . (($newQuery != "") ? ('?' . $newQuery) : ''));

        return $newUrl;
    }
}
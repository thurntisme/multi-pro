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
}
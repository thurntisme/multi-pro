<?php
namespace App\Helpers;

use App\Core\Response;
use App\Middleware\CsrfMiddleware;

class Common
{
    public static function inputCsrfField(): string
    {
        $csrfMiddleware = new CsrfMiddleware(new Response());
        $token = $csrfMiddleware->token();
        return "<input type='hidden' name='{$csrfMiddleware->tokenKey}' value='{$token}'>";
    }

    public static function homeUrl(string $path = ''): string
    {
        return BASE_PATH . "/" . ($path ?? '');
    }

    public static function isCurrentPage(string $path): bool
    {
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return rtrim($currentPath, '/') === rtrim($path, '/');
    }
}
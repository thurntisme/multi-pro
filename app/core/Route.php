<?php
declare(strict_types=1);

namespace App\Core;

use App\Helpers\NetworkHelper;

class Route
{
    private string $url;
    private string $token;

    public function __construct()
    {
        $this->url = NetworkHelper::extractPathFromCurrentUrl();
    }

    public function register()
    {
        $this->renderPage($this->url);
    }

    public function renderPage(string $url)
    {
        if ($url === '') {
            include_once VIEWS_PATH . '/landing.php';
            exit;
        }
        if ($url === 'resources') {
            include_once VIEWS_PATH . '/resources.php';
            exit;
        }
        if (!empty($url) && str_starts_with($url, 'api')) {
            include_once 'api/index.php';
            exit;
        }
        die("404 Page not found");
    }
}
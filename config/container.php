<?php

use App\Core\Container;
use App\Core\MVCTemplateViewer;
use App\Core\TemplateViewerInterface;

$container = new Container();

$container->set(\App\Core\Database::class, function () {
    return new \App\Core\Database($_ENV["DB_DRIVER"], $_ENV["DB_HOST"], $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);
});

$container->set(TemplateViewerInterface::class, function () {
    return new MVCTemplateViewer();
});

return $container;
<?php

use App\Core\Container;
use App\Core\MVCTemplateViewer;
use App\Core\TemplateViewerInterface;

$container = new Container();

$container->set(TemplateViewerInterface::class, function () {
    return new MVCTemplateViewer();
});

return $container;
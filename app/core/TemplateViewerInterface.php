<?php
declare(strict_types=1);

namespace App\Core;

interface TemplateViewerInterface
{
    public function render(string $template, array $data = []): string;
}
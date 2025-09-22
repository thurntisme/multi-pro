<?php

namespace App\Core;

interface MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $next): Response;
}
<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class WebsiteController extends Controller
{
    public function index(): Response
    {
        $data = [
            'title' => 'Website'
        ];
        return $this->view('website', $data);
    }
}
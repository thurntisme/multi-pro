<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class DatabaseController extends Controller
{
    public function index(): Response
    {
        $data = [
            'title' => 'Database'
        ];
        return $this->view('database', $data);
    }
}
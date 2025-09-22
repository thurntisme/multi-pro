<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class TodoController extends Controller
{
    public function index(): Response
    {
        $data = [
            'title' => 'Todo'
        ];
        return $this->view('todo', $data);
    }
}
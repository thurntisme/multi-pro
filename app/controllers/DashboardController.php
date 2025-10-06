<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $data = [
            'title' => 'Dashboard'
        ];
        return $this->view('dashboard', $data);
    }
}
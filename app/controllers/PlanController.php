<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class PlanController extends Controller
{
    public function index(): Response
    {
        $data = [
            'title' => 'Plan'
        ];
        return $this->view('plan', $data);
    }
}
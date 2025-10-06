<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class CalendarController extends Controller
{
    public function index(): Response
    {
        $data = [
            'title' => 'Calendar'
        ];
        return $this->view('calendar', $data);
    }
}
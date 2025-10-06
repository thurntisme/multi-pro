<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class WorkoutController extends Controller
{
    public function index(): Response
    {
        $data = [
            'title' => 'Workout'
        ];
        return $this->view('workout', $data);
    }
}
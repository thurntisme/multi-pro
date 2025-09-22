<?php

namespace App\Controllers;

use App\Core\Controller;

class LandingController extends Controller
{
    public function index()
    {
        // Render the landing page
        $this->view('landing');
    }
}
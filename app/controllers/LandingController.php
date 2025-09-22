<?php

namespace App\Controllers;

use App\Core\Controller;

class LandingController extends Controller
{
    public function index()
    {
        // Render the landing page
        return $this->view('landing', ['title' => 'Welcome to My Website']);
    }
}
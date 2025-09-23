<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class ProfileController extends Controller
{
    public function index(): Response
    {
        $data = [
            'title' => 'Profile'
        ];
        return $this->view('profile', $data);
    }
}
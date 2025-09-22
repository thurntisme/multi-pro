<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class AuthController extends Controller
{
    public function renderLogin(): Response
    {
        return $this->view('auth/login', ['title' => 'Login']);
    }

    public function renderRegister()
    {
        return $this->view('auth/register', ['title' => 'Register']);
    }
}
<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class SettingController extends Controller
{
    public function index(): Response
    {
        $data = [
            'title' => 'Setting'
        ];
        return $this->view('setting', $data);
    }
}
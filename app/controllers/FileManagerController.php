<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class FileManagerController extends Controller
{
    public function index(): Response
    {
        $data = [
            'title' => 'File Manager'
        ];
        return $this->view('file-manager', $data);
    }
}
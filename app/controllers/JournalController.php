<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class JournalController extends Controller
{
    public function index(): Response
    {
        $data = [
            'title' => 'Journal'
        ];
        return $this->view('journal', $data);
    }
}
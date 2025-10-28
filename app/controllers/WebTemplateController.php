<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class WebTemplateController extends Controller
{
    public function index(): Response
    {
        $data = [
            'title' => 'Web Template'
        ];
        return $this->view('web-template', $data);
    }

    public function detail(): Response
    {
        $data = [
            'title' => 'Web Template Detail'
        ];
        return $this->view('web-template-detail', $data);
    }
}

<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class AIController extends Controller
{
    public function tools(): Response
    {
        $data = [
            'title' => 'AI Tools'
        ];
        return $this->view('ai-tools', $data);
    }

    public function prompt(): Response
    {
        $data = [
            'title' => 'AI Prompt'
        ];
        return $this->view('ai-prompt', $data);
    }
}

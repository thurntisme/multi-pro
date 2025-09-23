<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class NotificationsController extends Controller
{
    public function index(): Response
    {
        $data = [
            'title' => 'Notifications'
        ];
        return $this->view('notification', $data);
    }
}
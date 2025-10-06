<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class ClientController extends Controller
{
  public function index(): Response
  {
    $data = [
      'title' => 'Client'
    ];
    return $this->view('client', $data);
  }
}
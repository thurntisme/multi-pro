<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class EstimationController extends Controller
{
  public function index(): Response
  {
    $data = [
      'title' => 'Estimation'
    ];
    return $this->view('estimation', $data);
  }
}
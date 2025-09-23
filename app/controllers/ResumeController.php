<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class ResumeController extends Controller
{
  public function index(): Response
  {
    $data = [
      'title' => 'Resume'
    ];
    return $this->view('resume', $data);
  }
}
<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class ReportController extends Controller
{
  public function index(): Response
  {
    $data = [
      'title' => 'Report'
    ];
    return $this->view('report', $data);
  }
}
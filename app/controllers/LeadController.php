<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class LeadController extends Controller
{
  public function index(): Response
  {
    $data = [
      'title' => 'Lead'
    ];
    return $this->view('lead', $data);
  }
}
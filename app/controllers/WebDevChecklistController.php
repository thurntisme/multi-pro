<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class WebDevChecklistController extends Controller
{
  public function index(): Response
  {
    $data = [
      'title' => 'Web Dev Checklist'
    ];
    return $this->view('web-dev-checklist', $data);
  }
}
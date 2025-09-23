<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class WebSecureChecklistController extends Controller
{
  public function index(): Response
  {
    $data = [
      'title' => 'Web Secure Checklist'
    ];
    return $this->view('web-secure-checklist', $data);
  }
}
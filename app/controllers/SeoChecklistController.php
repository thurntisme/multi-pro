<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class SeoChecklistController extends Controller
{
  public function index(): Response
  {
    $data = [
      'title' => 'Seo Checklist'
    ];
    return $this->view('seo-checklist', $data);
  }
}
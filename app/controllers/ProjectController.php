<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class ProjectController extends Controller
{
  public function index(): Response
  {
    $data = [
      'title' => 'Project'
    ];
    return $this->view('project', $data);
  }
}
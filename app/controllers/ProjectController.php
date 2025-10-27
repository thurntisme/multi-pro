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

  public function detail(): Response
  {
    $data = [
      'title' => 'Project Detail'
    ];
    return $this->view('project-detail', $data);
  }

  public function new(): Response
  {
    $data = [
      'title' => 'New Project'
    ];
    return $this->view('project-adjust', $data);
  }

  public function edit(): Response
  {
    $data = [
      'title' => 'Edit Project'
    ];
    return $this->view('project-adjust', $data);
  }

  public function estimate(): Response
  {
    $data = [
      'title' => 'Project Estimate'
    ];
    return $this->view('project-estimate', $data);
  }

  public function question(): Response
  {
    $data = [
      'title' => 'Project Question'
    ];
    return $this->view('project-question', $data);
  }
}

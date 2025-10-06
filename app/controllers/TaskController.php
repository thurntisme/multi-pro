<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class TaskController extends Controller
{
  public function index(): Response
  {
    $data = [
      'title' => 'Task'
    ];
    return $this->view('task', $data);
  }
}
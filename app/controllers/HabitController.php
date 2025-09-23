<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class HabitController extends Controller
{
  public function index(): Response
  {
    $data = [
      'title' => 'Habit'
    ];
    return $this->view('habit', $data);
  }
}
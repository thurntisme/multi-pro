<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class NoteController extends Controller
{
  public function index(): Response
  {
    $data = [
      'title' => 'Note'
    ];
    return $this->view('note', $data);
  }
}
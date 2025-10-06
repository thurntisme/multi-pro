<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class MaintainWebController extends Controller
{
  public function index(): Response
  {
    $data = [
      'title' => 'Maintain Web'
    ];
    return $this->view('maintain-web', $data);
  }
}
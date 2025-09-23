<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class PackageController extends Controller
{
  public function index(): Response
  {
    $data = [
      'title' => 'Package'
    ];
    return $this->view('package', $data);
  }
}
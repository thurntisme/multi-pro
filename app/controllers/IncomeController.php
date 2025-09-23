<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class IncomeController extends Controller
{
  public function index(): Response
  {
    $data = [
      'title' => 'Income'
    ];
    return $this->view('income', $data);
  }
}
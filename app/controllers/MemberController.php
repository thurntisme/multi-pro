<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class MemberController extends Controller
{
  public function index(): Response
  {
    $data = [
      'title' => 'Member'
    ];
    return $this->view('member', $data);
  }
}

<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class SubscriptionController extends Controller
{
  public function index(): Response
  {
    $data = [
      'title' => 'Subscription'
    ];
    return $this->view('subscription', $data);
  }
}
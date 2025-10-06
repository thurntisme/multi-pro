<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class ExpenseController extends Controller
{
    public function index(): Response
    {
        $data = [
            'title' => 'Expense'
        ];
        return $this->view('expense', $data);
    }
}
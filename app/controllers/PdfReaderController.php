<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class PdfReaderController extends Controller
{
    public function index(): Response
    {
        $data = [
            'title' => 'Pdf Reader'
        ];
        return $this->view('pdf-reader', $data);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Table',
        ];

        // Perbaiki baris ini
        return view('table', $data);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecAnalystController extends Controller
{
    public function index()
    {
        $readme = file_get_contents(base_path('vendor/recanalyst/recanalyst/README.md'));
        return view('markdown', [
            'title' => 'RecAnalyst',
            'source' => $readme,
        ]);
    }
}

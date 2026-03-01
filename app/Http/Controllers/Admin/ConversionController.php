<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ConversionController extends Controller
{
    public function index()
    {
        return view('admin.list', ['title' => 'Unit conversions']);
    }
}

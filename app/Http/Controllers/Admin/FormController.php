<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class FormController extends Controller
{
    public function index()
    {
        return view('admin.list', ['title' => 'Forms']);
    }
}

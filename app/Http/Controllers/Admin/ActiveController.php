<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ActiveController extends Controller
{
    public function index()
    {
        return view('admin.list', ['title' => 'Actives']);
    }
}

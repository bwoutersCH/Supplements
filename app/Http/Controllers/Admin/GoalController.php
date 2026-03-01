<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class GoalController extends Controller
{
    public function index()
    {
        return view('admin.list', ['title' => 'Goals']);
    }
}

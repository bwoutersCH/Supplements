<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class SponsoredEntryController extends Controller
{
    public function index()
    {
        return view('admin.list', ['title' => 'Sponsored entries']);
    }
}

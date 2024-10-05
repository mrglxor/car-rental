<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function renting()
    {
        return view('admin.renting');
    }
    public function rentingOut()
    {
        return view('admin.renting-out');
    }
}

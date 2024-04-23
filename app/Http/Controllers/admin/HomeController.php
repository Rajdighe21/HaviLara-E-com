<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\Guard;

class HomeController extends Controller
{
    public function index()
    {
        // $admin = Auth::guard('admin')->user();
        // echo "Test " . $admin->name . ' <a href="' . route('admin.logout') . '">Logout</a>';


        return view('admin.dashboard');
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}


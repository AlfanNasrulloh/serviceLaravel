<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        // $customers = User::where('role', 'customer')->get();
        $customers = User::all();

        return view('customers.index', compact('customers'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if ($request->user()->role === 'credential_manager' || $request->user()->role === 'admin') {
            return redirect()->route('credentials');
        }

        if ($request->user()->role === 'vote_manager') {
            return redirect()->route('amendments');
        }

        return redirect()->route('vote');
    }
}

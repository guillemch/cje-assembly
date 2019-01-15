<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AmendmentsController extends Controller
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
     * List all amendments
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $request->user()->authorizeRoles('vote_manager');

        return view('amendments.index');
    }
}

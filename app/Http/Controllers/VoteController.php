<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Amendment;
use App\Vote;

class VoteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'jwt']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('vote.index');
    }

    /**
     * Get the currently open vote
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function current()
    {
        $current = Amendment::current()->get();

        return response()->json($current);
    }
}

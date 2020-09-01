<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SecretVote;

class SecretVoteController extends Controller
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
     * Show the secret vote app
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if ($request->user()->credentials_pickedup_at === null) {
            abort(403, 'Tienes que acreditarte para poder votar');
        }

        $secretVotes = SecretVote::all();

        return view('secret_vote.index', compact('secretVotes'));
    }
}

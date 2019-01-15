<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Amendment;
use App\Vote;
use App\Http\Requests\VoteRequest;

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
     * @return Response
     */
    public function current(Request $request)
    {
        $current = Amendment::current()->get();

        $current->load(['votes' => function ($query) use ($request) {
            $query->where('user_id', $request->user()->id);
            $query->limit(1);
        }]);

        return response()->json($current);
    }

    /**
     * Submit a vote
     *
     * @return Response
     */
    public function submit(VoteRequest $request)
    {
        $vote = new Vote();
        $vote->amendment_id = $request->input('amendment_id');
        $vote->user_id = $request->user()->id;
        $vote->vote_for = $request->input('selected');
        
        $submitted = $vote->save();

        return response()->json([
            'submitted' => $submitted
        ]);
    }
}

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
     * Show the vote app
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if ($request->user()->credentials_pickedup_at === null) {
            abort(403, 'Tienes que acreditarte para poder votar');
        }

        return view('vote.index');
    }

    /**
     * Show a user's votes
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function archive(Request $request)
    {
        $votes = $request->user()->votes()->with('amendment')->orderBy('id', 'desc')->get();

        return view('vote.archive', compact('votes'));
    }

    /**
     * Get the currently open vote
     *
     * @return Response
     */
    public function current(Request $request)
    {
        $current = Amendment::current()->first();

        if (!$current) return response()->json(['no_open_vote' => true]);

        if ($request->input('with_results')) {
            $request->user()->authorizeRoles('vote_manager');

            $current->results = Amendment::results($current->id);

            return response()->json($current);
        }

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

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
    public function archive()
    {
        return view('vote.archive');
    }

    /**
     * Show a user's votes
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function myVotes(Request $request)
    {
        $votes = $request->user()->votes()->selectRaw('vote_for, amendment_id, created_at, COUNT(id) as votes')
            ->groupBy('vote_for')
            ->groupBy('amendment_id')
            ->groupBy('created_at')
            ->with('amendment')
            ->orderBy('created_at', 'desc')->get();

        return response()->json($votes);
    }

    /**
     * Get the currently open votes
     *
     * @return Response
     */
    public function open_votes(Request $request)
    {
        $current = Amendment::open()->get();

        if (!$current) return response()->json(['no_open_vote' => true]);

        $current->load(['votes' => function ($query) use ($request) {
            $query->where('user_id', $request->user()->id);
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
        $amendments = $request->input('selected');

        // For each amendment
        foreach($amendments as $amendmentId => $selection) {
            // For each option in amendment
            foreach($selection as $option => $votes) {
                // If 0 votes, skip
                if ($votes < 1) continue;

                //For each vote in option
                for($i = 1; $i <= $votes; $i++) {
                    $vote = new Vote();
                    $vote->amendment_id = $amendmentId;
                    $vote->user_id = $request->user()->id;
                    $vote->vote_for = str_replace('option_', '', $option);
                    $saved = $vote->save();
                }
            }
        }

        return response()->json([
            'submitted' => true
        ]);
    }
}

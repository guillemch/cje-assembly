<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\SecretVote;
use App\SecretVoteBallot;
use App\SecretVoteRoll;
use App\Http\Requests\SecretVoteRequest;

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

        return view('secret_vote.index');
    }

    /**
     * List vites with options and voter roll
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function list(Request $request)
    {
        if ($request->user()->credentials_pickedup_at === null) {
            abort(403, 'Tienes que acreditarte para poder votar');
        }

        $userId = $request->user()->id;
        $secretVotes = SecretVote::with([
            'roll' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            },
            'options'
        ])->get();

        return response()->json($secretVotes);
    }

    /**
     * Submit a vote
     *
     * @return Response
     */
    public function submit(SecretVoteRequest $request)
    {
        $ballot = $request->input('selected');

        // For each secret vote
        foreach($ballot as $secretVoteId => $selection) {
            // For each option in selection
            $voteCounter = 0;
            foreach($selection as $option => $votes) {
                // If 0 votes, skip
                if ($votes < 1) continue;

                // For each vote in option
                for($i = 1; $i <= $votes; $i++) {
                    $secretVote = new SecretVoteBallot();
                    $secretVote->secret_vote_option_id = $secretVoteId;
                    $secretVote->save();
                    $voteCounter++;
                }
            }

            // Mark user has having voted in this election
            if ($voteCounter > 0) {
                $voterRoll = new SecretVoteRoll();
                $voterRoll->user_id = $request->user()->id;
                $voterRoll->secret_vote_id = $secretVoteId;
                $voterRoll->votes = $voteCounter;
                $voterRoll->save();
            }
        }

        return response()->json([
            'submitted' => true
        ]);
    }
}

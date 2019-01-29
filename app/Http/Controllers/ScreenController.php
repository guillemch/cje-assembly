<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google2FA;
use App\Amendment;
use Carbon\Carbon;

class ScreenController extends Controller
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
    public function index(Request $request)
    {
        $request->user()->authorizeRoles('admin');

        return view('screen.index');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function screen(Request $request)
    {
        $request->user()->authorizeRoles('admin');

        /* Time-based password */
        $secret = config('google2fa.secret');
        $code = Google2FA::getCurrentOtp($secret);
        $timestamp = Google2FA::getTimestamp();
        $now = Carbon::now();
        $nextCode = Carbon::createFromTimestamp(($timestamp * 30) + 30);

        $nextAlert = ($nextCode->diffInSeconds($now) <= 5);

        /* Vote */
        $justClosed = false;
        $vote = Amendment::current()->first();

        if (!$vote) {
            // Retreive last vote if closed within 30 seconds
            $vote = Amendment::orderBy('closed_at', 'desc')->first();
            $voteClosedAt = Carbon::parse($vote->closed_at);

            if ($voteClosedAt->diffInSeconds($now) > 30) {
                $vote = null;
            } else {
                $justClosed = true;
            }
        }

        return response()->json([
            'vote' => $vote,
            'code' => $code,
            'next_alert' => $nextAlert,
            'just_closed' => $justClosed
        ]);
    }
}

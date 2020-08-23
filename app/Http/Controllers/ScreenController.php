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
        $google2fa = app('pragmarx.google2fa');
        $google2fa->setOneTimePasswordLength(4);
        $google2fa->setKeyRegeneration(30);
        $code = $google2fa->getCurrentOtp($secret);
        $timestamp = Google2FA::getTimestamp();
        $now = Carbon::now();
        $nextCode = Carbon::createFromTimestamp(($timestamp * 30) + 30);
        $nextAlert = ($nextCode->diffInSeconds($now) <= 5);

        /* Vote */
        $justClosed = false;
        $votes = Amendment::open()->get();

        if (!$votes) {
            // Retreive last votes if closed within 30 seconds
            $votes = Amendment::orderBy('closed_at', 'desc')->get();
            $votesClosedAt = Carbon::parse($votes[0]->closed_at);

            if ($votes[0]->closed_at) {
                if ($votesClosedAt->diffInSeconds($now) > 30) {
                    $vote = null;
                } else {
                    $justClosed = true;
                }
            } else {
                $votes = null;
            }
        }

        return response()->json([
            'votes' => $votes,
            'code' => $code,
            'next_alert' => $nextAlert,
            'just_closed' => $justClosed
        ]);
    }
}

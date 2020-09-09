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
        $google2fa->setKeyRegeneration(45);
        $code = $google2fa->getCurrentOtp($secret);
        $timestamp = Google2FA::getTimestamp();
        $now = Carbon::now();
        $nextCode = Carbon::createFromTimestamp(($timestamp * 45) + 45);
        $nextAlert = ($nextCode->diffInSeconds($now) <= 5);
        $willHideIn = null;

        /* Vote */
        $justClosed = false;
        $votes = Amendment::open()->get();

        if (!$votes->count()) {
            // Get last close time
            $lastCloseTime = Amendment::orderBy('closed_at', 'desc')->first();
            if ($lastCloseTime->closed_at) {
                // Get all amendments closed at the same time
                $lastAmendments = Amendment::where('closed_at', $lastCloseTime->closed_at)->orderBy('id', 'asc')->get();

                // Allocate 10 seconds per amendment or 30 if only one
                $votesClosedAt = Carbon::parse($lastCloseTime->closed_at);
                $seconds = ($lastAmendments->count() === 1) ? 35 : 10 * ($lastAmendments->count() + 1);

                if ($votesClosedAt->diffInSeconds($now) < $seconds) {
                    $votes = $lastAmendments;
                    $justClosed = true;
                    $willHideIn = $seconds * 1000;
                }
            }
        }

        return response()->json([
            'votes' => $votes,
            'code' => $code,
            'next_alert' => $nextAlert,
            'just_closed' => $justClosed,
            'will_hide_in' => $willHideIn
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google2FA;
use App\Amendment;

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

        /* Vote */
        $vote = Amendment::current()->first();

        return response()->json([
            'vote' => $vote,
            'code' => $code
        ]);
    }
}

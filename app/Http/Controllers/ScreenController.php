<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google2FA;

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

        $secret = config('google2fa.secret');

        $code = Google2FA::getCurrentOtp($secret);

        return view('screen', compact('code'));
    }
}

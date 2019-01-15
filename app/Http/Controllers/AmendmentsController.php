<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Amendment;

class AmendmentsController extends Controller
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
     * List all amendments
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $request->user()->authorizeRoles('vote_manager');

        return view('amendments.index');
    }

    /**
     * List all amendments
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function list(Request $request)
    {
        $request->user()->authorizeRoles('vote_manager');
        
        $amendments = Amendment::all();

        return response()->json($amendments);
    }
}

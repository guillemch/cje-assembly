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
     * @return Response
     */
    public function list(Request $request)
    {
        $request->user()->authorizeRoles('vote_manager');
        
        $amendments = Amendment::all();

        return response()->json($amendments);
    }

    /**
     * Create new amendment
     *
     * @return Response
     */
    public function new(Request $request)
    {
        $request->user()->authorizeRoles('vote_manager');

        $request->validate([
            'name' => 'required|max:255',
            'option_1' => 'required|max:40',
            'option_2' => 'required|max:40',
            'option_3' => 'max:40',
            'option_4' => 'max:40',
            'option_5' => 'max:40',
        ]);

        $amendment = new Amendment;
        $amendment->name = $request->input('name');
        $amendment->option_1 = $request->input('option_1');
        $amendment->option_2 = $request->input('option_2');
        if ($request->input('option_3_active')) {
            $amendment->option_3 = $request->input('option_3');
        }
        if ($request->input('option_4_active')) {
            $amendment->option_4 = $request->input('option_4');
        }
        if ($request->input('option_5_active')) {
            $amendment->option_5 = $request->input('option_5');
        }

        $amendment->save();

        if ($request->input('open')) {
            $amendment->openVote();
        }

        return response()->json(['success' => true]);
    }

    /**
     * Open a vote
     *
     * @return Response
     */
    public function open(Amendment $amendment, Request $request)
    {
        $request->user()->authorizeRoles('vote_manager');

        $amendment->openVote();

        return response()->json(['success' => true]);
    }
}

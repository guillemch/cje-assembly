<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Amendment;
use App\User;
use App\Group;

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

        $groups = Group::all();

        return view('amendments.index', compact('groups'));
    }

    /**
     * List all amendments
     *
     * @return Response
     */
    public function list(Request $request)
    {
        $request->user()->authorizeRoles('vote_manager');
        
        $amendments = Amendment::whereNull('joint_with')->with('joint_amendments')->get();

        return response()->json($amendments);
    }

    /**
     * Retreive an amendment
     *
     * @return Response
     */
    public function getAmendment(Amendment $amendment, Request $request)
    {
        $request->user()->authorizeRoles('vote_manager');

        $amendment->load(['votes' => function ($query) {
            $query->select('votes.id', 'votes.amendment_id', 'votes.vote_for', 'votes.created_at', 'users.name', 'users.last_name', 'users.type', 'groups.name AS group_name', 'groups.acronym AS acronym');
            $query->join('users', 'users.id', '=', 'votes.user_id');
            $query->join('groups', 'groups.id', '=', 'users.group_id');
            $query->orderBy('users.type', 'asc');
            $query->orderBy('groups.acronym', 'asc');
            $query->orderBy('users.last_name', 'asc');
        }]);

        return response()->json($amendment);
    }

    /**
     * Retreive an amendment
     *
     * @return Response
     */
    public function getAmendmentSummary(Amendment $amendment, Request $request)
    {
        $amendment->load(['votes' => function ($query) use ($request) {
            $query->where('user_id', $request->user()->id);
            $query->limit(1);
        }]);

        return response()->json($amendment);
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

    /**
     * Close a vote
     *
     * @return Response
     */
    public function close(Amendment $amendment, Request $request)
    {
        $request->user()->authorizeRoles('vote_manager');

        $amendment->closeVote();

        return response()->json(['success' => true]);
    }
}

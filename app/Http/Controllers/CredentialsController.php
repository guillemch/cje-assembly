<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class CredentialsController extends Controller
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
     * List all users
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $request->user()->authorizeRoles('credential_manager');

        return view('credentials.index');
    }

    /**
     * Return all users
     *
     * @return Response
     */
    public function list(Request $request)
    {
        $request->user()->authorizeRoles('credential_manager');
        
        $users = User::all();

        return response()->json($users);
    }

}

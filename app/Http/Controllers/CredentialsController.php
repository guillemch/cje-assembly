<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Notifications\UserPickedupCredentials;
use Carbon\Carbon;

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

    /**
     * Check an attendee in
     *
     * @return Response
     */
    public function checkIn(Request $request)
    {
        $request->user()->authorizeRoles('credential_manager');

        $userId = $request->input('user_id');
        $undo = $request->input('undo');

        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'No se ha encontrado el usuario especificado', 'checkedin' => false], 422);
        }

        if ($undo) {
            $user->credentials_pickedup_at = null;
            $user->save();

            return response()->json(['user' => $user, 'checkedin' => false]);
        }

        if ($user->credentials_pickedup_at !== null) {
            $message = $user->name . ' ' . $user->last_name . ' ya se ha acreditado';
            return response()->json(['error' => $message, 'user' => $user], 422);
        }

        $newPassword = str_random(8);
        $user->credentials_pickedup_at = Carbon::now();
        $user->password = bcrypt($newPassword);
        $user->save();

        $user->notify(new UserPickedupCredentials($user, $newPassword));

        return response()->json(['user' => $user, 'checkedin' => true]);
    }

}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Handle actions after user is authenticated.
     */
    protected function authenticated(Request $request, $user)
    {
        $currentSessionId = Session::getId();

        // If user already has another active session, destroy it
        if ($user->session_id && $user->session_id !== $currentSessionId) {
            Session::getHandler()->destroy($user->session_id);
        }

        // Save current session ID to user
        $user->session_id = $currentSessionId;

        // Save last login time
        $user->last_login_at = now();

        $user->save();

        return redirect()->intended($this->redirectPath());
    }

    /**
     * Logout user and clear session_id.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $user->session_id = null;
            $user->save();
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

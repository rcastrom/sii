<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
        //$this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
    public function authenticated(Request $request, $user)
    {
        if ($user->hasRole('escolares')) {
            return redirect()->intended('/escolares');
        }
        if ($user->hasRole('alumno')) {
            return redirect()->intended('/estudiante');
        }
        if ($user->hasRole('personal')) {
            return redirect()->intended('/personal');
        }
        if ($user->hasRole('division')) {
            return redirect()->intended('/division');
        }
        // En proceso
        if ($user->hasRole('rechumanos')) {
            return redirect()->intended('/rechumanos');
        }
        // Faltan por crear
        if ($user->hasRole('verano')) {
            return redirect()->intended('/verano');
        }

    }
}

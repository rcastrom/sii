<?php
namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract{
    public function toResponse($request)
    {
        if (Auth::user()->hasRole('escolares')) {
            return redirect('/escolares');
        } elseif (Auth::user()->hasRole('division')) {
            return redirect('/division');
        } elseif (Auth::user()->hasRole('personal')) {
            return redirect('/personal');
        } elseif (Auth::user()->hasRole('rechumanos')) {
            return redirect('/rechumanos');
        } elseif (Auth::user()->hasRole('desacad')) {
            return redirect('/desarrollo');
        } elseif (Auth::user()->hasRole('academico')) {
            return redirect('/academicos');
        } elseif (Auth::user()->hasRole('alumno')) {
            return redirect('/alumnos');
        }else{
            return redirect('/');
        }
        /*return $request->wantsJson()
            ? response()->json(['two_factor' => false])
            : redirect()->intended(config('fortify.home'));
        */
    }

}

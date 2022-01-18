<?php
namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract{
    public function toResponse($request)
    {
        if(Auth::user()->hasRole('escolares')){
            return redirect('/escolares');
        }elseif(Auth::user()->hasRole('division')) {
            return redirect('/division');
        }else{
            return redirect('/');
        }
        /*return $request->wantsJson()
            ? response()->json(['two_factor' => false])
            : redirect()->intended(config('fortify.home'));
        */
    }

}

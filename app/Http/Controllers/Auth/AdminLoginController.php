<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\AdminAuthenticatesUsers;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class AdminLoginController extends Controller
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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function login()
    {
        return view('auth.admin_login');
    }

    public function postLogin(Request $request)
    {

        $rules = [
            'email' => 'required',
            'password' => 'required'
        ];
      $validator = \Validator::make($request->all(), $rules);
        if($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }
     $user = User::where([
            'email' => $request->get('email'),
        ])->first();

        if ($user == null) {
            return redirect()->back()->with('error', 'Invalid User');
        }
       if ($user->isAdmin() || $user->isSuperAdmin()) {
            if (\Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')])) {
                return redirect()->intended('/admin');
            }
        } else {
            $validator->getMessageBag()->add('email', 'You are not admin');
            return redirect()->back()->withInput()->with('error', 'You are not an admin')->withErrors($validator->errors());
        }

        return redirect()->back()->with('error', 'Invalid Credentials');
    }

}

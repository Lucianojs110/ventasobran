<?php

namespace SisVentaNew\Http\Controllers\Auth;

use SisVentaNew\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;
use SisVentaNew\Sucursal;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
        parent::__construct();
    }

    //overrride//
    public function showLoginForm()
    {
        $sucursal = Sucursal::where('estado','Activo')->get();
       
        return view('auth.login', compact(['sucursal']));
    }

    //overrride//
    protected function credentials(Request $request)
    {
        $credentials = $request->only($this->username(), 'password', 'sucursal');

        $id_sucursal = $credentials['sucursal'];

        session(['sucursal' => $id_sucursal]);

        return $request->only($this->username(), 'password');
    }
}

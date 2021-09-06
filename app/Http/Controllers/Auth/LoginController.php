<?php

namespace SisVentaNew\Http\Controllers\Auth;

use SisVentaNew\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use SisVentaNew\Sucursal;
use SisVentaNew\SucursalUser;
use SisVentaNew\User;
use Illuminate\Support\Facades\Redirect;
Use Session;

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


    public function login(Request $request)
    {
        
    
        $usuario=User::where('email',  $request->email)->first();

        $sucursalUser = SucursalUser::where('user_id', $usuario->id)->get();

        //dd($sucursalUser);

        $arraysucursal= array();

        foreach($sucursalUser as $sucUser){ 
                array_push($arraysucursal, $sucUser->sucursal_id);
        }

        if(!in_array($request->sucursal,  $arraysucursal)){
            
            $request->session()->regenerate();
            $this->clearLoginAttempts($request);

            Session::flash('message','No tiene permisos para ingresar a esta sucursal');
            return redirect()->route('login');
            
        }

    
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function permiso()
    {
    
       
        return view('auth.permiso');
    }
}

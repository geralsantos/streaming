<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function authenticate(Request $request)
    {
        $credentials = $request->only('usuario', 'contrasena');

        if (Auth::attempt($credentials)) {
            return redirect()->intended('/dashboard');
        }
    }
    protected function credentials(Request $request)
    {
        //$field = $this->field($request);

        return [
            'usuario' => $request->get($this->username()),
            'password' => $request->get('contrasena'),
        ];
    }
    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        //$field = $this->field($request);

        $messages = ["{$this->username()}.exists" => 'La cuenta no ha sido encontrado o está deshabilitada.'];

        $this->validate($request, [
            $this->username() => "required|exists:usuario",
            'contrasena' => 'required',
        ], $messages);
    }
    public function username()
    {
        return 'usuario';
    }
}

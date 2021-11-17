<?php

namespace App\Http\Controllers;

use App\Models\TrainerShift;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Http\Helpers\AllowedNationalities;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function registration()
    {
        return view('auth.register');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function postLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended('home.index')
                ->withSuccess('You have Successfully loggedin');
        }

        return redirect("login")->withSuccess('Oppes! You have entered invalid credentials');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function postRegistration(Request $request)
    {
        $allowedNationalities = new AllowedNationalities();
        $request->validate([
            'name' => 'required',
            'user_name' => 'required|unique:users,user_name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'gender' => 'required|in:male,female',
            'phone_number' => '',
            'address' => 'string',
            'nationality' => 'in:' . $allowedNationalities->toString()
        ]);

        $this->register($request->all());

        return redirect("login")->withSuccess('Great! You have Successfully loggedin');
    }


    /**
     * Write code on Method
     *
     * @return response()
     */
    public function register(array $data)
    {
        return User::create([
            'id' => Str::uuid(),
            'name' => $data['name'],
            'user_name' => $data['user_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'gender' => $data['gender'],
            'phone_number' => $data['phone_number'],
            'address' => $data['address'],
            'nationality' => $data['nationality'],
        ]);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function logout()
    {
        Session::flush();
        Auth::logout();

        return Redirect('login');
    }
}

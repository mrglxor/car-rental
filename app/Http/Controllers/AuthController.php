<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'register_email' => 'required|string|email|max:255|unique:users,email',
            'alamat' => 'required|string',
            'phone_number' => 'required|string',
            'sim_number' => 'required|string',
            'register_password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            $errorMessages = $validator->errors()->all();
            $errorString = implode(' ', $errorMessages);

            return redirect()->back()->with('error', $errorString)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->register_email,
            'password' => Hash::make($request->register_password),
            'address' => $request->alamat,
            'phone_number' => $request->phone_number,
            'sim_number' => $request->sim_number,
        ]);

        Auth::login($user);

        session()->flash('message', 'Registrasi berhasil! Anda sekarang bisa login.');
        return redirect()->route('auth');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login_email' => 'required|string|email',
            'login_password' => 'required|string',
        ]);

        $credentials = [
            'email' => $request->input('login_email'),
            'password' => $request->input('login_password'),
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->route('admin');
            } elseif ($user->role === 'staff') {
                return redirect()->route('staff');
            } else {
                return redirect()->route('customer');
            }
        }

        return redirect()->back()->with('error', 'Login gagal. Cek email dan password.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}

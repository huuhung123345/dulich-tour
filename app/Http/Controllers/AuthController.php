<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials + ['status' => 1])) {
            $request->session()->regenerate();
            return Auth::user()->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->intended(route('home'));
        }

        return back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng, hoặc tài khoản bị khóa.'])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:100',
            'email'     => 'required|email|max:100|unique:users,email',
            'phone'     => 'nullable|string|max:15',
            'address'   => 'nullable|string|max:255',
            'password'  => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'role_id'       => 3,
            'full_name'     => $data['full_name'],
            'email'         => $data['email'],
            'password_hash' => Hash::make($data['password']),
            'phone'         => $data['phone'] ?? null,
            'address'       => $data['address'] ?? null,
            'status'        => 1,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')->with('ok', 'Đăng ký thành công! Chào mừng bạn.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}

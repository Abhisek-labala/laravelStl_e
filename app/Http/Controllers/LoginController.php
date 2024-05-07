<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $username = $request->input("username");
        $password = $request->input("password");

        $user = DB::table('regforms')->where('username', $username)->first();

        if ($user) {
            if (password_verify($password, $user->password)) {
                Session::put('loggedin', true);
                Session::put('username', $username);
                $demopassword = "password"; // This should be hashed in production

                if (password_verify($demopassword, $user->password)) {
                    return redirect()->route('dashboard');
                } else {
                    return redirect()->route('dashboard');
                }
            } else {
                return redirect()->back()->with('error', 'Invalid credentials');
            }
        } else {
            return redirect()->back()->with('error', 'Invalid credentials');
        }
    }

    function updatePassword(Request $request){
        $username = $request->session()->get('username');

        if ($request->isMethod('post')) {
            $password = $request->input('password');
            $cpassword = $request->input('cpassword');

            if ($password == $cpassword) {
                // Hash the password before storing
                $phash = Hash::make($password);

                // Update password in the database
                $result = DB::table('regforms')
                    ->where('username', $username)
                    ->update(['password' => $phash]);

                if ($result) {
                    return redirect()->route('login', ['password_updated' => true, 'username' => $username]);
                } else {
                    return back()->with('error', 'Failed to update password.');
                }
            } else {
                return back()->with('error', 'Passwords do not match.');
            }
        }

        return view('password.update');

    }
}

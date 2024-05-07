<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;


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
                    return redirect()->route('update');
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
    public function logout(Request $request)
    {
        $request->session()->flush();
        // Redirect to login page
        return redirect('/')->with('success', 'You have been logged out.');
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
                        // Store information in session
                        Session::flash('password_updated', true);
                        Session::flash('username', $username);
                        return Response::json(['redirect_url' => url('/')]);
                    } else {
                        return Response::json(['error' => 'Failed to update password.'], 500);
                    }
            } else {
                return Response::json(['error' => 'Passwords do not match.'], 400);
            }
        }

        return view('update');
        }
}

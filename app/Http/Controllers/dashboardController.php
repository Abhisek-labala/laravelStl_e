<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegForm;

class dashboardController extends Controller
{
 public function dashboard(Request $request)
        {
            $username = $request->session()->get('username');

        $user = Regform::where('username', $username)->first();

        if (!$user) {
            // Redirect to error page if user not found
            return redirect()->route('error_page');
        }

        $img = $user->image_url;

        $num = Regform::count(); // Count all records

        $sessionTimeout = 5 * 60; // 5 minutes in seconds

        // Check if session variable last_activity exists
        if ($request->session()->has('last_activity') && (time() - $request->session()->get('last_activity') > $sessionTimeout)) {
            // Session expired, destroy session
            $request->session()->forget('last_activity');
            $request->session()->flush();

            // Redirect to error page
            return redirect()->route('error_page');
        }

        // Update last activity time
        $request->session()->put('last_activity', time());

        // Pass data to the view
        return view('dashboard', compact('user', 'img', 'num'));
    }
}
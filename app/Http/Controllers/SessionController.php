<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    /**
     * Show the login form.
     */
    public function create()
    {
        if (Auth::check()) {
            return redirect()->route('admin.rooms.index');
        }

        return view('pages.admin.login');
    }

    /**
     * Handle login submission.
     */
    public function store(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('admin.rooms.index');
        }

        return back()
            ->withErrors(['email' => 'The credentials are not valid.'])
            ->withInput();
    }

    /**
     * Handle logout.
     */
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

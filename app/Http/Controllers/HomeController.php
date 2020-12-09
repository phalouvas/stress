<?php

namespace App\Http\Controllers;

use App\Models\User as User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function showToken() {
        $token = User::first()->token;
        return response($token);
    }

    public function updateToken(Request $request) {
        $user = User::first();
        $user->token = $request->token;
        $user->save();
        return response('ok');
    }
}

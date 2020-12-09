<?php

namespace App\Http\Controllers;

use App\Jobs\Get;
use App\Jobs\Post;
use App\Models\User as User;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
        return auth()->user();
    }

    public function updateToken(Request $request) {
        $user = User::first();
        $user->token = $request->user['token'];
        $user->endpoint_webapp = $request->user['endpoint_webapp'];
        $user->endpoint_msms = $request->user['endpoint_msms'];
        $user->endpoint_msas = $request->user['endpoint_msas'];
        $user->save();
        return response('ok');
    }

    public function start(Request $request) {
        $token = $request->user()->token;
        $test = $request->except('q');
        Cache::put('rec_' . $test['id'], $test, now()->addDay());

        if ($request->type == 'get') {
            dispatch(new Get($token, $test['id']));
        } else {
            dispatch(new Post($token, $test['id']));
        }
        return response('ok');
    }

    public function stop(Request $request) {
        $test = $request->except('q');
        Cache::put('rec_' . $test['id'], $test, now()->addDay());
        return response('ok');
    }

    public function status(Request $request) {
        return Cache::get('rec_' . $request->id);
    }
}

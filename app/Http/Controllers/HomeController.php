<?php

namespace App\Http\Controllers;

use \DB;
use App\Models\Shift;
use Auth;
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
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $shift = DB::table('shifts')->whereNull('deleted_at')->count();
        $shift_requests = DB::table('shift_requests')->count();
        $pending = DB::table('shift_requests')->where('status', '=', 'pending')->count();
        $accepted =  DB::table('shift_requests')->where('status', '=', 'accepted')->count();
        $rejected = DB::table('shift_requests')->where('status', '=', 'rejected')->count();


        return view('home', compact('shift', 'shift_requests', 'pending', 'accepted', 'rejected'));
    }
    public function logout(Request $request)
    {

        Auth::logout();

        return redirect('login');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return view('home');
    }
    public function add_admin(array $data)
    {

        return view('add_admin');
    }
    public function store_admin(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone_number' => $data['phone_number'],
            'is_admin' => 1,
        ]);
    }

    public function update_profile()
    {

        $user = Auth::user();

        return view('update_profile', compact('user'));
    }

    public function update_profile_data(Request $request)
    {
        $user = Auth::user();

        if ($request->has('name')) {
            $user->name = $request->input('name');
        }

        if ($request->has('email')) {
            $user->email = $request->input('email');
        }

        if ($request->has('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class OpenShiftsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shifts = Shift::all();
        return view('shift.shift', compact('shifts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'location' => 'required|max:255',
            'staff_needed' => 'required|max:255',
        ]);

        $validatedData['start_date'] = date('Y-m-d H:i:s', strtotime($request->input('start_date')));

        $validatedData['end_date'] = date('Y-m-d H:i:s', strtotime($request->input('end_date')));

        $create = Shift::create($validatedData);

        return back()->with('success', 'Shift created successfully!');
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {

        $shift = Shift::findOrFail($id);
        return view('shift.edit_shift', compact('shift'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'location' => 'required|max:255',
        ]);

        $shift = Shift::findOrFail($id);
        $shift->update($request->all());

        return redirect('open-shifts')->with('success', 'Shift updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $shift = Shift::find($id);

        if ($shift) {
            $shift->delete();
            return redirect('open-shifts')->with('success', 'Shift Deleted successfully');
        } else {
            return redirect('open-shifts')->with('error', 'Shift not found');
        }
    }
    public function login_user(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $role_id = Auth::user()->role_id;

            if ($role_id == 1) {
                return redirect('/home');
            } else {
                return redirect('/show-calendar');
            }
        }

        return redirect("login")->withSuccess('Oops! You have entered invalid credentials');
    }
}

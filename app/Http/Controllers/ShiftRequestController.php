<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Shift;
use App\Models\ShiftAccepted;
use App\Models\ShiftRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;

class ShiftRequestController extends Controller
{
    public function index()
    {
        $shiftRequests = ShiftRequest::where('status', 'pending')->with(['user', 'shift'])->get();

        return view('shift-requests.index', compact('shiftRequests'));
    }
    public function accept(Request $request, $id)
    {




        $shiftRequest = ShiftRequest::findOrFail($id);
        $shiftRequest->status = 'accepted';
        $shiftRequest->save();

        // Create notification
        $notification = new Notification;
        $notification->user_id = $shiftRequest->user->id;
        $notification->message = 'Your shift request has been accepted for ' . $shiftRequest->shift->title . ' on ' . date('l, F jS', strtotime($shiftRequest->shift->start_date)) . ' from ' . date('h:i A', strtotime($shiftRequest->shift->start_date)) . ' to ' . date('h:i A', strtotime($shiftRequest->shift->end_date));
        $notification->save();

        // Send email
        $to = $shiftRequest->user->email;
        $subject = 'Shift Request Accepted';
        $message = 'Your shift request for ' . $shiftRequest->shift->title . ' on ' . date('l, F jS', strtotime($shiftRequest->shift->start_date)) . ' from ' . date('h:i A', strtotime($shiftRequest->shift->start_date)) . ' to ' . date('h:i A', strtotime($shiftRequest->shift->end_date)) . ' has been accepted.';

        Mail::raw($message, function ($mail) use ($to, $subject) {
            $mail->to($to);
            $mail->subject($subject);
        });

        $adminEmail = 'nazeermohsin187@gmail.com';
        $adminSubject = 'Shift Request Accepted';
        $adminMessage = "Shift Request Accepted:

        Title: {$shiftRequest->shift->title}
        Start Time: " . date('h:i A', strtotime($shiftRequest->shift->start_date)) . "
        End Time: " . date('h:i A', strtotime($shiftRequest->shift->end_date)) . "
        ";
        Mail::raw($adminMessage, function ($mail) use ($adminEmail, $adminSubject) {
            $mail->to($adminEmail);
            $mail->subject($adminSubject);
        });

        $shiftAccepted = new \App\Models\ShiftAccepted;
        $shiftAccepted->user_id = $shiftRequest->user->id;
        $shiftAccepted->shift_id = $shiftRequest->shift->id;
        $shiftAccepted->save();
        $shift = Shift::find($shiftRequest->shift->id);
        $shift->staff_needed = $shift->staff_needed - 1;
        $shift->save();


        return back()->with('success', 'Shift request accepted.');
    }

    public function showRejectionForm($id)
    {

        $request = ShiftRequest::findOrFail($id);
        return view('shift-requests.reject', compact('request'));
    }

    public function reject($id, Request $request)
    {
        $shiftRequest = ShiftRequest::findOrFail($id);
        $shiftRequest->status = 'rejected';
        $shiftRequest->rejection_reason = $request->rejection_reason;
        $shiftRequest->save();

        $notification = new Notification;
        $notification->user_id = $shiftRequest->user->id;
        $notification->message = 'Your shift request has been rejected.';
        $notification->save();

        $to = $shiftRequest->user->email;
        $subject = 'Shift Request Rejected';
        $message = "Your shift request for {$shiftRequest->shift->title} on " .
            date('l, F jS', strtotime($shiftRequest->shift->start_date)) .
            " from " . date('h:i A', strtotime($shiftRequest->shift->start_date)) .
            " to " . date('h:i A', strtotime($shiftRequest->shift->end_date)) .
            " has been rejected.\n\nReason: {$shiftRequest->rejection_reason}";

        Mail::raw($message, function ($mail) use ($to, $subject) {
            $mail->to($to);
            $mail->cc('nazeermohsin187@gmail.com');
            $mail->subject($subject);
        });

        return redirect('shift-request')->with('success', 'Shift request rejected.');
    }

    public function approved_request()
    {
        $approved = ShiftRequest::where('status', 'accepted')->with(['user', 'shift'])->get();

        return view('shift-requests.accepted', compact('approved'));
    }
    public function rejected_request()
    {
        $rejected = ShiftRequest::where('status', 'rejected')->with(['user', 'shift'])->get();

        return view('shift-requests.rejected', compact('rejected'));
    }


    public function calendar()
    {
        $shifts = Shift::whereNull('deleted_at')->get();

        return response()->json($shifts);
    }

    public function showCalendarView()
    {
        $shifts = Shift::whereNull('deleted_at')->get()->groupBy(function ($date) {
            return Carbon::parse($date->start_date)->format('Y-m-d'); // grouping by date
        });
        $notifications = Notification::where('user_id', auth()->id())->get();

        // Calculate remaining slots for each date
        $remainingSlots = Shift::whereNull('deleted_at')
            ->groupBy(\DB::raw('DATE(start_date)'))
            ->pluck(\DB::raw('count(*)'), \DB::raw('DATE(start_date)'));

        return view('calender', compact('shifts', 'notifications', 'remainingSlots'));
    }





    public function store_shift(Request $request)
    {
        $userId = Auth::id();

        $add = new ShiftRequest();
        $add->shift_id = $request->shift_id;
        $add->user_id = $userId;
        $add->shift_description = $request->description;
        $add->save();

        $admins = \App\Models\User::where('role_id', 1)->get();

        // Send email to the admins
        $toAdmins = $admins->pluck('email')->toArray();
        $subjectToAdmins = 'New Shift Request Submitted';
        $messageToAdmins = "A new shift request has been submitted.\n\n";
        $messageToAdmins .= "Shift ID: {$add->shift_id}\n";
        $messageToAdmins .= "User ID: {$add->user_id}\n";
        $messageToAdmins .= "Description: {$add->shift_description}\n";

        Mail::raw($messageToAdmins, function ($mail) use ($toAdmins, $subjectToAdmins) {
            $mail->to($toAdmins);
            $mail->subject($subjectToAdmins);
        });

        // Send email to the user
        $user = \App\Models\User::find($userId);
        $toUser = $user->email;
        $subjectToUser = 'Shift Request Submitted';
        $messageToUser = 'Your request for the shift has been submitted. Please wait for admin approval.';

        Mail::raw($messageToUser, function ($mail) use ($toUser, $subjectToUser) {
            $mail->to($toUser);
            $mail->subject($subjectToUser);
        });

        return back()->with('success', 'New shift received from ' . Auth::user()->name . '.');
    }
}

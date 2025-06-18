<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use App\Mail\MailableName;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with('user', 'service')->get();
        $services = Service::all();
        return view('booking.index', [
            'bookings' => $bookings,
            'services' => $services,
            // 'userRole' => Auth::user()->role
        ]);
    }

    public function create()
    {
        $services = Service::all();
        return view('booking.add', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // 'user_id' => Auth::id(),
            'service_id' => 'required|exists:services,id',
            'date_booking' => 'required|date',
        ]);
        $validated['user_id'] = Auth::id();

        $existingBooking = Booking::where('user_id', Auth::id())
            ->where('service_id', $validated['service_id'])
            ->whereDate('date_booking', $validated['date_booking'])
            ->first();

        if ($existingBooking) {
            return redirect()->back()->withErrors([
                'date_booking' => 'You have already booked this service on that date. It can only be used once per day.'
            ])->withInput();
        }
        // dd($request->all());
        $booking = Booking::create($validated);
        $booking->load('user');
        Mail::to($booking->user->email)->send(new MailableName($booking));
        return redirect()->route('booking.index')->with('success', 'Booking berhasil ditambahkan!');
    }

    public function show($id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        return response()->json([
            'id' => $booking->id,
            'user_id' => $booking->user_id,
            'service_id' => $booking->service_id,
            'date_booking' => $booking->date_booking,
            'status' => $booking->status,
        ]);
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'date_booking' => 'required|date',
            'status' => 'required|in:pending,completed',
        ]);

        $booking->update($validated);

        return response()->json(['message' => 'Booking updated successfully']);
    }

    public function destroy($id)
    {
        $booking = Booking::find($id);
        $booking->delete();
        return response()->json(['message' => 'Booking deleted successfully.']);
    }
}

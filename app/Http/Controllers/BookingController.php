<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\WorkshopSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BookingController extends Controller
{
    public function create(Request $request): Response|RedirectResponse
    {
        // Find the session by the ?session= query parameter
        $session = WorkshopSession::with('workshop')->findOrFail($request->query('session'));

        // If the session is in the past
        if ($session->starts_at->isPast()) {
            return redirect()->route('home')->with('error', 'Ez az időpont már elmúlt.');
        }

        $spotsLeft = $session->spotsRemaining();

        if ($spotsLeft <= 0) {
            return redirect()->route('home')->with('error', 'Ez az időpont már betelt.');
        }

        // Passing the session data and initial headcount to the Vue page
        // Important to manually shape the data here so we only send what the frontend need
        // The props must match this
        return Inertia::render('Bookings/Create', [
            'session' => [
                'id'           => $session->id,
                'starts_at'    => $session->starts_at,
                'max_capacity' => $session->max_capacity,
                'spots_left'   => $spotsLeft,
                'workshop'     => [
                    'id'               => $session->workshop->id,
                    'name'             => $session->workshop->name,
                    'price_per_person' => $session->workshop->price_per_person,
                    'duration_minutes' => $session->workshop->duration_minutes,
                ],
            ],

            // Read headcount from the URL query string, default to 1 if not set
            'headcount' => (int) $request->query('headcount', 1),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        // Validate the form submission
        // session_id must exist in the workshop_sessions table
        // headcount must be a number between 1 and 10
        $validated = $request->validate([
            'session_id' => 'required|exists:workshop_sessions,id',
            'headcount' => 'required|integer|min:1|max:10'
        ]);

        // Lock to prevent other requests at the same time
        return \DB::transaction(function () use ($validated) {

            // Load the session and its parent workshop so we can calculate the price
            $session = WorkshopSession::with('workshop')->findOrFail($validated['session_id']);

            $session->refresh();

            // IMPORTANT!! Re-check spot availability at submit time
            // Someone else may have booked between when the page loaded and when they submitted
            if ($session->spotsRemaining() < $validated['headcount']) {
                return back()->withErrors([
                    'headcount' => 'Nincs elég szabad hely.',
                ]);
            }

            // If user already has a booking for THIS session
            $alreadyBooked = Booking::where('user_id', auth()->id())
                ->where('session_id', $session->id)
                ->exists();


            if ($alreadyBooked) {
                return back()->withErrors([
                    'headcount' => 'Már van foglalásod erre az időpontra.',
                ]);
            }

            Booking::create([
                'user_id' => auth()->id(),
                'session_id' => $session->id,
                'headcount' => $validated['headcount'],
                'amount_paid' => $session->workshop->price_per_person * $validated['headcount'],
            ]);

            return redirect()->route('dashboard')->with('success', 'Sikeres foglalás!');
        });
    }
}

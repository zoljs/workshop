<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\WorkshopSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Dashboard/Index', [
            'user' => [
                'name' => auth()->user()->name,
                'points' => auth()->user()->points,
            ],

            // only the 3 most recent upcoming bookings for the overview
            'upcomingBookings' => auth()->user()->bookings()
                ->with('session.workshop.instructor')
                ->whereHas('session', fn($q) => $q->where('starts_at', '>', now()))
                ->latest()
                ->take(3)
                ->get(),
        ]);
    }

    public function bookings(): Response
    {
        return Inertia::render('Dashboard/Bookings', [
            'user' => ['name' => auth()->user()->name, 'points' => auth()->user()->points],
            'bookings' => auth()->user()->bookings()
                ->with('session.workshop')
                ->with('session.workshop.instructor')
                ->latest()
                ->get(),
        ]);
    }
}

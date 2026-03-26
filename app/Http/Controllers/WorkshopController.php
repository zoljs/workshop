<?php

namespace App\Http\Controllers;

use App\Models\Workshop;
use Inertia\Inertia;
use Inertia\Response;

class WorkshopController extends Controller
{
    public function index(): Response
    {
        $workshops = Workshop::with(['sessions' => function ($query) {
            $query->where('starts_at', '>', now())
                // withCount should be withSum, otherwise it just counts all entries and doesnt sum at all
                ->withSum('bookings', 'headcount')
                ->orderBy('starts_at');
        }, 'instructor'])
            ->where('archived', false)
            ->get();

        return Inertia::render('Workshops/Index', [
            'workshops' => $workshops,
        ]);
    }

    public function show(Workshop $workshop): Response
    {
        abort_if($workshop->archived, 404);

        $workshop->load(['sessions' => function ($query) {
            $query->where('starts_at', '>', now())
                ->withSum('bookings', 'headcount')
                ->orderBy('starts_at');
        }, 'instructor']);

        return Inertia::render('Workshops/Show', [
            'workshop' => $workshop,
        ]);
    }
}

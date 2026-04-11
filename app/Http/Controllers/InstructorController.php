<?php

namespace App\Http\Controllers;

use App\Models\Workshop;
use App\Models\WorkshopSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class InstructorController extends Controller
{
    // Workshop CRUD
    // Fetch workshops belonging to instructor
    public function index(): Response
    {
        $workshops = Workshop::where('user_id', auth()->id())
            ->with(['sessions' => function ($query) {
                $query->withSum('bookings', 'headcount')
                ->orderBy('starts_at');
            }])
            ->orderByDesc('created_at')
            ->get()
            // Map URLs based on the controller to the index page, and let web.php handle the rest
            ->map(function ($workshop) {
                // Workshops
                $workshop->edit_url    = route('instructor.workshops.edit', $workshop);
                $workshop->archive_url = route('instructor.workshops.archive', $workshop);

                // Sessions
                $workshop->sessions->transform(function ($session) {
                    $session->edit_url = route('instructor.sessions.edit', $session);
                    $session->cancel_url = route('instructor.sessions.cancel', $session);
                    return $session;
                });
                return $workshop;
            });

        // Pass props
        return Inertia::render('Instructor/Workshops/Index', [
            'workshops' => $workshops,
            // URLs
            'workshop_create' => route('instructor.workshops.create'),
            'session_create'  => route('instructor.sessions.create'),
            'session_store_url'  => route('instructor.sessions.store'),
        ]);
    }

    // Render create workshop form
    public function createWorkshop(): Response
    {
        return Inertia::render('Instructor/Workshops/Create', [
            'store_url'  => route('instructor.workshops.store'),
        ]);
    }

    // Validate and store the new workshop, then redirect to the workshop list
    public function storeWorkshop(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'required|string',
            'duration_minutes' => 'required|integer|min:15|max:480',
            'price_per_person' => 'required|integer|min:0',
        ]);

        Workshop::create([
            ...$validated,
            'user_id'  => auth()->id(),
            'archived' => false,
        ]);

        return redirect()->route('instructor.workshops.index')
            ->with('success', 'Workshop created successfully.');
    }

    // Render edit form, only if the workshop belongs to the instructor
    public function editWorkshop(Workshop $workshop): Response|RedirectResponse
    {
        $this->authorizeWorkshop($workshop);

        $workshop->load(['sessions' => function ($query) {
            $query->withSum('bookings', 'headcount')
                ->orderBy('starts_at');
        }]);

        return Inertia::render('Instructor/Workshops/Edit', [
            'workshop' => $workshop,
            // Pass URLs as well so web.php can handle the rest
            'update_url'  => route('instructor.workshops.update', $workshop),
            'archive_url' => route('instructor.workshops.archive', $workshop),
        ]);
    }

    // Validate and update the workshop, only if it belongs to the instructor
    public function updateWorkshop(Request $request, Workshop $workshop): RedirectResponse
    {
        $this->authorizeWorkshop($workshop);

        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'required|string',
            'duration_minutes' => 'required|integer|min:15|max:480',
            'price_per_person' => 'required|integer|min:0',
        ]);

        $workshop->update($validated);

        return redirect()->route('instructor.workshops.index')
            ->with('success', 'Workshop updated successfully.');
    }

    // Archive the workshop (soft delete), only if it belongs to the instructor
    public function archiveWorkshop(Workshop $workshop): RedirectResponse
    {
        $this->authorizeWorkshop($workshop);

        // Cancel all of it's sessions in a transaction safely
        \DB::transaction(function () use ($workshop) {
            $workshop->sessions()
                ->where('status', '!=', 'cancelled')
                ->each(function ($session) {
                    $session->bookings()->update(['status' => 'cancelled_by_instructor']);
                    $session->update(['status' => 'cancelled']);
                });

            $workshop->update(['archived' => true]);
        });

        return redirect()->route('instructor.workshops.index')
            ->with('success', 'Workshop archived.');
    }


    // Sessions CRUD
    // Render the create form
    public function createSession(): Response
    {
        // Only own, non-archived workshops available to attach a session to
        $workshops = Workshop::where('user_id', auth()->id())
            ->where('archived', false)
            ->orderBy('name')
            ->get(['id', 'name']);

        return Inertia::render('Instructor/Sessions/Create', [
            'workshops' => $workshops,
            'store_url'  => route('instructor.sessions.store'),
        ]);
    }

    // After validation, does a second ownership check on the workshop_id that was submitted, because a malicious user could POST any workshop ID they want regardless of what was in the dropdown
    public function storeSession(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'workshop_id'  => 'required|exists:workshops,id',
            'starts_at'    => 'required|date|after:now',
            'max_capacity' => 'required|integer|min:1|max:100',
        ]);

        // Make sure the workshop belongs to the instructor
        $workshop = Workshop::findOrFail($validated['workshop_id']);
        if ($workshop->user_id !== auth()->id()) {
            abort(403);
        }

        WorkshopSession::create($validated);

        return redirect()->route('instructor.workshops.index')
            ->with('success', 'Session added successfully.');
    }

    // Edit session form
    public function editSession(WorkshopSession $session): Response|RedirectResponse
    {
        $this->authorizeSession($session);

        $session->load('workshop');

        return Inertia::render('Instructor/Sessions/Edit', [
            'session' => [
                'id'           => $session->id,
                'starts_at'    => $session->starts_at,
                'max_capacity' => $session->max_capacity,
                'status'       => $session->status,
                'booked_count' => $session->bookings()->sum('headcount'),
                'workshop'     => [
                    'id'   => $session->workshop->id,
                    'name' => $session->workshop->name,
                ],
            ],
            // Pass URLs as well so web.php can handle the rest
            'update_url' => route('instructor.sessions.update', $session),
            'cancel_url' => route('instructor.sessions.cancel', $session),
        ]);
    }

    // Update the session after validation
    public function updateSession(Request $request, WorkshopSession $session): RedirectResponse
    {
        $this->authorizeSession($session);

        $bookedCount = $session->bookings()->sum('headcount');

        $validated = $request->validate([
            'starts_at'    => 'required|date|after:now',
            'max_capacity' => [
                'required',
                'integer',
                'min:' . $bookedCount, // Cannot go below current bookings
                'max:100',
            ],
        ]);

        $session->update($validated);

        return redirect()->route('instructor.workshops.index')
            ->with('success', 'Session updated successfully.');
    }

    public function cancelSession(WorkshopSession $session): RedirectResponse
    {
        $this->authorizeSession($session);

        \DB::transaction(function () use ($session) {
            // Mark all bookings as cancelled by instructor
            $session->bookings()->update(['status' => 'cancelled_by_instructor']);

            // Mark the session itself as cancelled
            $session->update(['status' => 'cancelled']);
        });

        return redirect()->route('instructor.workshops.index')
            ->with('success', 'Session cancelled. All bookings have been marked as cancelled.');
    }

    // Helpers

    private function authorizeWorkshop(Workshop $workshop): void
    {
        if ($workshop->user_id !== auth()->id()) {
            abort(403);
        }
    }

    private function authorizeSession(WorkshopSession $session): void
    {
        $session->loadMissing('workshop');
        if ($session->workshop->user_id !== auth()->id()) {
            abort(403);
        }
    }
}

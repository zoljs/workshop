<?php

    namespace App\Http\Controllers;

    use App\Models\User;
    use App\Models\Workshop;
    use App\Models\WorkshopSession;
    use Illuminate\Http\RedirectResponse;
    use Illuminate\Http\Request;
    use Inertia\Inertia;
    use Inertia\Response;

    class AdminController extends Controller
    {
        // Users

        public function users(): Response
        {
            $users = User::whereDoesntHave('roles', fn($q) => $q->where('name', 'admin'))
                ->with('roles')
                ->orderBy('name')
                ->get();

            return Inertia::render('Admin/Users', [
                'users' => $users->map(fn($user) => [
                    'id'         => $user->id,
                    'name'       => $user->name,
                    'email'      => $user->email,
                    'points'     => $user->points,
                    'banned_until' => $user->banned_until,
                    'created_at' => $user->created_at,
                    'roles'      => $user->roles->pluck('name'),
                ]),
            ]);
        }

        // Instructor workshops (same logic as InstructorController but scoped to target instructor)

        public function instructorWorkshops(User $instructor): Response
        {
            $this->authorizeInstructor($instructor);

            $workshops = Workshop::where('user_id', $instructor->id)
                ->with(['sessions' => function ($query) {
                    $query->withSum('bookings', 'headcount')
                        ->orderBy('starts_at');
                }])
                ->orderByDesc('created_at')
                ->get()
                // Map URLs based on the controller to the index page, and let web.php handle the rest
                ->map(function ($workshop) use ($instructor) {
                    $workshop->edit_url    = route('admin.instructor.workshops.edit', [$instructor, $workshop]);
                    $workshop->archive_url = route('admin.instructor.workshops.archive', [$instructor, $workshop]);
                    $workshop->sessions->transform(function ($session) use ($instructor) {
                        $session->edit_url   = route('admin.instructor.sessions.edit', [$instructor, $session]);
                        $session->cancel_url = route('admin.instructor.sessions.cancel', [$instructor, $session]);
                        return $session;
                    });
                    return $workshop;
                });

            return Inertia::render('Instructor/Workshops/Index', [
                'workshops'       => $workshops,
                'workshop_create' => route('admin.instructor.workshops.create', $instructor),
                'session_create'  => route('admin.instructor.sessions.create', $instructor),
                'instructor'      => ['id' => $instructor->id, 'name' => $instructor->name],
            ]);
        }

        public function createWorkshop(User $instructor): Response
        {
            $this->authorizeInstructor($instructor);

            return Inertia::render('Instructor/Workshops/Create', [
                'instructor' => ['id' => $instructor->id, 'name' => $instructor->name],
            ]);
        }

        public function storeWorkshop(Request $request, User $instructor): RedirectResponse
        {
            $this->authorizeInstructor($instructor);

            $validated = $request->validate([
                'name'             => 'required|string|max:255',
                'description'      => 'required|string',
                'duration_minutes' => 'required|integer|min:15|max:480',
                'price_per_person' => 'required|integer|min:0',
            ]);

            Workshop::create([
                ...$validated,
                'user_id'  => $instructor->id,
                'archived' => false,
            ]);

            return redirect()->route('admin.instructor.workshops', $instructor->id)
                ->with('success', 'Workshop created.');
        }

        public function editWorkshop(User $instructor, Workshop $workshop): Response|RedirectResponse
        {
            $this->authorizeInstructor($instructor);
            $this->authorizeWorkshop($workshop, $instructor);

            $workshop->load(['sessions' => function ($query) {
                $query->withSum('bookings', 'headcount')->orderBy('starts_at');
            }]);

            return Inertia::render('Instructor/Workshops/Edit', [
                'workshop'   => $workshop,
                'instructor' => ['id' => $instructor->id, 'name' => $instructor->name],
                // Pass URLs as well so web.php can handle the rest
                'update_url'  => route('admin.instructor.workshops.update', [$instructor, $workshop]),
                'archive_url' => route('admin.instructor.workshops.archive', [$instructor, $workshop]),
            ]);
        }

        public function updateWorkshop(Request $request, User $instructor, Workshop $workshop): RedirectResponse
        {
            $this->authorizeInstructor($instructor);
            $this->authorizeWorkshop($workshop, $instructor);

            $validated = $request->validate([
                'name'             => 'required|string|max:255',
                'description'      => 'required|string',
                'duration_minutes' => 'required|integer|min:15|max:480',
                'price_per_person' => 'required|integer|min:0',
            ]);

            $workshop->update($validated);

            return redirect()->route('admin.instructor.workshops', $instructor->id)
                ->with('success', 'Workshop updated.');
        }

        public function archiveWorkshop(User $instructor, Workshop $workshop): RedirectResponse
        {
            $this->authorizeInstructor($instructor);
            $this->authorizeWorkshop($workshop, $instructor);

            $workshop->update(['archived' => true]);

            return redirect()->route('admin.instructor.workshops', $instructor->id)
                ->with('success', 'Workshop archived.');
        }

        // Sessions

        public function createSession(User $instructor): Response
        {
            $this->authorizeInstructor($instructor);

            $workshops = Workshop::where('user_id', $instructor->id)
                ->where('archived', false)
                ->orderBy('name')
                ->get(['id', 'name']);

            return Inertia::render('Instructor/Sessions/Create', [
                'workshops'  => $workshops,
                'instructor' => ['id' => $instructor->id, 'name' => $instructor->name],
            ]);
        }

        public function storeSession(Request $request, User $instructor): RedirectResponse
        {
            $this->authorizeInstructor($instructor);

            $validated = $request->validate([
                'workshop_id'  => 'required|exists:workshops,id',
                'starts_at'    => 'required|date|after:now',
                'max_capacity' => 'required|integer|min:1|max:100',
            ]);

            $workshop = Workshop::findOrFail($validated['workshop_id']);
            if ($workshop->user_id !== $instructor->id) {
                abort(403);
            }

            WorkshopSession::create($validated);

            return redirect()->route('admin.instructor.workshops', $instructor->id)
                ->with('success', 'Session added.');
        }

        public function editSession(User $instructor, WorkshopSession $session): Response|RedirectResponse
        {
            $this->authorizeInstructor($instructor);
            $this->authorizeSession($session, $instructor);

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
                'instructor' => ['id' => $instructor->id, 'name' => $instructor->name],
                // Pass URLs as well so web.php can handle the rest
                'update_url' => route('admin.instructor.sessions.update', [$instructor, $session]),
                'cancel_url' => route('admin.instructor.sessions.cancel', [$instructor, $session]),
            ]);
        }

        public function updateSession(Request $request, User $instructor, WorkshopSession $session): RedirectResponse
        {
            $this->authorizeInstructor($instructor);
            $this->authorizeSession($session, $instructor);

            $bookedCount = $session->bookings()->sum('headcount');

            $validated = $request->validate([
                'starts_at'    => 'required|date|after:now',
                'max_capacity' => ['required', 'integer', 'min:' . $bookedCount, 'max:100'],
            ]);

            $session->update($validated);

            return redirect()->route('admin.instructor.workshops', $instructor->id)
                ->with('success', 'Session updated.');
        }

        public function cancelSession(User $instructor, WorkshopSession $session): RedirectResponse
        {
            $this->authorizeInstructor($instructor);
            $this->authorizeSession($session, $instructor);

            \DB::transaction(function () use ($session) {
                $session->bookings()->update(['status' => 'cancelled_by_instructor']);
                $session->update(['status' => 'cancelled']);
            });

            return redirect()->route('admin.instructor.workshops', $instructor->id)
                ->with('success', 'Session cancelled.');
        }

        // Authorization helpers

        private function authorizeInstructor(User $instructor): void
        {
            if (!$instructor->hasRole('instructor')) {
                abort(403);
            }
        }

        private function authorizeWorkshop(Workshop $workshop, User $instructor): void
        {
            if ($workshop->user_id !== $instructor->id) {
                abort(403);
            }
        }

        private function authorizeSession(WorkshopSession $session, User $instructor): void
        {
            $session->loadMissing('workshop');
            if ($session->workshop->user_id !== $instructor->id) {
                abort(403);
            }
        }
    }

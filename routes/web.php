<?php

    use App\Http\Controllers\AdminController;
    use App\Http\Controllers\BookingController;
    use App\Http\Controllers\DashboardController;
    use App\Http\Controllers\ProfileController;
    use Illuminate\Support\Facades\Route;

    // Workshop specific
    use App\Http\Controllers\WorkshopController;

    // Instructor specific
    use App\Http\Controllers\InstructorController;

    // Index page showing workshops
    Route::get('/', [WorkshopController::class, 'index'])->name('home');

    // Specific workshop page
    Route::get('/workshops/{workshop}', [WorkshopController::class, 'show'])->name('workshops.show');


    /*Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');*/

    // Login guarded routes
    Route::middleware('auth')->group(function () {

        // Dashboard routes
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // My Bookings
        Route::get('/dashboard/bookings', [DashboardController::class, 'bookings'])->name('dashboard.bookings');

        // Points & Rewards
        //Route::get('/dashboard/rewards', [DashboardController::class, 'rewards'])->name('dashboard.rewards');
        //Route::post('/dashboard/rewards/redeem', [DashboardController::class, 'redeem'])->name('rewards.redeem');

        // Profile routes
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // Booking routes
        Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
        Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
        Route::get('/bookings/{booking}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
        Route::patch('/bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update');
        Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

        // Instructor routes
        Route::middleware('role:instructor')->prefix('instructor')->name('instructor.')->group(function () {

            Route::get('/workshops', [InstructorController::class, 'index'])->name('workshops.index');
            Route::get('/workshops/create', [InstructorController::class, 'createWorkshop'])->name('workshops.create');
            Route::post('/workshops', [InstructorController::class, 'storeWorkshop'])->name('workshops.store');
            Route::get('/workshops/{workshop}/edit', [InstructorController::class, 'editWorkshop'])->name('workshops.edit');
            Route::patch('/workshops/{workshop}', [InstructorController::class, 'updateWorkshop'])->name('workshops.update');
            Route::patch('/workshops/{workshop}/archive', [InstructorController::class, 'archiveWorkshop'])->name('workshops.archive');

            Route::get('/sessions/create', [InstructorController::class, 'createSession'])->name('sessions.create');
            Route::post('/sessions', [InstructorController::class, 'storeSession'])->name('sessions.store');
            Route::get('/sessions/{session}/edit', [InstructorController::class, 'editSession'])->name('sessions.edit');
            Route::patch('/sessions/{session}', [InstructorController::class, 'updateSession'])->name('sessions.update');
            Route::patch('/sessions/{session}/cancel', [InstructorController::class, 'cancelSession'])->name('sessions.cancel');
        });

        // Admin routes
        Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {

            Route::get('/users', [AdminController::class, 'users'])->name('users');

            Route::get('/instructors/{instructor}/workshops', [AdminController::class, 'instructorWorkshops'])->name('instructor.workshops');
            Route::get('/instructors/{instructor}/workshops/create', [AdminController::class, 'createWorkshop'])->name('instructor.workshops.create');
            Route::post('/instructors/{instructor}/workshops', [AdminController::class, 'storeWorkshop'])->name('instructor.workshops.store');
            Route::get('/instructors/{instructor}/workshops/{workshop}/edit', [AdminController::class, 'editWorkshop'])->name('instructor.workshops.edit');
            Route::patch('/instructors/{instructor}/workshops/{workshop}', [AdminController::class, 'updateWorkshop'])->name('instructor.workshops.update');
            Route::patch('/instructors/{instructor}/workshops/{workshop}/archive', [AdminController::class, 'archiveWorkshop'])->name('instructor.workshops.archive');

            Route::get('/instructors/{instructor}/sessions/create', [AdminController::class, 'createSession'])->name('instructor.sessions.create');
            Route::post('/instructors/{instructor}/sessions', [AdminController::class, 'storeSession'])->name('instructor.sessions.store');
            Route::get('/instructors/{instructor}/sessions/{session}/edit', [AdminController::class, 'editSession'])->name('instructor.sessions.edit');
            Route::patch('/instructors/{instructor}/sessions/{session}', [AdminController::class, 'updateSession'])->name('instructor.sessions.update');
            Route::patch('/instructors/{instructor}/sessions/{session}/cancel', [AdminController::class, 'cancelSession'])->name('instructor.sessions.cancel');

        });
    });

    require __DIR__.'/auth.php';

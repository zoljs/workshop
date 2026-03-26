<?php

    use App\Http\Controllers\BookingController;
    use App\Http\Controllers\DashboardController;
    use App\Http\Controllers\ProfileController;
    use App\Models\Booking;
    use Illuminate\Foundation\Application;
    use Illuminate\Support\Facades\Route;
    use Inertia\Inertia;

    // Workshop specific
    use App\Http\Controllers\WorkshopController;

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

    });

    require __DIR__.'/auth.php';

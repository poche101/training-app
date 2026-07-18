<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // <-- CRITICAL: Imported to prevent fallback crashes
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

// Public Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\LivestreamController;
use App\Http\Controllers\TestimonyController;
use App\Http\Controllers\EventRegistrationController;
use App\Models\Livestream;

// Auth Controllers
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LivestreamController as AdminLivestreamController;
use App\Http\Controllers\Admin\ResourceController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\EventRegistrationController as AdminEventRegistrationController;

// ─── Core Landing & Authentication Redirection Logic ──────────────────────
Route::get('/', function () {
    return redirect()->route('login');
});

// ─── Public Routes (No Auth Required) ───────────────────────────────────────
Route::get('/livestreams', [LivestreamController::class, 'index'])->name('livestreams');
Route::get('/livestreams/{livestream}', [LivestreamController::class, 'show'])->name('stream.view');
Route::post('/livestreams/{livestream}/heartbeat', [LivestreamController::class, 'heartbeat'])->name('stream.heartbeat');

// Live comments — guests can view and post comments on a stream; replies
// are posted separately by admins from the admin panel (see below).
Route::get('/livestreams/{livestream}/comments', [LivestreamController::class, 'comments'])->name('stream.comments');
Route::post('/livestreams/{livestream}/comments', [LivestreamController::class, 'storeComment'])->name('stream.comments.store');

Route::post('/testimony', [TestimonyController::class, 'submit'])->name('testimony.submit');

// Public event registration (Healing Streams Prayer Outreach form) — must stay
// outside the auth/verified group so guests can register without logging in.
Route::post('/member/event-register', [EventRegistrationController::class, 'store'])->name('member.event.register');

/**
 * The Main Event Page (A Day Of Blessings layout) — now standalone/public
 * Resides inside resources/views/public/home.blade.php
 */
Route::get('/home', function () {
    $liveLivestream = Livestream::where('status', 'live')->latest('started_at')->first();

    return view('public.home', compact('liveLivestream'));
})->name('home');

// ─── Guest Routes (Only Unauthenticated Visitors) ───────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// ─── Email Verification Intermediary Routes ──────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('welcome');
    })->middleware('signed')->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    })->middleware('throttle:6,1')->name('verification.send');
});

// ─── Fully Authenticated & Verified Application Wrapper ─────────────────────
Route::middleware(['auth', 'verified'])->group(function () {

    /**
     * The Public Platform Dashboard (General update layout)
     * Feeds calendar events, announcements, and testimonies from the database.
     */
    Route::get('/welcome', [HomeController::class, 'index'])->name('welcome');

    Route::get('/events', [HomeController::class, 'events'])->name('events');

    // ─── Member Sub-Routes ───────────────────────────────────────────────────
    Route::prefix('member')->name('member.')->group(function () {
        Route::get('/dashboard', [MemberController::class, 'dashboard'])->name('dashboard');
        Route::get('/resources', [MemberController::class, 'resources'])->name('resources');
        Route::get('/resources/{resource}/download', [MemberController::class, 'downloadResource'])->name('resources.download');
        Route::get('/announcements', [MemberController::class, 'announcements'])->name('announcements');
        Route::get('/events', [MemberController::class, 'events'])->name('events');
        Route::post('/events/{event}/rsvp', [MemberController::class, 'rsvpEvent'])->name('events.rsvp');
        Route::post('/events/{event}/checkin', [MemberController::class, 'checkinEvent'])->name('events.checkin');
        Route::get('/offerings', [MemberController::class, 'offerings'])->name('offerings');
        Route::get('/profile', [MemberController::class, 'profile'])->name('profile');
        Route::patch('/profile', [MemberController::class, 'updateProfile'])->name('profile.update');
    });

    // ─── Admin Sub-Routes ────────────────────────────────────────────────────
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/analytics', [DashboardController::class, 'analytics'])->name('analytics');

        // Testimonies
        Route::get('/testimonies', [App\Http\Controllers\Admin\TestimonyController::class, 'index'])->name('testimonies.index');
        Route::patch('/testimonies/{testimony}/approve', [App\Http\Controllers\Admin\TestimonyController::class, 'approve'])->name('testimonies.approve');
        Route::delete('/testimonies/{testimony}', [App\Http\Controllers\Admin\TestimonyController::class, 'destroy'])->name('testimonies.destroy');

        // Livestreams
        Route::resource('livestreams', AdminLivestreamController::class);
        Route::post('/livestreams/{livestream}/go-live', [AdminLivestreamController::class, 'goLive'])->name('livestreams.go-live');
        Route::post('/livestreams/{livestream}/end', [AdminLivestreamController::class, 'endStream'])->name('livestreams.end');

        // Livestream comment replies — admin/pastoral team responds to a
        // viewer's comment; the reply is stored as a threaded child comment.
        Route::post('/livestreams/{livestream}/comments/{comment}/reply', [AdminLivestreamController::class, 'replyComment'])
            ->name('livestreams.comments.reply');

        // Resources
        Route::resource('resources', ResourceController::class)->except(['show', 'edit', 'update']);
        Route::get('/resource-categories', [ResourceController::class, 'categories'])->name('resources.categories');
        Route::post('/resource-categories', [ResourceController::class, 'storeCategory'])->name('resources.categories.store');

        // Events
        Route::resource('events', EventController::class);

        // Event Registrations
        Route::get('/event-registrations/export', [AdminEventRegistrationController::class, 'export'])->name('event-registrations.export');
        Route::get('/event-registrations', [AdminEventRegistrationController::class, 'index'])->name('event-registrations.index');

        // Announcements
        Route::resource('announcements', AnnouncementController::class)->except(['show']);

        // Users
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.role');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::get('/activity-logs', [UserController::class, 'activityLogs'])->name('activity-logs');
    });
});

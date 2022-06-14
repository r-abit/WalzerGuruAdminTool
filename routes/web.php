<?php

use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DancingController;
use App\Http\Controllers\EventsController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard.show');
//    return Inertia::render('Welcome', [
//        'canLogin' => Route::has('login'),
//        'canRegister' => Route::has('register'),
//        'laravelVersion' => Application::VERSION,
//        'phpVersion' => PHP_VERSION,
//    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard/Show');
    })->name('dashboard.show');

    Route::controller(OrganizerController::class)->group(function () {
        Route::get('/organizers', 'show')->name('organizers.show');
        Route::post('/organizers', 'upsert')->name('organizers.upsert');
        Route::delete('/organizers', 'delete')->name('organizers.delete');
    });

    Route::controller(EventsController::class)->group(function () {
        Route::get('/events', 'show')->name('events.show');
        Route::post('/events', 'upsert')->name('events.upsert');
        Route::delete('/events', 'delete')->name('events.delete');
        Route::post('/event/participate', 'participate')->name('event.participation');
    });

    Route::controller(DancingController::class)->group(function () {
        Route::get('/dancing', 'show')->name('dancing.show');
        Route::delete('/dancing', 'delete')->name('dancing.delete');
    });

    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'show')->name('dashboard.show');
        Route::delete('/dashboard', 'delete')->name('dashboard.delete');
    });
});

/** This allows us to customerize the routes of packages as needed */
require_once __DIR__ . '/fortify.php';
require_once __DIR__ . '/inertia.php';

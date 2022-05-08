<?php

use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\FellowController;
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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::controller(OrganizerController::class)->group(function () {
        Route::get('/organizers', 'show')->name('organizers.show');
        Route::post('/organizers', 'upsert')->name('organizers.upsert');
        Route::delete('/organizers', 'delete')->name('organizers.delete');
    });

    Route::controller(EventsController::class)->group(function () {
        Route::get('/events', 'show')->name('events.show');
        Route::post('/events', 'upsert')->name('events.upsert');
        Route::delete('/events', 'delete')->name('events.delete');
    });

    Route::controller(FellowController::class)->group(function () {
        Route::get('/fellow', 'show')->name('fellow.show');
    });
});

/** This allows us to customerize the routes of packages as needed */
require_once __DIR__ . '/fortify.php';
require_once __DIR__ . '/inertia.php';

<?php

use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Livewire\Volt\Volt;

Volt::route('/', 'pages.welcome')->name('home');

// Export Routes
Route::middleware(['auth'])->prefix('export')->name('export.')->group(function () {
    Route::get('dashboard', [ExportController::class, 'dashboard'])->name('dashboard');
    Route::get('events/{event}', [ExportController::class, 'eventDetail'])->name('event');
    Route::get('respondents', [ExportController::class, 'respondents'])->name('respondents');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Rute untuk Master Data Pertanyaan
\Livewire\Volt\Volt::route('questions', 'pages.questions.index')
    ->middleware(['auth'])
    ->name('questions');

// Rute baru untuk Data Event
\Livewire\Volt\Volt::route('events', 'pages.events.index')
    ->middleware(['auth'])
    ->name('events');

// Rute baru untuk Detail Event
\Livewire\Volt\Volt::route('events/{event}', 'pages.events.detail')
    ->middleware(['auth'])
    ->name('events.detail');

// Rute untuk Data Responden
\Livewire\Volt\Volt::route('respondents', 'pages.respondents.index')
    ->middleware(['auth'])
    ->name('respondents');

\Livewire\Volt\Volt::route('skrining/{event}', 'pages.skrining.welcome')
    ->name('skrining.welcome');

\Livewire\Volt\Volt::route('skrining/{event}/data-diri', 'pages.skrining.data-diri')
    ->name('skrining.data-diri');

\Livewire\Volt\Volt::route('skrining/{event}/petunjuk', 'pages.skrining.petunjuk')
    ->name('skrining.petunjuk');

\Livewire\Volt\Volt::route('skrining/{event}/disclaimer', 'pages.skrining.disclaimer')
    ->name('skrining.disclaimer');

\Livewire\Volt\Volt::route('skrining/{event}/pertanyaan', 'pages.skrining.pertanyaan')
    ->name('skrining.pertanyaan');

\Livewire\Volt\Volt::route('skrining/{event}/hasil/{respondent}', 'pages.skrining.hasil')
    ->name('skrining.hasil');

\Livewire\Volt\Volt::route('skrining/{event}/penutup/{respondent}', 'pages.skrining.penutup')
    ->name('skrining.penutup');

Route::post('/logout', function () {
    Auth::guard('web')->logout();
    Session::invalidate();
    Session::regenerateToken();
    return redirect('/admin');
})->middleware('auth')->name('logout');

require __DIR__.'/auth.php';
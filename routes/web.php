<?php

use App\Http\Controllers\TeamController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// Redirect root URL to teams index
Route::get('/', fn() => redirect()->route('teams.index'));

// Auth scaffolding routes (login, register, etc.)
require __DIR__.'/auth.php';

// Protected routes (only accessible when logged in)
Route::middleware('auth')->group(function () {
    // Dashboard route (fixes "Route [dashboard] not defined" error)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Teams routes
    Route::resource('teams', TeamController::class)->only(['index','create','store','show']);
    Route::post('teams/{team}/invite', [TeamController::class, 'invite'])->name('teams.invite');

    // Tasks routes (scoped under a team)
    Route::get('teams/{team}/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('teams/{team}/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('teams/{team}/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('teams/{team}/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('teams/{team}/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
});

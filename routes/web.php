<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\PodomoroController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\TrashController;
use App\Http\Models\Task;

Auth::routes(['register' => true]);

// Redirect setelah login sukses
Route::get('/home', function () {
    return redirect()->route('tasks.index');
})->middleware('auth')->name('home');

// Routes yang membutuhkan autentikasi
Route::middleware('auth')->group(function () {
    // Task Routes
    Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::patch('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    // Podomoro Route
    Route::get('/podomoro', [PodomoroController::class, 'index'])->name('podomoro');

    // Calendar Route
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');

    // Trash Routes
    Route::prefix('trash')->group(function () {
        Route::get('/', [TrashController::class, 'index'])->name('trash.index');
        Route::post('/{id}/restore', [TrashController::class, 'restore'])->name('trash.restore');
        Route::delete('/{id}', [TrashController::class, 'destroy'])->name('trash.destroy');
    });
});

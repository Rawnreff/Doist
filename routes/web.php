<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Auth\LoginController;

Auth::routes(['register' => true]); // Sesuaikan dengan kebutuhan registrasi

// Redirect setelah login sukses
Route::get('/home', function () {
    return redirect()->route('tasks.index');
})->middleware('auth')->name('home');

// Routes yang membutuhkan autentikasi
Route::middleware('auth')->group(function () {
    Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::patch('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
});
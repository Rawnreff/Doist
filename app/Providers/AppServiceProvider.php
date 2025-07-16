<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot()
{
    view()->composer('layouts.app', function ($view) {
        if (Auth::check()) {
            $deletedTasksCount = Auth::user()->tasks()->onlyTrashed()->count();
            $view->with('deletedTasksCount', $deletedTasksCount);
        }
    });
}
}

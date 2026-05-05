<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            // Auto-seed database jika kosong (Sangat berguna untuk NativePHP Mobile SQLite)
            if (\Illuminate\Support\Facades\Schema::hasTable('users') && \App\Models\User::count() === 0) {
                \Illuminate\Support\Facades\Artisan::call('db:seed');
            }
        } catch (\Exception $e) {
            // Abaikan error jika database belum siap
        }
    }
}

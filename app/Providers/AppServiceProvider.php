<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
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
        // define Response Macros
        Response::macro('success', function ($data = null, $message = null, $status = 200) {
            return response()->json([
                'status' => 'success',
                'message' => $message,
                'data' => $data,
            ], $status);
        });
        Response::macro('error', function ($message = null, $status = 400) {
            return response()->json([
                'status' => 'error',
                'message' => $message,
            ], $status);
        });
    }
}

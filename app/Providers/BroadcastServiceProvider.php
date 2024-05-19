<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Broadcast::routes(['middleware' => ['web', 'auth']]);
        Broadcast::routes();
        // Broadcast::channel('public-channel', function () {
        //     return true; // Allow access without authentication
        // });

        require base_path('routes/channels.php');
    }
}

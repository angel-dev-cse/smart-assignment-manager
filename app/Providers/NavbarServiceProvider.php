<?php

namespace App\Providers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

class NavbarServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        View::composer('components.navbar', function ($view) {
            $user = Auth::user();

            // Get the current time using Carbon
            $currentTime = Carbon::now();

            // Determine the greeting based on the current time
            if ($currentTime->hour >= 5 && $currentTime->hour < 12) {
                $greeting = 'Morning';
            } elseif ($currentTime->hour >= 12 && $currentTime->hour < 17) {
                $greeting = 'Afternoon';
            } elseif ($currentTime->hour >= 17 && $currentTime->hour < 20) {
                $greeting = 'Evening';
            } else {
                $greeting = 'Night';
            }

            $notifications = Notification::where('user_id', $user->id)->orderBy('created_at', 'desc')->where('seen', false)->get();

            $notificationCount = $notifications->count();
            
            $view->with('greeting', $greeting);
            $view->with('notifications', $notifications);
            $view->with('notificationCount', $notificationCount);
            $view->with('user', $user);
        });
    }
}
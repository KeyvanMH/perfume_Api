<?php

namespace App\Providers;

use App\Models\Brand;
use App\Models\Factor;
use App\Models\User;
use App\Observers\BrandObserver;
use App\Observers\FactorObserver;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\Gate;
use App\Models\PerfumeComment;
use App\Models\PerfumeCommentReply;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
        /* observers */
        User::observe(UserObserver::class);
        Factor::observe(FactorObserver::class);
        Brand::observe(BrandObserver::class);

        /* all gate's */
        Gate::define('manipulate_factor',function (User $user, Factor $factor){
            return $user->id == $factor->user_id || $user->role == 'super_admin';
        });
        Gate::define('manipulate-comment',function (User $user,PerfumeComment $comment){
            return $comment->user_id == $user->id || $user->role == 'super_admin';
        });
        Gate::define('manipulate-reply',function (User $user,PerfumeCommentReply $reply){
            return $reply->user_id == $user->id || $user->role == 'super_admin';
        });
    }
}

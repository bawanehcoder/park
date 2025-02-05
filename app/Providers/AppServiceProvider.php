<?php

namespace App\Providers;

use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Illuminate\Support\ServiceProvider;
use TomatoPHP\FilamentSubscriptions\Facades\FilamentSubscriptions;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(\L5Swagger\L5SwaggerServiceProvider::class);

        

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        FilamentSubscriptions::register(
            \TomatoPHP\FilamentSubscriptions\Services\Contracts\Subscriber::make('Company')
                ->name('Company')
                ->model(\App\Models\company::class)
        );

        FilamentSubscriptions::register(
            \TomatoPHP\FilamentSubscriptions\Services\Contracts\Subscriber::make('Company')
                ->name('User')
                ->model(\App\Models\User::class)
        );

        FilamentSubscriptions::afterSubscription(function (array $data){
            // your logic here
        });

        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['ar','en']); // also accepts a closure
        });

        
    }
}

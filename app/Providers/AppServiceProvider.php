<?php

namespace App\Providers;

use App\Models\Reservation;
use App\Observers\ReservationObserver;
use Illuminate\Support\Facades\Blade;
use Illuminate\Database\Eloquent\Model;
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
        Reservation::observe(ReservationObserver::class);
        Model::automaticallyEagerLoadRelationships();
        Blade::directive('honeypot', function () {
            return "<?php
        echo '<input type=\"text\" name=\"_hp\" autocomplete=\"off\" tabindex=\"-1\" aria-hidden=\"true\" style=\"position:absolute;left:-9999px;\">';
        echo '<input type=\"hidden\" name=\"_hptime\" value=\"' . time() . '\">';
    ?>";
        });
    }
}

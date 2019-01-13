<?php

namespace App\Providers;

use App\Support\Interfaces\PaymentClientInterface;
use App\Util\YandexPaymentClient;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PaymentClientInterface::class, YandexPaymentClient::class);
    }
}

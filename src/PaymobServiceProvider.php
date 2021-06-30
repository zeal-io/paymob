<?php

namespace Zeal\ZealSMS;

use Illuminate\Support\ServiceProvider;
use Zeal\Paymob\Paymob;

class PaymobServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/payment.php' => config_path('payment.php')
        ]);
    }

    public function register()
    {
        parent::register(); // TODO: Change the autogenerated stub

        $this->mergeConfigFrom(
            __DIR__ . '/../config/payment.php',
            'payment'
        );

	    $this->app->singleton(Paymob::class, function () {
		    return new Paymob(config('payment.paymob.api_key'));
	    });
    }
}

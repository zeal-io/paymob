<?php

namespace Zeal\Paymob;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Zeal\Paymob\Core\PaymobClient;

class PaymobServiceProvider extends PackageServiceProvider
{
    public function register()
    {
        parent::register(); // TODO: Change the autogenerated stub

        $this->mergeConfigFrom(
            __DIR__ . '/../config/payment.php',
            'payment'
        );

        $this->app->singleton(PaymobClient::class, function () {
            return new PaymobClient(config('payment.paymob.api_key'));
        });
    }

    public function configurePackage(Package $package): void
    {
        $package
            ->name('paymob')
            ->hasConfigFile('payment');
    }
}

<?php

namespace Smaakvoldelen\Otp;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class OtpServiceProvider extends PackageServiceProvider
{
    /**
     * Configure the package.
     */
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-otp')
            ->hasConfigFile()
            ->hasMigration('create_otps_table')
            ->hasTranslations();
    }

    /**
     * Callback when the package is booted.
     */
    public function packageBooted(): void
    {
        $this->bootingRoutes();
    }

    /**
     * Callback when the package is registered.
     */
    public function packageRegistered(): void
    {
        $this->app->bind(StatefulGuard::class, function () {
            return Auth::guard(config('otp.guard'));
        });
    }

    /**
     * Configure the routes offered by the package.
     */
    protected function bootingRoutes(): void
    {
        if (Otp::$registerRoutes) {
            Route::group([
                'namespace' => 'Smaakvoldelen\Otp\Http\Controllers',
                'domain' => config('otp.domain'),
                'prefix' => config('otp.prefix'),
            ], function () {
                $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
            });
        }
    }
}

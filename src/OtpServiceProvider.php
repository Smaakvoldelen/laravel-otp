<?php

namespace Smaakvoldelen\Otp;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Smaakvoldelen\Otp\Contracts\LockoutResponse as LockoutResponseContract;
use Smaakvoldelen\Otp\Contracts\LogoutResponse as LogoutResponseContract;
use Smaakvoldelen\Otp\Contracts\RegisterResponse as RegisterResponseContract;
use Smaakvoldelen\Otp\Contracts\SentOtpResponse as SendOtpResponseContract;
use Smaakvoldelen\Otp\Contracts\VerifyOtpFailedResponse as VerifyOtpFailedResponseContract;
use Smaakvoldelen\Otp\Contracts\VerifyOtpSuccessResponse as VerifyOtpSuccessResponseContract;
use Smaakvoldelen\Otp\Http\Responses\LockoutResponse;
use Smaakvoldelen\Otp\Http\Responses\LogoutResponse;
use Smaakvoldelen\Otp\Http\Responses\RegisterResponse;
use Smaakvoldelen\Otp\Http\Responses\SentOtpResponse;
use Smaakvoldelen\Otp\Http\Responses\VerifyOtpFailedResponse;
use Smaakvoldelen\Otp\Http\Responses\VerifyOtpSuccessResponse;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
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
            ->hasTranslations()
            ->publishesServiceProvider('OtpServiceProvider')
            ->hasInstallCommand(function (InstallCommand $command) {
                $command->hidden = false;

                return $command->publishConfigFile()
                    ->publishMigrations()
                    ->publish('support')
                    ->askToRunMigrations()
                    ->copyAndRegisterServiceProviderInApp();
            });
    }

    /**
     * Callback when the package is booted.
     */
    public function packageBooted(): void
    {
        $this->configurePublishing();
        $this->bootingRoutes();
    }

    /**
     * Callback when the package is registered.
     */
    public function packageRegistered(): void
    {
        $this->registerResponseBindings();

        $this->app->bind(StatefulGuard::class, function () {
            return Auth::guard(config('otp.guard'));
        });
    }

    /**
     * Configure the publishable resources offered by the package.
     */
    private function configurePublishing(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../resources/stubs/CreateNewUser.php.stub' => app_path('Actions/User/CreateNewUser.php'),
            ], "{$this->package->shortName()}-support");
        }
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

    /**
     * Register the response bindings.
     */
    protected function registerResponseBindings(): void
    {
        $this->app->singleton(LockoutResponseContract::class, LockoutResponse::class);
        $this->app->singleton(LogoutResponseContract::class, LogoutResponse::class);
        $this->app->singleton(SendOtpResponseContract::class, SentOtpResponse::class);
        $this->app->singleton(RegisterResponseContract::class, RegisterResponse::class);
        $this->app->singleton(VerifyOtpFailedResponseContract::class, VerifyOtpFailedResponse::class);
        $this->app->singleton(VerifyOtpSuccessResponseContract::class, VerifyOtpSuccessResponse::class);
    }
}

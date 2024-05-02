<?php

namespace Smaakvoldelen\Otp;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class OtpServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-otp')
            ->hasConfigFile()
            ->hasMigration('create_otps_table')
            ->hasTranslations();
    }
}

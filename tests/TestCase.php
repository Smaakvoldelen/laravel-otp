<?php

namespace Smaakvoldelen\Otp\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as Orchestra;
use Smaakvoldelen\Otp\OtpServiceProvider;
use Workbench\App\Models\User;

class TestCase extends Orchestra
{
    protected ?User $testUser;

    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Smaakvoldelen\\Otp\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );

        $this->setUpDatabase($this->app);
    }

    protected function getPackageProviders($app): array
    {
        return [
            OtpServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
    }

    protected function setUpDatabase(?Application $app): void
    {
        // Create migrations.
        $migration = include __DIR__.'/../database/migrations/create_otps_table.php.stub';
        $migration->up();

        // Create users tables.
        $schema = $app['db']->connection()->getSchemaBuilder();

        $schema->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
            $table->timestamps();
        });

        // Create test user.
        $this->testUser = User::create([
            'email' => 'test@localhost',
        ]);
    }
}

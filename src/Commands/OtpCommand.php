<?php

namespace Smaakvoldelen\Otp\Commands;

use Illuminate\Console\Command;

class OtpCommand extends Command
{
    public $signature = 'laravel-otp';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}

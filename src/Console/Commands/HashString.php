<?php

namespace Squadron\Base\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class HashString extends Command
{
    protected $signature = 'squadron:utils:hash {value : The string that will be hashed}';
    protected $description = 'Get hash of the string';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info(sprintf('Hash: %s', Hash::make($this->argument('value'))));
    }
}

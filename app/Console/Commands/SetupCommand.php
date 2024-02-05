<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sets up the app with all the necessary configs';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->call("migrate:fresh");
        $this->call("key:generate");
        $this->call("db:seed");
        $this->call("config:clear");
        $this->call("cache:clear");
    }
}

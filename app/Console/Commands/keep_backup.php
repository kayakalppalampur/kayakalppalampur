<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class keep_backup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'keep:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It will keep backups.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      \Log::info("Inside handle function");

        // return redirect(url('patient/query'));

    }
}


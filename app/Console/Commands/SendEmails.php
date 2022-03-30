<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\PostSender;

use App\Models\Website;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:send {site_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command send emails to subscribers';

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
     * @return int
     */
    public function handle()
    {
        PostSender::dispatch(Website::find($this->argument('site_id')));
    }
}

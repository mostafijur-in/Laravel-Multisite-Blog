<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Jobs\SendPostNotification;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:sendpostnotification {post} {--queue}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send new post notification to subscribers';

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
        $post_id   = $this->argument('post');

        if($this->option('queue')) {
            return SendPostNotification::dispatch($post_id);
        } else {
            return SendPostNotification::dispatchNow($post_id);   // use dispatch() instead as dispatchNow() is deprecated.
        }
    }
}

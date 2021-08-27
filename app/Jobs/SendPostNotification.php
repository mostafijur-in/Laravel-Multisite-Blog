<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\MailerJob;
use App\Models\Post;
use App\Models\Subscriber;
use App\Models\SubscriberEmailLog;

use Illuminate\Support\Facades\Mail;

use Exception;

class SendPostNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $post_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($post_id)
    {
        $this->post_id = $post_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $errors = [];

        if(MailerJob::where('model', 'Post')->where('model_id', $this->post_id)->where('status', 'complete')->count() > 0) {
            return false;
        }

        // Get post
        $Post   = Post::find($this->post_id);

        // Get subscribers
        $emailsSentLogs = SubscriberEmailLog::where('post_id', $this->post_id)->where('email_status', '1')->pluck('email')->toArray();
        $Subscribers    = Subscriber::whereNotIn("email", $emailsSentLogs)->get();

        $details = [
            'post_id'       => $this->post_id,
            'post_title'    => $Post->title,
            'body'          => $Post->description,
        ];

        $counter    = 0;
        foreach($Subscribers as $subscriber) {
            try{
                Mail::to($subscriber->email)->send(new \App\Mail\PostNotificationEmail($details));

                SubscriberEmailLog::updateOrCreate(
                    [
                        'post_id'   => $this->post_id,
                        'email'     => $subscriber->email,
                    ],
                    [
                        'email_status'  => 1,
                    ]
                );

                $counter++;
            } catch (Exception $e) {
                $errors[]    = $e->getMessage();
            }
        }

        if(count($Subscribers) === $counter) {
            MailerJob::updateOrCreate(
                [
                    'model'     => 'Post',
                    'model_id'  => $this->post_id,
                ],
                [
                    'status'  => 'complete',
                ]
            );
        }

        if(!empty($errors)) {
            return implode('<br />', $errors);
        }

        return "Email sent successfully";
    }
}

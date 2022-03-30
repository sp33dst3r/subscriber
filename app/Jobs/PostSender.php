<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Post;
use App\Models\Website;
use Mail;
use App\Mail\Subscription;

class PostSender implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $website = null;

    /**
     * SendEmails constructor.
     * @param Post $post
     */
    public function __construct(Website $website)
    {
        $this->website = $website;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $posts_to_send =$this->website->posts()->where('is_published', 0)->get();
        $subscriptions = $this->website->subscriptions;

        foreach ($posts_to_send as $post)
        {
            foreach($subscriptions as $sub){
                Mail::to($sub)->send(new Subscription($post));
            }
            $post->is_published = 1;
            $post->save();
        }
    }
}

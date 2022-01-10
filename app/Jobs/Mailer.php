<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\SendMessage;
use Mail;

class Mailer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $receiver_email;
    protected $detail;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($receiver_email, $data)
    {
        $this->receiver_email = $receiver_email;
        $this->detail = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->receiver_email != '') { 
            Mail::to($this->receiver_email)
            ->send(new SendMessage($this->detail));
        } else {
            info('Не найден получатель');
        }
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMessage extends Mailable
{
    use Queueable, SerializesModels;

    protected $ticket;
    protected $userName;
    protected $title;
    protected $message;
    protected $fileToPath;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->ticket = $data['ticket_id']
        $this->userName = $data['user_name'];
        $this->title = $data['subject'];
        $this->message = $data['message'];
        $this->fileToPath = $data['file_path'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $result = $mail = $this->subject("#$this->ticket - $this->title ($this->userName)")
                                ->view('emails.requests', ['text' => $this->message]);
        if (isset($this->fileToPath) && file_exists($this->fileToPath)) {
            $result = $mail->attach($this->fileToPath);
        }

        return $result;
    }
}

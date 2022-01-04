<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMessage extends Mailable
{
    use Queueable, SerializesModels;

    protected $title;
    protected $message;
    protected $file_to_path;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->title = $data['subject'];
        $this->message = $data['message'];
        $this->file_to_path = $data['file_to_path'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $result = $mail = $this->subject($this->title)->view('emails.requests', ['text' => $this->message]);
        if (isset($this->file_to_path) && file_exists($this->file_to_path)) {
            $result = $mail->attach($this->file_to_path);
        }

        return $result;
    }
}

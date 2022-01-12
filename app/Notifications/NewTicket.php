<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewTicket extends Notification implements ShouldQueue
{
    use Queueable;

    public $ticket;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(\App\Models\Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $id = $this->ticket->id;
        $subject = $this->ticket->subject;
        $author = $this->ticket->user->name;
        $message = $this->ticket->message;
        return (new MailMessage)
                    ->subject("На сайте создан новый Тикет")
                    ->line("Имя пользоватяле: $author")
                    ->line("id Тикета: $id")
                    ->line("Заголовок: $subject")
                    ->line("Текст:")
                    ->line($message)
                    ->action('Перейти на сайт', route('manager.index'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

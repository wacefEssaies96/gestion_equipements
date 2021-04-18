<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class ChatMessage extends Notification
{
    use Queueable;

    public $message;
    public $reciever;
    public function __construct($message,$reciever)
    {
       $this->message = $message;
       $this->reciever = $reciever;
    }

    public function via($notifiable)
    {
        return ['database','broadcast'];
    }

    public function toArray($notifiable)
    {
        return [
            'newMessage' => $this->message,
            'reciever' => $this->reciever,
            'user' => $notifiable
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'notification'=> $notifiable->notifications()->latest()->first()
        ]);
    }
}

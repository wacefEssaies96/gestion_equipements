<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use App\Historique;

class HistoriqueAdded extends Notification
{
    use Queueable;

    public $hist;

    public function __construct(Historique $hist)
    {
        $this->hist = $hist;
    }

    public function via($notifiable)
    {
        return ['database','broadcast'];
    }

    public function toArray($notifiable)
    {
        return [
            'createdHist' => $this->hist,
            'tech' => $notifiable
        ];
    }
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'notification'=> $notifiable->notifications()->latest()->first()
        ]);
    }
}

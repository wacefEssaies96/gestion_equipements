<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;
use App\Historique;


class AppelleNonCloture extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;
    
    public $hist;

    public function __construct(Historique $hist)
    {
        $this->hist = $hist;
        $this->delay(now('Africa/Tunis')->addMinutes(30));
    }

    public function via($notifiable)
    {
        if($this->dontSend($notifiable)) {
            return [];
        }
        return ['database','broadcast'];
    }

    public function dontSend($notifiable)
    {
        return $this->hist->appelle == 'Cloturé';
    }

    public function toArray($notifiable)
    {
        return [
            'appelleNonCloture' => $this->hist,
            'admin' => $notifiable
        ];
    }
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'notification'=> $notifiable->notifications()->latest()->first()
        ]);
    }
}

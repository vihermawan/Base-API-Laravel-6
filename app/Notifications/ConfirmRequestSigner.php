<?php
namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
class ConfirmRequestSigner extends Notification implements ShouldQueue
{
    use Queueable;
    private $information;
    
    /**
    * Create a new notification instance.
    *
    * @return void
    */
    public function __construct($information)
    {
        $this->information = $information;
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
        return (new MailMessage)
                    ->subject($this->information['subject'])
                    ->greeting($this->information['greeting'])
                    ->line($this->information['body'])
                    ->line($this->information['thank']);
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
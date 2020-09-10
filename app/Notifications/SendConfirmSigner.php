<?php
namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendConfirmSigner extends Notification implements ShouldQueue
{
    use Queueable;
    protected $token;
    private $details;
    /**
    * Create a new notification instance.
    *
    * @return void
    */
    public function __construct($token,$details)
    {
        $this->token = $token;
        $this->details = $details;
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
        //$url = url('http://localhost:3010/set-password-signer/'.$this->token.'/'); //versi lokal
        $url = url('http://178.128.208.144:3010/set-password-signer/'.$this->token.'/'); //versi dev server
        return (new MailMessage)   
        ->subject($this->details['subject']) 
        ->greeting($this->details['greeting'])
        ->line($this->details['body'])
        ->action($this->details['actionText'], url($url))
        ->line($this->details['closing']);
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
<?php
namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;

class ConfirmSignerSuccess extends Notification implements ShouldQueue
{
    use Queueable;
    private $details;
    
    /**
    * Create a new notification instance.
    *
    * @return void
    */
    public function __construct($details)
    {
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
        return (new MailMessage)
            ->greeting('Selamat bergabung dengan EventIn')
            ->subject('Kata Sandi EventIn anda telah dibuat')
            ->line(new HtmlString('<pre>Ini adalah email pemberitahuan bahwa kata sandi akun EventIn anda sebagai Penandatangan telah berhasil dibuat. </pre>'))
            ->line(new HtmlString('<pre>Jika Anda lupa kata sandi akun, silakan <a href="http://178.128.208.144:3010/forgot-password">Atur ulang Kata Sandi</a></pre>'));
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
<?php
namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;
class PasswordResetSuccess extends Notification implements ShouldQueue
{
    use Queueable;
    
    /**
    * Create a new notification instance.
    *
    * @return void
    */
    public function __construct()
    {
        //
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
        ->greeting('Password Anda Telah Berhasil Diubah')
        ->subject('Kata Sandi EventIn anda telah diubah')
        ->line(new HtmlString('<pre>Ini adalah email pemberitahuan bahwa kata sandi akun EventIn anda telah berhasil diubah. </pre>'))
        ->line(new HtmlString('<pre>Jika Anda tidak pernah melakukan perubahan kata sandi ini, silakan <a href="http://178.128.208.144:3010/forgot-password">Atur ulang Kata Sandi</a></pre>'));
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
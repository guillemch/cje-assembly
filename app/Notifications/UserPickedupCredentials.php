<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Messagebird\MessagebirdChannel;
use NotificationChannels\Messagebird\MessagebirdMessage;

class UserPickedupCredentials extends Notification
{
    use Queueable;

    /**
     * The user
     */
    protected $user;

    /**
     * The new password
     */
    protected $newPassword;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $newPassword)
    {
        $this->user = $user;
        $this->newPassword = $newPassword;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', MessagebirdChannel::class];
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
                    ->subject('[CJE] Tu contraseña para acceder a las votaciones')
                    ->markdown('mail.password', ['name' => $this->user->name, 'password' => $this->newPassword]);
    }

    /**
     * Get the SMS representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMessageBird($notifiable)
    {
        if (!$this->user->phone) return;

        return (new MessagebirdMessage)
                    ->setRecipients($this->user->phone)
                    ->setBody("Bienvenido a la Asamblea CJE\n\nTus claves de acceso para las votaciones son:\nUsuario: {$this->user->email}\nContraseña: {$this->newPassword}\n\nPuedes acceder en https://votacionescje.org");
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

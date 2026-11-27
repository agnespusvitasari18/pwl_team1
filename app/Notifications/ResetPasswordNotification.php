<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends BaseResetPassword
{
    public function toMail($notifiable): MailMessage
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        $expireMinutes = config('auth.passwords.'.config('auth.defaults.passwords').'.expire', 60);

        return (new MailMessage)
            ->subject('Atur Ulang Kata Sandi Akun PKLoka')
            ->greeting('Halo, '.$notifiable->name.'!')
            ->line('Anda menerima email ini karena kami menerima permintaan untuk mengatur ulang kata sandi akun PKLoka Anda.')
            ->action('Atur Ulang Kata Sandi', $url)
            ->line("Tautan atur ulang kata sandi ini hanya berlaku selama {$expireMinutes} menit.")
            ->line('Jika Anda tidak merasa meminta pengaturan ulang kata sandi, Anda dapat mengabaikan email ini.')
            ->salutation('Salam hormat, Tim PKLoka '.config('pkloka.school_name'));
    }
}

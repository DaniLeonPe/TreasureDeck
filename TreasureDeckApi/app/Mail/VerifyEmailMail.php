<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class VerifyEmailMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $verificationUrl;

    public function __construct(User $user)
    {
        $this->user = $user;

        // Genera la URL firmada para la verificación (ajusta el nombre de la ruta)
        $this->verificationUrl = URL::temporarySignedRoute(
            'verification.verify', // nombre de la ruta que vamos a crear
            Carbon::now()->addMinutes(60 * 24 * 7), // Link válido 7 días
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );
    }

    public function build()
    {
        return $this->subject('Verifica tu cuenta')
                    ->view('emails.verifyemail')
                    ->with([
                        'user' => $this->user,
                        'verificationUrl' => $this->verificationUrl,
                    ]);
    }
}

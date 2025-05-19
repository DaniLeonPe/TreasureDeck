<?php

namespace Tests\Unit\Mail;

use App\Mail\VerifyEmailMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class VerifyEmailMailTest extends TestCase
{    
    use RefreshDatabase;

    public function test_mail_builds_correctly()
    {
        // Crear un usuario de prueba
        $user = User::factory()->make([
            'id' => 1,
            'email' => 'test@example.com',
        ]);

        // Forzar tiempo para la URL firmada
        Carbon::setTestNow(Carbon::now());

        // Instanciar el mail
        $mail = new VerifyEmailMail($user);

        // Verificar que la URL de verificación es una URL firmada válida
        $this->assertTrue(
            URL::hasValidSignature(
                \Illuminate\Http\Request::create($mail->verificationUrl)
            )
        );

        // Construir el mail
        $builtMail = $mail->build();

        // Verificar que el asunto es correcto
        $this->assertEquals('Verifica tu cuenta', $builtMail->subject);

        // Verificar que la vista es la esperada
        $this->assertEquals('emails.verifyemail', $builtMail->view);

        // Verificar que el mail tiene las variables con usuario y URL
        $viewData = $builtMail->viewData;
        $this->assertArrayHasKey('user', $viewData);
        $this->assertArrayHasKey('verificationUrl', $viewData);
        $this->assertEquals($user, $viewData['user']);
        $this->assertEquals($mail->verificationUrl, $viewData['verificationUrl']);
    }
}

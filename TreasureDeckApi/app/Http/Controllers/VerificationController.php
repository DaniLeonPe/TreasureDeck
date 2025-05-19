<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;

class VerificationController extends Controller
{
    public function verify(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        // Validar que el hash coincida con el email del usuario
        if (!hash_equals((string) $hash, sha1($user->email))) {
            return response()->json(['message' => 'URL inválida'], 403);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Correo ya verificado']);
        }

        $user->markEmailAsVerified();

        event(new Verified($user));

        return response()->json(['message' => 'Correo verificado con éxito']);
    }
}

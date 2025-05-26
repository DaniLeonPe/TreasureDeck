<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmailMail;

/**
 * @OA\Tag(
 *     name="Auth",
 *     description="Operaciones de autenticación y registro de usuarios"
 * )
 */
class AuthApiController extends Controller
{
    /**
     * Registrar un nuevo usuario y enviar correo de verificación.
     *
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Auth"},
     *     summary="Registrar usuario",
     *     description="Registra un nuevo usuario y envía correo para verificar su email.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password"},
     *             @OA\Property(property="name", type="string", example="Juan Pérez"),
     *             @OA\Property(property="email", type="string", format="email", example="juan@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuario registrado. Verifica tu correo antes de iniciar sesión.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Usuario registrado. Verifica tu correo antes de iniciar sesión.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validación fallida",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 2,
            'email_verification_token' => Str::random(60)
            
        ]);

        Mail::to($user->email)->send(new VerifyEmailMail($user));

        return response()->json([
            'message' => 'Usuario registrado. Verifica tu correo antes de iniciar sesión.'
        ], 201);
    }

    /**
     * Login del usuario, solo si el correo está verificado.
     *
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Auth"},
     *     summary="Iniciar sesión",
     *     description="Permite iniciar sesión si el usuario está registrado y el correo verificado.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","password"},
     *             @OA\Property(property="name", type="string", example="Juan Pérez"),
     *             @OA\Property(property="password", type="string", format="password", example="secret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Token JWT generado",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciales incorrectas",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Credenciales incorrectas")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Correo no verificado",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Debes verificar tu correo antes de iniciar sesión")
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('name', $request->name)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Credenciales incorrectas'], 401);
        }

        if (is_null($user->email_verified_at)) {
            return response()->json(['error' => 'Debes verificar tu correo antes de iniciar sesión'], 403);
        }

        $token = JWTAuth::fromUser($user);
        return response()->json(['token' => $token]);
    }
}

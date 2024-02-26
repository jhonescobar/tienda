<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->middleware('jwt.auth')
            ->except([
                'login', 'logout'
            ]);
    }

    /**
     * Genera la autenticación de un usuario en específico.
     *
     * @param Illuminate\Http\Request  $request Parametros de la petición
     * @return Illuminate\Http\Response
    */
    public function login(Request $request) {

        $datos = $request->json()->all();

        if(!isset($datos['username']) || !isset($datos['password'])){
            return response()->json([
                'message' => 'Data Verification Error',
                'errors'  => [
                    'You must send the username and password.'
                ]
            ], 422);
        }

        $user = User::select([
                'usu_id',
                'usu_username',
                'usu_password',
                'estado'
            ])
            ->where('usu_username', $datos['username'])
            ->where('estado', 'ACTIVO')
            ->first();

        if(!$user) {
            return response()->json([
                'message' => 'Authentication Error',
                'errors'  => [
                    'Invalid access credentials.'
                ],
            ], 401);
        }

        // Se válidan que el usuario y contraseña sean correctos.
        if (!$access_token = auth('api')->attempt(['usu_username' => $datos['username'], 'password' => $datos['password']])) {
            return response()->json([
                'message' => 'Authentication Error',
                'errors'  => [
                    'Invalid access credentials.'
                ],
            ], 401);
        }

        return response()->json(
            compact('access_token')
        );
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->logout();

        return response()->json(['message' => 'End of Session.']);
    }

}
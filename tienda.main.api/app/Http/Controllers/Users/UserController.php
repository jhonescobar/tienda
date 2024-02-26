<?php

namespace App\Http\Controllers\Users;

use Exception;
use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {

    /**
     * Constructor.
     */
    public function __construct() {
        
    }

    /**
     * Método que crea un usuario.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $arrErrores = [];

        $datos = $request->json()->all();

        if(!isset($datos['username']) || !isset($datos['password'])){
            return response()->json([
                'message' => 'Data Verification Error',
                'errors'  => [
                    'You must send the username and password.'
                ]
            ], 422);
        }

        $request = new Request();
        $request->merge([
            'usu_username' => $datos['username'],
            'usu_password' => $datos['password'],
            'estado'   => 'ACTIVO'
        ]);

        $arrErrores = $this->validaciones($request, 'CREAR');
        if(count($arrErrores) > 0){
            return response()->json([
                'message' => 'Data Validation Error',
                'errors'  => $arrErrores
            ], 422);
        }

        try {
            $userCreate = User::create($request->all());
            if(!$userCreate){
                $arrErrores[] = 'Could not create user.';
            }
        } catch (\Exception $e) {
            $arrErrores[] = $e->getMessage();
        }

        if(count($arrErrores) > 0) {
            return response()->json([
                'message' => 'Error creating user',
                'errors'  => $arrErrores
            ], 422);
        }

        return response()->json([
            'message' => 'User created succesfully.'
        ], 201);
    }

    /**
     * Método que realiza las validaciones al momento de crear o actualizar un usuario.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $metodo CREAR|ACTUALIZAR
     * @param \App\Models\User $usuario Modelo Usuario
     */
    public function validaciones(Request $request, string $metodo, User $usuario = null) {

        $arrErrores = [];

        if($metodo == "CREAR") {
            // Validación conforme a reglas del modelo
            $objValidatorUser = Validator::make($request->all(), User::$rules, User::$messagesValidators);
            if($objValidatorUser->fails()) {
                return $objValidatorUser->messages()->all();
            }
            // Se válida que el usuario no exista con la misma identificación.
            $usuario = User::select('usu_username')
                ->where('usu_username', $request->usu_username)
                ->first();

            if($usuario){
                $arrErrores[] = "User already exists";
            }
        }

        return $arrErrores;
    }

    /**
     * Método que retorna la información del usuario solicitado.
     *
     * @param  \App\Models\User $usuario Modelo Usuario
     * @return \Illuminate\Http\Response
     */
    public function show(User $usuario) {
        return response()->json([
            'data' => $usuario
        ], 200);
    }

}

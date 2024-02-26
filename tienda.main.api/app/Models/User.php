<?php

namespace App\Models;

use OwenIt\Auditing\Auditable;
use App\Traits\TimestampSerializableTrait;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class User extends Authenticable implements AuditableContract, JWTSubject {
    use Auditable, TimestampSerializableTrait;

    protected $connection = 'mysql';

    // Tabla relacionada con el modelo
    protected $table = 'users';

    // Llave primaria de la tabla
    protected $primaryKey = 'usu_id';

    public static $rules = [
        'usu_username'  => 'required|string|max:255',
        'usu_password'  => 'required|string|max:200',
        'estado'        => 'required|string|in:ACTIVO,INACTIVO'         
    ];

    public static $rulesUpdate = [
        'usu_username'  => 'required|email|max:255',
        'usu_password'  => 'nullable|string|max:200',
        'estado'        => 'nullable|string|in:ACTIVO,INACTIVO'
    ];

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'usu_id',
        'usu_username',
        'usu_password',
        'estado'
    ];

    /**
     * The attributes that should be visible in arrays
     * @var array
     */
    protected $visible = [
        'usu_id',
        'usu_username',
        'estado',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Se sobrescribe la función getAuthPassword 
     * Esto es debido a que la tabla auth_usuarios el campo password se llama usu_password
     *
     * @return string
     */
    public function getAuthPassword() {
        return $this->usu_password;
    }

    /**
     * Se sobrescribe la función getEmailForPasswordReset
     * Esto es debido a que la tabla auth_usuarios el campo email se llama usu_email
     */
    public function getEmailForPasswordReset() {
        return $this->usu_email;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [
            'id'     => $this->usu_id,
            'nombre' => $this->usu_username
        ];
    }

    /**
     * Mensajes personalizados conforme a las reglas de validación.
     *
     * @var array
     */
    public static $messagesValidators = [
        'usu_id.required'             => 'The User ID field is required.',
        'usu_username.required'       => 'Username is required.',
        'usu_username.filled'         => 'The username cannot be empty.',
        'usu_username.max'            => 'The username cannot exceed 255 characters.',
        'usu_password.required'       => 'Password is required.',
        'usu_password.max'            => 'The password cannot exceed 200 characters.',
        'estado.required'             => 'The status is required.',
        'estado.string'               => 'The state must be of type string.',
        'estado.in'                   => 'The status value must be ACTIVE or INACTIVE.'
    ];
}

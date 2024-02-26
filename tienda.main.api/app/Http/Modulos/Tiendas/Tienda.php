<?php

namespace App\Http\Modulos\Tiendas;

use App\Http\Modulos\MainModel;

class Tienda extends MainModel {
    
    /**
     * Tabla relacionada con el modelo.
     *
     * @var string
     */
    protected $table = 'tienda';

    /**
     * Llave primaria de la tabla.
     *
     * @var string
     */
    protected $primaryKey = 'tie_id';

    /**
     * Reglas de validación para el recurso crear.
     *
     * @var array
     */
    public static $rules = [
        'tie_nombre'         => 'required|string|max:255',
        'usuario_creacion'   => 'nullable|numeric',
        'estado'             => 'required|string|in:ACTIVO,INACTIVO',
    ];

    /**
     * Reglas de validación para el recurso actualizar.
     * 
     * @var array
     */
    public static $rulesUpdate = [
        'tie_nombre'         => 'nullable|string|max:255',
        'usuario_creacion'   => 'nullable|numeric',
        'estado'             => 'nullable|string|in:ACTIVO,INACTIVO'
    ];
  
    /**
     * Los atributos que deberían estar visibles.
     * 
     * @var array
     */
    protected $visible = [
        'tie_id',
        'tie_nombre',
        'usuario_creacion',
        'estado',
        'updated_at'
    ];

    /**
     * Los atributos que son asignables en masa.
     * 
     * @var array
     */
    protected $fillable = [
        'tie_nombre',
        'usuario_creacion',
        'estado'
    ];

    /**
     * Mensajes personalizados conforme a las reglas de validación.
     *
     * @var array
     */
    public static $messagesValidators = [
        'tie_nombre.required'           => 'Store name is required.',
        'tie_nombre.string'             => 'The store name must be of type string.',
        'tie_nombre.max'                => 'The store name cannot exceed 255 characters.',
        'usuario_creacion.numeric'      => 'The creation user must be a numeric type.',
        'estado.required'               => 'The status is required.',
        'estado.string'                 => 'The state must be of type string.'
    ];

    /**
     * Scope para buscar en una lista de tiendas un valor determinado.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $buscar Valor a buscar
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBuscar($query, $buscar){
        if($buscar){
            return $query->where('tie_nombre', 'like', '%'.$buscar.'%')
                ->orWhere('updated_at', 'like', '%'.$buscar.'%')
                ->orWhere('estado', 'like', '%'.$buscar.'%');
        }
    }

    /**
     * Scope para ordenar una lista de tiendas por una columna determinada.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $columna Nombre de la columna de la tabla
     * @param mixed $orden Tipo de ordenamiento ASC|DESC
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdenar($query, $columna, $orden){
        if($columna && $orden){
            return $query->orderBy($columna, $orden);
        }

        return $query->orderBy('updated_at', 'DESC');
    }
}

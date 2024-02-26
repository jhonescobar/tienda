<?php

namespace App\Http\Modulos\Articulos;

use App\Http\Modulos\MainModel;
use App\Http\Modulos\Tiendas\Tienda;

class Articulo extends MainModel {
    
    /**
     * Tabla relacionada con el modelo.
     *
     * @var string
     */
    protected $table = 'articulo';

    /**
     * Llave primaria de la tabla.
     *
     * @var string
     */
    protected $primaryKey = 'art_id';

    /**
     * Reglas de validación para el recurso crear.
     *
     * @var array
     */
    public static $rules = [
        'tie_id'             => 'required|numeric',
        'art_nombre'         => 'required|string|max:255',
        'art_precio'         => 'required|numeric',
        'usuario_creacion'   => 'nullable|numeric',
        'estado'             => 'required|string|in:ACTIVO,INACTIVO',
    ];

    /**
     * Reglas de validación para el recurso actualizar.
     * 
     * @var array
     */
    public static $rulesUpdate = [
        'tie_id'             => 'nullable|numeric',
        'art_nombre'         => 'nullable|string|max:255',
        'art_precio'         => 'nullable|numeric',
        'usuario_creacion'   => 'nullable|numeric',
        'estado'             => 'nullable|string|in:ACTIVO,INACTIVO'
    ];
  
    /**
     * Los atributos que deberían estar visibles.
     * 
     * @var array
     */
    protected $visible = [
        'art_id',
        'tie_id',
        'art_nombre',
        'art_precio',
        'usuario_creacion',
        'estado',
        'updated_at',
        'getTienda'
    ];

    /**
     * Los atributos que son asignables en masa.
     * 
     * @var array
     */
    protected $fillable = [
        'tie_id',
        'art_nombre',
        'art_precio',
        'estado'
    ];

    /**
     * Mensajes personalizados conforme a las reglas de validación.
     *
     * @var array
     */
    public static $messagesValidators = [
        'tie_id.required'               => 'Store ID is required.',
        'tie_id.numeric'                => 'Store ID must be numerical.',
        'art_nombre.required'           => 'The item name is required.',
        'art_nombre.string'             => 'The item name must be of type string.',
        'art_nombre.max'                => 'The article name cannot exceed 255 characters..',
        'art_precio.required'           => 'The price of the item is required.',
        'art_precio.numeric'            => 'The price of the item must be numerical.',
        'usuario_creacion.numeric'      => 'The creation user must be a numeric type.',
        'estado.required'               => 'The status is required.',
        'estado.string'                 => 'The state must be of type string.'
    ];

    /**
     * Obtiene la tienda.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTienda() {
        return $this->belongsTo(Tienda::class,'tie_id','tie_id');
    }

    /**
     * Scope para buscar en una lista de articulos un valor determinado.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $buscar Valor a buscar
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBuscar($query, $buscar){
        if($buscar){
            return $query->whereHas('getTienda', function($queryHas) use ($buscar) {
                    $queryHas->where('tie_nombre', 'like', "%{$buscar}%");
                })
                ->orWhere('art_nombre', 'like', '%'.$buscar.'%')
                ->orWhere('art_precio', 'like', '%'.$buscar.'%')
                ->orWhere('updated_at', 'like', '%'.$buscar.'%')
                ->orWhere('estado', 'like', '%'.$buscar.'%');
        }
    }

    /**
     * Scope para ordenar una lista de articulos por una columna determinada.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $columna Nombre de la columna de la tabla
     * @param mixed $orden Tipo de ordenamiento ASC|DESC
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdenar($query, $columna, $orden){
        if($columna && $orden){
            switch ($columna) {
                case 'tie_nombre':
                    return $query->join('tienda as tienda', 'tienda.tie_id', '=', 'articulo.tie_id')
                        ->orderBy('tienda.tie_nombre', $orden);
                break;
                default:
                    return $query->orderBy($columna, $orden);
                break;
            }
            return $query->orderBy($columna, $orden);
        }

        return $query->orderBy('updated_at', 'DESC');
    }
}

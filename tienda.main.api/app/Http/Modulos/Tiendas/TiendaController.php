<?php

namespace App\Http\Modulos\Tiendas;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Modulos\Tiendas\Tienda;
use App\Http\Modulos\Articulos\Articulo;

class TiendaController extends Controller {

    /**
     * Constructor.
     */
    public function __construct() {
        // $this->middleware([
        //     'jwt.auth',
        //     'jwt.refresh'
        // ]);
    }

    /**
     * Crea una tienda.
     *
     * @param string $nombre Nombre de la tienda
     * @return Response Respuesta Http
     */
    public function store($nombre) {
        $request = new Request();
        $request->merge([
            'tie_nombre'       => $nombre,
            'usuario_creacion' => null,
            'estado'           => 'ACTIVO'
        ]);

        $objValidator = Validator::make($request->all(), Tienda::$rules, Tienda::$messagesValidators);
        if($objValidator->fails()){
            return response()->json([
                'message' => 'Data Validation Error',
                'errors' => $objValidator->messages()->all()
            ], 409);
        }

        // Verifica que la tienda no exista.
        $tienda = Tienda::select('estado')
            ->where('tie_nombre', $nombre)
            ->first();

        if($tienda) {
            return response()->json([
                'message' => 'Data Validation Error',
                'errors'  => [
                    'The store ['.$nombre.'], it already exists.'
                ]
            ], 409);
        }

        try {
            $tiendaCreate = Tienda::create($request->all());
            if(!$tiendaCreate){
                $arrErrores[] = 'Could not create store.';
            }
        } catch (\Exception $e) {
            $arrErrores[] = $e->getMessage();
        }

        if(isset($arrErrores) && !empty($arrErrores)) {
            return response()->json([
                'message' => 'Error creating store',
                'errors' => $arrErrores
            ], 409);
        }

        return response()->json([
            'id'    => $tiendaCreate->tie_id,
            'name'  => $tiendaCreate->tie_nombre,
            'items' => []
        ], 201);
    }

    /**
     * Obtiene la información de la tienda solicitada.
     *
     * @param string $tienda Nombre de la tienda a buscar
     * @return Response Respuesta Http
     */
    public function show($tienda) {
        // Verifica que la tienda no exista.
        $tiendaModel = Tienda::where('tie_nombre', $tienda)
            ->first();

        if(!$tiendaModel) {
            return response()->json([
                'message' => 'Data Validation Error',
                'errors'  => [
                    'The store ['.$tienda.'], does not exist.'
                ]
            ], 409);
        }

        $articulos = Articulo::where('tie_id', $tiendaModel->tie_id)
            ->get()
            ->map(function($articulo) {
                $collection = [];
                $collection['id']       = $articulo->art_id;
                $collection['name']     = $articulo->art_nombre;
                $collection['price']    = $articulo->art_precio;
                $collection['store_id'] = $articulo->tie_id;
                return collect($collection);
            })
            ->values();

        return response()->json([
            'id'    => $tiendaModel->tie_id,
            'name'  => $tiendaModel->tie_nombre,
            'items' => $articulos
        ], 200);
    }

    /**
     * Elimina una tienda.
     *
     * @param string $tienda Nombre de la tienda a eliminar
     * @return Response Respuesta Http
     */
    public function destroy($tienda) {
        // Verifica que la tienda exista.
        $tiendaModel = Tienda::where('tie_nombre', $tienda)
            ->first();

        if(!$tiendaModel) {
            return response()->json([
                'message' => 'Data Validation Error',
                'errors'  => [
                    'The store ['.$tienda.'], does not exist.'
                ]
            ], 409);
        }

        $articulos = Articulo::where('tie_id', $tiendaModel->tie_id)
            ->get()
            ->map(function($articulo) {
                $articulo->delete();
            });

        Tienda::find($tiendaModel->tie_id)->delete();

        return response()->json([
            'message' => 'Store deleted'
        ], 200);
    }

    /**
     * Lista todas las tiendas.
     *
     * @return Response Respuesta Http
     */
    public function list() {
        $registros = Tienda::get()
            ->map(function($registro) {
                $articulos = Articulo::where('tie_id', $registro->tie_id)
                    ->get()
                    ->map(function($articulo) {
                        $collection = [];
                        $collection['id']       = $articulo->art_id;
                        $collection['name']     = $articulo->art_nombre;
                        $collection['price']    = $articulo->art_precio;
                        $collection['store_id'] = $articulo->tie_id;
                        return collect($collection);
                    })
                    ->values();

                $collection = [];
                $collection['id']    = $registro->tie_id;
                $collection['name']  = $registro->tie_nombre;
                $collection['items'] = $articulos;
                return collect($collection);
            })
            ->values();

        return response()->json([
            'stores' => $registros
        ], 200);
    }
}

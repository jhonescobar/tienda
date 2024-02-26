<?php

namespace App\Http\Modulos\Articulos;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Modulos\Tiendas\Tienda;
use App\Http\Modulos\Articulos\Articulo;

class ArticuloController extends Controller {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->middleware([
            'jwt.auth',
            // 'jwt.refresh'
        ])
        ->only(['show']);
    }

    /**
     * Crea un articulo.
     *
     * @param string $nombre Nombre del articulo
     * @return Response Respuesta Http
     */
    public function store(Request $request, $nombre) {
        $arrErrores = [];

        $datos = $request->json()->all();

        if (!isset($datos['store_id'])) {
            $arrErrores[] = "You must send the store ID.";
        }
        if (!isset($datos['price'])) {
            $arrErrores[] = "You must send the value of the item.";
        }
        if (count($arrErrores) > 0) {
            return response()->json([
                'message' => 'Data Validation Error',
                'errors' => $arrErrores
            ], 422);
        }

        $request = new Request();
        $request->merge([
            'tie_id'     => $datos['store_id'],
            'art_nombre' => $nombre,
            'art_precio' => $datos['price'],
            'estado'     => 'ACTIVO'
        ]);

        $objValidator = Validator::make($request->all(), Articulo::$rules, Articulo::$messagesValidators);
        if($objValidator->fails()){
            return response()->json([
                'message' => 'Data Validation Error',
                'errors' => $objValidator->messages()->all()
            ], 409);
        }

        // Verifica que la tienda exista.
        $tienda = Tienda::select('estado')
            ->where('tie_id', $request->tie_id)
            ->first();

        if(!$tienda) {
            return response()->json([
                'message' => 'Data Validation Error',
                'errors'  => [
                    'The store ['.$request->tie_id.'], does not exist.'
                ]
            ], 409);
        }

        // Verifica que el articulo no exista.
        $articulo = Articulo::select('estado')
            ->where('art_nombre', $request->art_nombre)
            ->first();

        if($articulo) {
            return response()->json([
                'message' => 'Data Validation Error',
                'errors'  => [
                    'The item ['.$request->art_nombre.'], it already exists.'
                ]
            ], 409);
        }

        try {
            $articuloCreate = Articulo::create($request->all());
            if(!$articuloCreate){
                $arrErrores[] = 'Could not create item.';
            }
        } catch (\Exception $e) {
            $arrErrores[] = $e->getMessage();
        }

        if(isset($arrErrores) && !empty($arrErrores)) {
            return response()->json([
                'message' => 'Error creating item',
                'errors' => $arrErrores
            ], 409);
        }

        return $this->returnJsonResponse($articuloCreate, 201);
    }

    /**
     * Retorna la respuesta json.
     *
     * @param object $articulo Informacion del articulo
     * @return Response Respuesta Http
     */
    private function returnJsonResponse($articulo, $statusCode = 200) {
        return response()->json([
            'id'        => $articulo->art_id,
            'name'      => $articulo->art_nombre,
            'price'     => $articulo->art_precio,
            'store_id'  => $articulo->tie_id
        ], $statusCode);
    }

    /**
     * Obtiene la información del articulo solicitado.
     *
     * @param string $articulo Nombre del articulo a buscar
     * @return Response Respuesta Http
     */
    public function show($articulo) {
        // Verifica que el articulo no exista.
        $articuloModel = Articulo::where('art_nombre', $articulo)
            ->first();

        if(!$articuloModel) {
            return response()->json([
                'message' => 'Data Validation Error',
                'errors'  => [
                    'The item ['.$articulo.'], does not exist.'
                ]
            ], 409);
        }

        return $this->returnJsonResponse($articuloModel);
    }

    /**
     * Actualiza un articulo.
     *
     * @param Request $request Parámetros de la petición
     * @param string $nombre Nombre del articulo
     * @return Response Respuesta Http
     */
    public function update(Request $request, $nombre) {
        $arrErrores = [];

        $datos = $request->json()->all();

        if (!isset($datos['store_id'])) {
            $arrErrores[] = "You must send the store ID.";
        }
        if (!isset($datos['price'])) {
            $arrErrores[] = "You must send the value of the item.";
        }
        if (count($arrErrores) > 0) {
            return response()->json([
                'message' => 'Data Validation Error',
                'errors' => $arrErrores
            ], 422);
        }

        $request = new Request();
        $request->merge([
            'tie_id'     => $datos['store_id'],
            'art_nombre' => $nombre,
            'art_precio' => $datos['price']
        ]);

        $objValidator = Validator::make($request->all(), Articulo::$rulesUpdate, Articulo::$messagesValidators);
        if($objValidator->fails()){
            return response()->json([
                'message' => 'Data Validation Error',
                'errors' => $objValidator->messages()->all()
            ], 409);
        }

        // Verifica que el articulo exista.
        $articulo = Articulo::where('art_nombre', $request->art_nombre)
            ->first();

        if(!$articulo) {
            return response()->json([
                'message' => 'Data Validation Error',
                'errors'  => [
                    'The item ['.$request->art_nombre.'], does not exist.'
                ]
            ], 409);
        }

        try {
            $articuloUpdate = $articulo->update($request->all());
            if (!$articuloUpdate) {
                $arrErrores[] = 'Could not update item ['.$request->art_nombre.']';
            }
        } catch (\Exception $e) {
            $arrErrores[] = $e->getMessage();
        }

        if(isset($arrErrores) && !empty($arrErrores)) {
            return response()->json([
                'message' => 'Error updating item',
                'errors' => $arrErrores
            ], 409);
        }

        return $this->returnJsonResponse($articulo);
    }

    /**
     * Elimina un articulo.
     *
     * @param string $nombre Nombre del articulo a eliminar
     * @return Response Respuesta Http
     */
    public function destroy($nombre) {
        // Verifica que el articulo exista.
        $articuloModel = Articulo::where('art_nombre', $nombre)
            ->first();

        if(!$articuloModel) {
            return response()->json([
                'message' => 'Data Validation Error',
                'errors'  => [
                    'The item ['.$nombre.'], does not exist.'
                ]
            ], 409);
        }

        Articulo::find($articuloModel->art_id)->delete();

        return response()->json([
            'message' => 'Item deleted'
        ], 200);
    }

    /**
     * Lista todos los articulos.
     *
     * @return Response Respuesta Http
     */
    public function list() {
        $registros = Articulo::get()
            ->map(function($registro) {
                $collection = [];
                $collection['id']       = $registro->art_id;
                $collection['name']     = $registro->art_nombre;
                $collection['price']    = $registro->art_precio;
                $collection['store_id'] = $registro->tie_id;
                return collect($collection);
            })
            ->values();

        return response()->json([
            'items' => $registros
        ], 200);
    }
}

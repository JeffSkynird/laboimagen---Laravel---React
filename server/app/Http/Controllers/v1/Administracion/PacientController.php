<?php

namespace App\Http\Controllers\v1\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Pacient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use \Validator;

class PacientController extends Controller
{
    /**
     * Función para crear nuevos people
     * @param Request $request 
     * @return json
     */
    public function create(Request $request)
    {
        DB::beginTransaction();
        try {
            $params = $request->all();

            $vacios = Validator::make($request->all(), [
                'dni' => 'required',
                'names' => 'required',
                'last_names' => 'required',
                'address' => 'required',
                'phone' => 'required',
                'email' => 'required',
                'born_date'=>'required'
            ]);
            if ($vacios->fails()) {
                return response([
                    'message' => "No debe dejar campos vacíos",
                    'fields' => $request->all(),
                    'type' => "error",
                ]);
            }
            $params['user_id'] = Auth::id();
            Pacient::create($params);
            DB::commit();
            return response()->json([
                "status" => "200",
                "message" => 'Registro exitoso',
                "type" => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "status" => "500",
                "message" => $e->getMessage(),
                "type" => 'error'
            ]);
        }
    }
    /**
     * Función para obtener los datos de un people
     * @param int $id 
     * @return json
     */
    public function show($id)
    {
        $data = Pacient::find($id);
        return response()->json([
            "status" => "200",
            "message" => 'Datos obtenidos con éxito',
            "data" => $data,
            "type" => 'success'
        ]);
    }
    /**
     * Función para modificar los datos de un people
     * @param int $id, Request $request 
     * @return json
     */
    public function update(Request $request, $id)
    {
        $vacios = Validator::make($request->all(), [
            'dni' => 'required',
            'names' => 'required',
            'last_names' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'born_date'=>'required'
        ]);
        if ($vacios->fails()) {
            return response([
                'message' => "No deje campos vacíos",
                'type' => "error",
            ]);
        }
        DB::beginTransaction();
        try {
            Pacient::find($id)->update($request->all());
            DB::commit();
            return response()->json([
                "status" => "200",
                "message" => 'Modificación exitosa',
                "type" => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "status" => "500",
                "message" => $e->getMessage(),
                "type" => 'error'
            ]);
        }
    }

    /**
     * Función para eliminar un people
     * @param  int $id
     * @return json
     */
    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $data = Pacient::find($id);
            if(is_null($data)){
                return response()->json([
                    "status" => "404",
                    "message" => 'No se encontró el registro',
                    "type" => 'error'
                ]);
            }
            $data->delete();
            DB::commit();
            return response()->json([
                "status" => "200",
                "message" => 'Eliminación exitosa',
                "type" => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "status" => "500",
                "message" => $e->getMessage(),
                "type" => 'error'
            ]);
        }
    }
    /**
     * Función para obtener todos los people
     * @return json
     */
    public function index()
    {
        $data = Pacient::with('orders')->orderBy('id', 'desc')->get();
        return response()->json([
            "status" => "200",
            "data" => $data,
            "message" => 'Listado exitoso',
            "type" => 'success'
        ]);
    }
}

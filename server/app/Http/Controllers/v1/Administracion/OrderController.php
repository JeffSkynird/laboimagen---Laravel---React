<?php

namespace App\Http\Controllers\v1\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use \Validator;

class OrderController extends Controller
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
                'pacient_id' => 'required',
                'exams' => 'required'
            ]);
            if ($vacios->fails()) {
                return response([
                    'message' => "No debe dejar campos vacíos",
                    'fields' => $request->all(),
                    'type' => "error",
                ]);
            }
            $params['user_id'] = Auth::id();
            $order = Order::create($params);
            foreach ($params['exams'] as $exam) {
                $obj = [
                    'exam_id' => $exam['id'],
                    'user_id' => Auth::id()
                ];
                $order->plannings()->create($obj);
            }
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
        $data = Exam::join('plannings', 'plannings.exam_id', '=', 'exams.id')
        ->join('categories', 'categories.id', '=', 'exams.category_id')
        ->where('plannings.order_id', $id)
        ->selectRaw('exams.*,exams.name as exam_name,categories.name as category_name')
        ->get();
  
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
            'pacient_id' => 'required'
        ]);
        if ($vacios->fails()) {
            return response([
                'message' => "No deje campos vacíos",
                'type' => "error",
            ]);
        }
        DB::beginTransaction();
        try {
            $order = Order::find($id);
           /*  if ($order->planning->count() > 0) {
                return response()->json([
                    "status" => "500",
                    "message" => 'No se puede editar una orden con resultados',
                    "type" => 'error'
                ]);
            } */
            $order->update($request->all());
            foreach ($request['exams'] as $exam) {
                $obj = [
                    'exam_id' => $exam['id'],
                    'user_id' => Auth::id()
                ];
                $planning = $order->plannings()->where('exam_id', $exam['id'])->first();
                if ($planning!=null) {
                    //tiene un planning con el exam_id
                   
                    
                 //   $order->plannings()->updateOrCreate(['id' => $exam['id']], $obj);
                } else {
                   
                    $order->plannings()->create($obj);
                }
            }
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
            $data = Order::find($id);
            if (is_null($data)) {
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
        $data = Order::with('pacient')->orderBy('id', 'desc')->get(); 
        return response()->json([
            "status" => "200",
            "data" => $data,
            "message" => 'Listado exitoso',
            "type" => 'success'
        ]);
    }
    public function pacientOrders($id)
    {
        $data = Order::with('pacient')->where('pacient_id', $id)->orderBy('id', 'desc')->get();
        return response()->json([
            "status" => "200",
            "data" => $data,
            "message" => 'Listado exitoso',
            "type" => 'success'
        ]);
    }



    
}

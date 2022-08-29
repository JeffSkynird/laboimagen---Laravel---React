<?php

namespace App\Http\Controllers\v1\Reporte;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Order;
use App\Models\Pacient;
use App\Models\Planning;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function kpis()
    {
        $pacients = Pacient::count();
        $orders1 = Order::whereHas('plannings', function ($query) {
            return $query->where('is_complete', '=', true);
        })->count();
        $orders2 = Order::whereHas('plannings', function ($query) {
            return $query->where('is_complete', '=', false);
        })->count();

        return response()->json([
            "status" => "200",
            "message" => 'Datos obtenidos con éxito',
            "data" => [
                "pacients" => $pacients,
                "orders1" => $orders1,
                "orders2" => $orders2,
            ],
            "type" => 'success'
        ]);
    }
    public function graph1()
    {
        $orders1 = Order::whereHas('plannings', function ($query) {
            return $query->where('is_complete', '=', true);
        })->whereBetween('created_at', 
        [Carbon::now()->subMonth(3), Carbon::now()]
            )->get()->sortBy(function ($item) {
                return $item->created_at;
           })->groupBy(function ($date) {
            return Carbon::parse($date->created_at)->format('F');
        })->map->count();

        return response()->json([
            "status" => "200",
            "message" => 'Datos obtenidos con éxito',
            "data" =>   $orders1,
            "type" => 'success'
        ]);
    }
    public function graph2()
    {
        $orders1 = Exam::whereHas('plannings', function ($query) {
            return $query->where('is_complete', '=', true);
        })->get()->groupBy('name');
        $orders2 = Planning::with('exam')->where('is_complete',true)->get()->groupBy('exam.name')->map(function ($row) {
            return $row->count('exam.name');
         })->sortDesc()->take(3);  

        return response()->json([
            "status" => "200",
            "message" => 'Datos obtenidos con éxito',
            "data" =>   $orders2,
            "type" => 'success'
        ]);
    }
}

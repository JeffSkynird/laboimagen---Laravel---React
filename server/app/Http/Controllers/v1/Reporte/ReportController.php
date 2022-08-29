<?php

namespace App\Http\Controllers\v1\Reporte;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Pacient;
use Illuminate\Http\Request;


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
                "pacients" =>$pacients,
                "orders1" => $orders1,
                "orders2" => $orders2,
            ],
            "type" => 'success'
        ]);
    }
}

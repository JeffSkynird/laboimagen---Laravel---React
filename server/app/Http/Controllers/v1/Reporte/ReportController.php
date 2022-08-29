<?php

namespace App\Http\Controllers\v1\Reporte;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function kpis()
    {
        $pacients = Pacient::count();
        $orders1 = Order::count();
        $orders2 = Result::count();
        
        return response()->json([
            "status" => "200",
            "message" => 'Datos obtenidos con éxito',
            "data" => $data,
            "type" => 'success'
        ]);
    }
}

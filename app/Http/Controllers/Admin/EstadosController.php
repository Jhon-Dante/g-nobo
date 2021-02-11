<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Estado;
use App\Models\Municipality;


class EstadosController extends Controller
{
    public $timestamps = false;

    public function index(Request $request)
    {
        $estados = Estado::where('pais_id', VENEZUELA_ID)
            ->orderBy('nombre', 'ASC')
            ->get();

        return view('admin.estados.index', [
            'estados' => $estados
        ]);
    }

    public function show($estadoId)
    {
        $estado = Estado::find($estadoId);

        $estados = Estado::where('pais_id', VENEZUELA_ID)->get();

        $municipalities = Municipality::with('estado', 'parishes')
            ->where('status', true)
            ->where('estado_id', $estadoId)
            ->latest()
            ->get();

        return  view('admin.municipalities.index', [
            'municipalities' => $municipalities,
            'estados' => $estados,
            'estadoId' => $estadoId,
            "estado" => $estado
        ]);
    }

    public function free($estadoId)
    {
        $estado = Estado::find($estadoId);
        $estado->free = !$estado->free;
        $estado->save();

        Municipality::where('estado_id', $estadoId)->update(['free' => $estado->free]);

        return $estado;
    }

    public function status($estadoId)
    {
        $estado = Estado::find($estadoId);
        $estado->status = !$estado->status;
        $estado->save();
        return $estado;
    }
}

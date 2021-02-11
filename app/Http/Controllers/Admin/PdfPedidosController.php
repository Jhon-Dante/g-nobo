<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Purchase;
use Illuminate\Http\Request;
use DB;
use PDF;

class PdfPedidosController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function pdfview(Purchase $purchase)
    {
        $file = PDF::loadView('admin.pdf.pedido', [
            'compra' => $purchase,
            'user' => $purchase->user,
        ]);

        $file_name = 'REPORTE-' . \Carbon\Carbon::now()->format('d-m-Y') . strtoupper(str_random(10));

        return $file->stream($file_name . '.pdf', array("Attachment" => false))->header('Content-Type', 'application/pdf');
    }
}

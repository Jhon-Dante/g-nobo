<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Product;
use Carbon\Carbon;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index($report)
    {
        return view('admin.reports.' . $report);
    }

    public function purchases($type, $from, $to)
    {
        $query = $this->parseTypeQuery($type);

        function sumFormat($field, $alias)
        {
            return "SUM($field) AS $alias";
        }

        return Purchase::select(
            DB::raw($query . ' AS label'),
            DB::raw(sumFormat('purchases.subtotal_bruto', 'purchases')),
            DB::raw(sumFormat('purchases.subtotal_bruto * exchange_rates.change', 'purchases_bs')),
            DB::raw(sumFormat('purchases.subtotal', 'purchases_neta')),
            DB::raw(sumFormat('purchases.subtotal * exchange_rates.change', 'purchases_neta_bs')),
            DB::raw(sumFormat('purchases.utilidad_bruta', 'utility')),
            DB::raw(sumFormat('purchases.utilidad_bruta * exchange_rates.change', 'utility_bs')),
            DB::raw(sumFormat('purchases.utilidad', 'utility_neta')),
            DB::raw(sumFormat('purchases.utilidad * exchange_rates.change', 'utility_neta_bs')),
            DB::raw("ROUND(SUM(purchases.utilidad_bruta) / SUM(purchases.subtotal_bruto) * 100, 2) AS utility_percentage"),
            DB::raw("ROUND(SUM(purchases.utilidad) / SUM(purchases.subtotal) * 100, 2) AS utility_neta_percentage")
        )
            ->whereBetween(DB::raw('date(purchases.created_at)'), [
                $from,
                $to
            ])
            ->join('exchange_rates', 'exchange_rates.id', '=', 'purchases.exchange_rate_id')
            ->groupBy(DB::raw($query))
            ->where('status', Purchase::STATUS_COMPLETED)
            ->get();
    }

    public function orders($from, $to)
    {
        $pending = Purchase::STATUS_ONHOLD;
        $processing = Purchase::STATUS_PROCESSING;
        $completed = Purchase::STATUS_COMPLETED;

        return Purchase::select(
            DB::raw('COUNT(purchases.id) as orders'),
            DB::raw('date(created_at) as label'),
            DB::raw("SUM(CASE WHEN status = $pending THEN 1 ELSE 0 END) as pending"),
            DB::raw("SUM(CASE WHEN status = $processing THEN 1 ELSE 0 END) as processing"),
            DB::raw("SUM(CASE WHEN status = $completed THEN 1 ELSE 0 END) as completed")
        )
            ->whereBetween(DB::raw('date(purchases.created_at)'), [
                $from,
                $to
            ])
            ->groupBy(DB::raw('date(purchases.created_at)'))
            ->whereIn('status', [$pending, $processing, $completed])
            ->get();
    }

    public function getUnitType($unit){
        switch ($unit) {
            case 1:
                return 'Gr';
            case 2:
                return 'Kg';
            case 3:
                return 'Ml';
            case 4:
                return 'L';
            case 5:
                return 'Cm';
            default:
                return null;
        }
    }

    public function products($from, $to)
    {
        $products = Product::select(
            'products.name',
            'purchase_details.product_amount_id',
            'product_amount.unit',
            'product_amount.presentation',
            DB::raw('SUM(purchase_details.quantity) as purchases_number')
        )
            ->whereBetween(DB::raw('date(purchases.created_at)'), [
                $from,
                $to
            ])
            ->join('product_colors', 'product_colors.product_id', '=', 'products.id')
            ->join('product_amount', 'product_amount.product_color_id', '=', 'product_colors.id')
            ->join('purchase_details', 'purchase_details.product_amount_id', '=', 'product_amount.id')
            ->join('purchases', 'purchases.id', '=', 'purchase_details.purchase_id')
            ->groupBy('products.id')
            ->where('purchases.status', Purchase::STATUS_COMPLETED)
            ->orderBy(DB::raw('SUM(purchase_details.quantity)'), 'DESC')
            ->get();
        foreach ($products as $key => $product) {
            $unit = $this->getUnitType($product['unit']);
            $pre = '';
            if(is_null($unit)){
                $unit = '';
            }
            if(isset($product['presentation']) && $product['presentation'] > 0.00){
                $pre = $product['presentation'];
            }
            $product['presentation_formatted'] = $product['name'].' '.$pre.' '.$unit;
        }
        return $products;
    }

    public function excel($report, Request $request)
    {
        $file = Excel::create('Reporte', function ($excel) use ($request, $report) {
            $excel->setCreator('LimonByte')->setCompany('Viveres&Abarrotes');
            $excel->setDescription('Reporte ' . $report);
            $excel->sheet('Listado', function ($sheet) use ($request, $report) {
                $sheet->loadView('admin.excel.' . $report)->with([
                    'data' => $request->data,
                    'type' => $request->has('report_type') ? $request->report_type : '',
                    'to' => $request->has('to') ? $request->to : '',
                    'from' => $request->has('from') ? $request->from : '',
                    'months' => $this->getMonths()
                ]);
            });
        })->download();

        return $file;
    }

    public function pdf($report, Request $request)
    {
        $file = PDF::loadView('admin.pdf.' . $report, [
            'data'   => $request->data,
            'type'   => $request->has('report_type') ? $request->report_type : '',
            'months' => $this->getMonths(),
            'from'   => $request->from ? Carbon::parse($request->from)->format('d-m-Y') : null,
            'to'     => $request->to ? Carbon::parse($request->to)->format('d-m-Y') : null,
        ]);

        $file_name = 'REPORTE-' . \Carbon\Carbon::now()->format('d-m-Y') . strtoupper(str_random(10));

        return $file->stream($file_name . '.pdf', array("Attachment" => false))->header('Content-Type', 'application/pdf');
    }

    private function parseTypeQuery($type)
    {
        switch ($type) {
            case 'daily':
                return 'date(purchases.created_at)';
            case 'monthly':
                return 'MONTH(purchases.created_at)';
            case 'yearly':
                return 'YEAR(purchases.created_at)';
        }
    }

    private function getMonths()
    {
        return [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];
    }
}

<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Mail\Replenishment as ReplenishmentMail;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Models\Replenishment;
use App\Models\ProductAmount;
use App\Models\Product;
use Carbon\Carbon;
use App\User;
use Validator;
use PDF;
use File;
use Auth;
use Mail;

ini_set('max_execution_time', 3000);
set_time_limit(3000);

class ReplenishmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::whereIn('status', ['1', '0'])
            ->with([
                'colors' => function ($colors) {
                    $colors->select('id', 'name', 'name_english', 'product_id')
                        ->where('status', '1')
                        ->with([
                            'amounts' => function ($q) {
                                $q->select('id as amount_id', 'amount', 'min', 'max', 'cost', 'umbral', 'price', 'unit', 'presentation', 'product_color_id', 'category_size_id')
                                    ->with([
                                        'category_size' => function ($c) {
                                            $c->select('id', 'category_id', 'size_id')
                                                ->with([
                                                    'size' => function ($s) {
                                                        $s->select('id', 'name')
                                                            ->where('status', '1');
                                                    }
                                                ]);
                                        }
                                    ]);
                            }
                        ]);
                }
            ])
            ->orderBy('name', 'ASC')
            ->get();
        return view('admin.replenishments.index')
            ->with([
                'products' => $products
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'existing' => 'required|numeric',
            'final' => 'required|numeric',
            'modified' => 'required',
            'presentation' => 'required|numeric',
            'product' => 'required|numeric',
            'reason' => 'required|string',
            'type' => 'required'
		];
        $messages = [
            "required_unless" => "El campo :attribute es obligatorio.",
            "required_if" => "El campo :attribute es obligatorio.",
            "confirmed" => 'Las contaseñas no coinciden',
            "unique" => 'El Correo :attribute ya esta siendo usado'
        ];
        $attributes = [
            'existing' => 'Cantidad en existencia',
            'final' => 'Cantidad final',
            'modified' => 'Cantidad de reposicion',
            'product' => 'Producto',
            'reason' => 'Razon',
            'type' => 'Tipo de reposicion',
            'presentation' => 'Presentacion'
        ];
		$validator = Validator::make($request->all(), $rules, $messages);
        $validator->setAttributeNames($attributes);
        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'msg' => $validator->errors()->first()
            ]);
        }else{
            $rep = new Replenishment;
            $rep->user_id = Auth::id();
            $rep->product_presentation = $request->presentation;
            $rep->type = $request->type;
            $rep->existing = $request->existing;
            $rep->modified = $request->modified;
            $rep->final = $request->final;
            $rep->reason = $request->reason;
            $rep->save();

            $productAmountQuery = ProductAmount::where('id', $request->presentation)->whereNull('deleted_at');

            $productAmount = $productAmountQuery->first();

            $presentationFormatted = 'No posee presentacion';
            
            if(isset($productAmount)) {
                $productAmountQuery->update([
                    'amount' => $request->final
                ]);
                $presentationFormatted = $productAmount->presentation.' '.$this->getUnitType($productAmount->unit);
            }
            $rep->load(['presentation' => function($q){
                $q->with('product')
                    ->withTrashed();
            }, 'user']);
            Mail::to(env('MAIL_CONTACTO'))->queue(new ReplenishmentMail($rep, $presentationFormatted));
            return response()->json([
                'msg' => 'Reposicion de inventario guardada exitosamente',
                'item' => $rep
            ]);
        }
    }

    public function filter(Request $request){
        $reps = Replenishment::select('replenishments.*')
            ->join('product_amount', 'replenishments.product_presentation', '=', 'product_amount.id')
            ->join('products', 'product_amount.product_color_id', '=', 'products.id')
            ->whereNull('replenishments.deleted_at')
            ->with(['presentation.product', 'user'])
            ->orderBy('replenishments.id', 'DESC');
        if(isset($request->start)){
            $start = Carbon::parse($request->start)->format('Y-m-d 00:00:00');
            $reps = $reps->where('replenishments.created_at', '>=', $start);
        }
        if(isset($request->end)){
            $end = Carbon::parse($request->end)->format('Y-m-d 23:59:59');
            $reps = $reps->where('replenishments.created_at', '<=', $end);
        }
        if(isset($request->type)){
            $reps = $reps->where('replenishments.type', $request->type);
        }
        if(isset($request->search)){
            $reps = $reps->where('products.name', 'like', '%'. $request->search.'%')
                ->orWhere('products.name_english', 'like', '%'. $request->search.'%')
                ->orWhere('products.description', 'like', '%'. $request->search .'%');
        }
        $reps = $reps->paginate(10);
        return response()->json([
            'reps' => $reps
        ]);
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
                return 'Unidad';
        }
    }

    public function export(Request $request){
        $reps = Replenishment::select('replenishments.*')
            ->join('product_amount', 'replenishments.product_presentation', '=', 'product_amount.id')
            ->join('products', 'product_amount.product_color_id', '=', 'products.id')
            ->whereNull('replenishments.deleted_at')
            ->with(['presentation.product', 'user'])
            ->whereHas('presentation')
            ->orderBy('replenishments.id', 'DESC');
        if(isset($request->start)){
            $start = Carbon::parse($request->start)->format('Y-m-d 00:00:00');
            $reps = $reps->where('replenishments.created_at', '>=', $start);
        }
        if(isset($request->end)){
            $end = Carbon::parse($request->end)->format('Y-m-d 23:59:59');
            $reps = $reps->where('replenishments.created_at', '<=', $end);
        }
        //if(isset($request->type)){
         //   $reps = $reps->where('replenishments.type', $request->type);
        //}
        if(isset($request->search)){
            $reps = $reps->where('products.name', 'like', '%'. $request->search.'%')
                ->orWhere('products.name_english', 'like', '%'. $request->search.'%')
                ->orWhere('products.description', 'like', '%'. $request->search .'%');
        }
        $data = $reps->get();

        $today = Carbon::parse()->format('d-m-Y h:i A');        
        $data = collect($data)->map(function ($item) {
            $item['presentation']['presentation_formatted'] = $item['presentation']['presentation'].' '.$this->getUnitType($item['presentation']['unit']);
            return $item;
        });

        $file = Excel::create('Reposicion-de-inventario', function ($excel) use ($data, $today) {
            $excel->setCreator('LimonByte')->setCompany('Viveres&Abarrotes');
            $excel->setDescription('Reposicion de inventario');
            $excel->sheet('Listado', function ($sheet) use ($data, $today) {

                $sheet->setWidth('A', 10);
                $sheet->setWidth('B', 50);
                $sheet->setWidth('C', 20);
                $sheet->setWidth('D', 20);
                $sheet->setWidth('E', 20);
                $sheet->setWidth('F', 20);
                $sheet->setWidth('G', 20);
                $sheet->setWidth('H', 20);
                $sheet->setWidth('I', 30);
                $sheet->setWidth('J', 20);
                $sheet->setWidth('K', 20);
                $sheet->setWidth('L', 20);
                $sheet->setWidth('M', 20);

                $sheet->loadView('admin.excel.replenishment')->with([
                    'reps' => $data,
                    'today' => $today,
                ]);
            });
        })->download();

        return $file;
    }

    public function pdf(Request $request){
        $data = Replenishment::select('replenishments.*')
            ->join('product_amount', 'replenishments.product_presentation', '=', 'product_amount.id')
            ->join('products', 'product_amount.product_color_id', '=', 'products.id')
            ->whereNull('replenishments.deleted_at')
            ->with(['presentation.product', 'user'])
            ->orderBy('replenishments.id', 'DESC');
        if(isset($request->start)){
            $start = Carbon::parse($request->start)->format('Y-m-d 00:00:00');
            $data = $data->where('replenishments.created_at', '>=', $start);
        }
        if(isset($request->end)){
            $end = Carbon::parse($request->end)->format('Y-m-d 23:59:59');
            $data = $data->where('replenishments.created_at', '<=', $end);
        }
        if(isset($request->type)){
            $data = $data->where('replenishments.type', $request->type);
        }
        if(isset($request->search)){
            $data = $data->where('products.name', 'like', '%'. $request->search.'%')
                ->orWhere('products.name_english', 'like', '%'. $request->search.'%')
                ->orWhere('products.description', 'like', '%'. $request->search .'%');
        }
        $data = $data->get();
        $file = PDF::loadView('admin.pdf.replenishment', [
            'data'   => $data
        ])
            ->setPaper('a4', 'landscape');

        $file_name = 'REPORTE-DE-REPOSICION-DE-INVENTARIO' . \Carbon\Carbon::now()->format('d-m-Y') . strtoupper(str_random(10));

        return $file->stream($file_name . '.pdf', array("Attachment" => false))
            ->header('Content-Type', 'application/pdf');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

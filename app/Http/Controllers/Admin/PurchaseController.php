<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\PurchaseDetails;
use App\Models\ProductAmount;
use App\Models\Social;
use App\Models\Category;
use App\Models\PromotionUser;
use App\User;
use Carbon\Carbon;
use Mail;
use Excel;

class PurchaseController extends Controller
{
    public function index()
    {
        return view('admin.purchases.index');
    }

    public function date(Request $request)
    {

        $init = $request->init ? new Carbon($request->init) : null;
        $end = $request->end ? new Carbon($request->end) : null;
        
        return Purchase::select('purchases.*')
        ->join('users', 'purchases.user_id', '=', 'users.id')
        ->with(['user', 'exchange', 'exchange', 'transfer.bankAccount.bank', 
            'delivery' => function($query) {
                $query->with(['state', 'municipality', 'parish']);
            }])
            ->when(!is_null($request->status), function ($query) use ($request) {
                $query->where('purchases.status', $request->status);
            })
            ->when($init && $end, function ($query) use ($init, $end) {
                $query->whereBetween('purchases.created_at', [$init->format('Y-m-d 00:00:00'), $end->format('Y-m-d 23:59:59')]);
            })
            ->when(isset($request->search), function ($query) use ($request) {
                $query->where('users.name', 'like', '%'.$request->search.'%')
                    ->orWhere('purchases.id', 'like', '%'.$request->search.'%');
            })
            ->where('purchases.payment_type',4)
            ->orderBy('id', 'DESC')
            ->paginate(10);
    }

    public function getDetails(Request $request){
        return Purchase::where('id', $request->id)
            ->with(['details'])
            ->first();
    }

    public function getTotalAmount($details, $exchange, $currency, $shipping_fee)
    {
        $subtotal = 0;
        $price = 0;

        $total = collect($details)->reduce(function ($carry, $item) use ($currency, $exchange, $subtotal, $price) {
            if ($currency == 2) {
                if ($item['coin'] == '1') {
                    $price = $item['price'] / $exchange['change'];
                } else {
                    $price = $item['price'];
                }
            } else {
                if ($item['coin'] == '1') {
                    $price = $item['price'];
                }else {
                    $price = $item['price'] * $exchange['change'];
                }
            }

            $subtotal = $price * $item['quantity'];

            return $carry + $subtotal;
        }, 0);
        $total = $total + $shipping_fee;
        return $total;
    }

    public function getTypePayment($payment)
    {
        switch ($payment) {
            case Purchase::PAYMENT_TRANSFER:
                return 'Transferencia';
            case Purchase::PAYMENT_MOBILE:
                return 'Pago Movil';
            case Purchase::PAYMENT_ZELLE:
                return 'Zelle';
            case Purchase::PAYMENT_PAYPAL:
                return 'Paypal';
            case Purchase::PAYMENT_EFECTIVO:
                return 'Efectivo';
            case Purchase::PAYMENT_STRIPE:
                return 'Stripe';
        }
    }

    public function getTurn($turn)
    {
        switch ($turn) {
            case Purchase::TURN_MORNING:
                return 'Mañana';
            case Purchase::TURN_AFTERNOON:
                return 'Tarde';
            case Purchase::TURN_NIGHT:
                return 'Noche';
        }
    }

    public function exportExcel(Request $request)
    {
        $init = $request->init ? new Carbon($request->init) : null;
        $end = $request->end ? new Carbon($request->end) : null;
        $data = Purchase::select('purchases.*')
            ->join('users', 'purchases.user_id', '=', 'users.id')
            ->with(['user', 'exchange', 'details', 'exchange', 'transfer.bankAccount.bank', 
                'delivery' => function($query) {
                    $query->with(['state', 'municipality', 'parish']);
                }])
                ->when(!is_null($request->status), function ($query) use ($request) {
                    $query->where('purchases.status', $request->status);
                })
                ->when($init && $end, function ($query) use ($init, $end) {
                    $query->whereBetween('purchases.created_at', [$init->format('Y-m-d 00:00:00'), $end->format('Y-m-d 23:59:59')]);
                })
                ->when(isset($request->search), function ($query) use ($request) {
                    $query->where('users.name', 'like', '%'.$request->search.'%')
                        ->orWhere('purchases.id', 'like', '%'.$request->search.'%');
                })
                ->orderBy('id', 'DESC')
                ->get();
        $today = Carbon::parse()->format('d-m-Y h:i A');

        $data = collect($data)->map(function ($item) {
            $item['amount'] = $this->getTotalAmount($item['details'], $item['exchange'], $item['currency'], $item['shipping_fee']);
            $item['createdAt'] = Carbon::parse($item['created_at'])->format('d-m-Y h:i A');
            $item['clientName'] = $item['user']['name'];
            $item['paymentType'] = $this->getTypePayment($item['payment_type']);
            $item['deliveryDay'] = Carbon::parse($item['delivery']['date'])->format('d-m-Y');
            $item['typeTurn'] = $this->getTurn($item['delivery']['turn']);
            $item['stateName'] = $item['delivery']['state']['nombre'];
            $item['municipalityName'] = $item['delivery']['municipality']['name'];
            $item['parishName'] = $item['delivery']['parish']['name'];
            if($item['payment_type'] == 5) {
                $item['code'] = '';
            }else {
                $item['code'] = $item['payment_type'] == 4 ? $item['transaction_code'] : $item['transfer']['number'];
            }
            $item['deliveryType'] = $item['delivery']['type'] ? $item['delivery']['type'] == '1' ? 'Nacional (Cobro a Destino)' : 'Nacional (Envio a Tienda)' : 'Envío Regional';
            $item['statusType'] = 'Completado';
            if($item['status'] == 0){
                $item['statusType'] = 'En Espera';
            }else if($item['status'] == 1){
                $item['statusType'] = 'Procesando';
            }else if($item['status'] == 2){
                $item['statusType'] = 'Cancelado';
            }
            return $item;
        });

        $file = Excel::create('Reporte', function ($excel) use ($data, $today) {
            $excel->setCreator('LimonByte')->setCompany('Viveres&Abarrotes');
            $excel->setDescription('Reporte de Pedidos');
            $excel->sheet('Listado', function ($sheet) use ($data, $today) {

                $sheet->setWidth('A', 10);
                $sheet->setWidth('B', 30);
                $sheet->setWidth('C', 30);
                $sheet->setWidth('D', 20);
                $sheet->setWidth('E', 20);
                $sheet->setWidth('F', 20);
                $sheet->setWidth('G', 20);
                $sheet->setWidth('H', 20);
                $sheet->setWidth('I', 30);
                $sheet->setWidth('J', 20);
                $sheet->setWidth('K', 20);
                $sheet->setWidth('L', 20);

                $sheet->loadView('admin.excel.purchases')->with([
                    'purchases' => $data,
                    'today' => $today,
                ]);
            });
        })->download();

        return $file;
    }

    public function approve(Request $request, $id)
    {
        $purchase = Purchase::with(['exchange','details','transfer', 'delivery'])
        ->whereHas('details',function($q) {
            $q->whereNotNull('product_amount_id');
        })->where('id', $id)->first();

        $purchase->status = $request->status;
        $purchase->save();

        $_sociales = Social::orderBy('id','desc')->first();

        $user = User::where('id', $purchase->user_id)->first();
        $statusName = $request->status == 1 ? 'APROBADO' : 'COMPLETADO';
        $subjectName = $request->status == 1 ? 'Compra Aprobada | ' : 'Compra Completada | ';

        Mail::send('emails.compra-aprobada', [
            'compra'     => $purchase, 
            'user'       => $user, 
            'sociales'   => $_sociales,
            'statusName' => $statusName
        ], function ($m) use ($user, $subjectName, $request) {
            $to = $request->status == 1 ? [$user->email, env('MAIL_CONTACTO')] : $user->email;
            $m->to($to)
                ->subject($subjectName . config('app.name'));
        });

        return response()->json(['result' => true]);
    }

    public function reject(Request $request, $id)
    {
        $purchase = Purchase::with([
            'exchange',
            'details',
            'transfer', 
            'delivery'
        ])
            ->whereHas('details',function($q) {
                $q->whereNotNull('product_amount_id');
            })->where('id', $id)->first();

        $promotionsUsedIds = [];

        foreach ($purchase->details as $detail) {
            if(!is_null($detail['product_amount_id'])){
                $amount = ProductAmount::find($detail['product_amount_id']);
                $amount->amount = $amount->amount + $detail['quantity'];
                $amount->save();
                if(!in_array($detail->promotion_id, $promotionsUsedIds)) {
                    array_push($promotionsUsedIds, $detail->promotion_id);
                }
            }
        }

        $purchase->status = $request->status;
        $purchase->save();

        foreach($promotionsUsedIds as $promotionUsedId) {
            PromotionUser::where('promotion_id', $promotionUsedId)->where('user_id', $purchase->user_id)->orderBy('id', 'desc')->delete();
        }

        $user = User::where('id', $purchase->user_id)->first();

        $purchase->rejectReason = $request->rejectReason;
        $purchase->transferNumber = !is_null($purchase->transfer) ? $purchase->transfer->number : '';

        Mail::send('emails.compra-rechazada', ['compra' => $purchase, 'user' => $user], function ($m) use ($user) {
            $m->to([$user->email, env('MAIL_CONTACTO')])->subject('Compra Cancelada | ' . config('app.name'));
        });

        return response()->json(['result' => true]);
    }

}

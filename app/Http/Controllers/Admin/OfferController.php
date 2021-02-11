<?php

namespace App\Http\Controllers\Admin;

use App\Models\Offer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use \Carbon\Carbon;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::where('status', '1')
            ->whereHas('products', function ($q) {
                $q->where('status', '1')
                    ->doesntHave('offersActive');
            })
            ->with(['products' => function ($q) {
                $q->where('status', '1')
                    ->doesntHave('offersActive');
            }])
            ->get();

        return view('admin.offers.index', [
            'offers' => Offer::latest()->get(),
            'categories' => $categories,
            'products' => Product::where('status', '1')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('status', '1')
            ->whereHas('products', function ($q) {
                $q->where('status', '1')
                    ->doesntHave('offersActive');
            })
            ->with(['products' => function ($q) {
                $q->where('status', '1')
                    ->doesntHave('offersActive');
            }])
            ->get();

        return view('admin.offers.create', [
            'categories' => $categories,
            'products' => Product::where('status', '1')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $offer = Offer::create($request->all());

        $offer->products()->attach($request->products_id);

        return $offer;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function show(Offer $offer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function edit(Offer $offer)
    {
        $categories = Category::where('status', '1')
            ->whereHas('products', function ($q)  use ($offer) {
                $q->where('status', '1')
                    ->where(function ($q) use ($offer) {
                        $q->whereIn('id', $offer->products()->pluck('id'))
                            ->orWhere(function ($q) {
                                $q->doesntHave('offersActive');
                            });
                    });
            })
            ->with(['products' => function ($q) use ($offer) {
                $q->where('status', '1')
                    ->where(function ($q) use ($offer) {
                        $q->whereIn('id', $offer->products()->pluck('id'))
                            ->orWhere(function ($q) {
                                $q->doesntHave('offersActive');
                            });
                    });
            }])
            ->get();

        return view('admin.offers.edit', [
            'offer' => $offer->load('products'),
            'categories' => $categories,
            'products' => Product::where('status', '1')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Offer $offer)
    {
        $offer->fill($request->all());

        $offer->save();

        $offer->products()->sync($request->products_id);

        return $offer->load('products');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Offer $offer)
    {
        $offer->delete();
    }

    public function status(Offer $offer)
    {
        if ($offer->status == Offer::INACTIVE) {
            $now = Carbon::now();
            $end = new Carbon($offer->end);
            if ($end->isBefore($now)) {
                return response()->json([
                    'message' => 'No se puede activar la oferta debido a que la fecha de finalización ya ha pasado'
                ], 422);
            }

            $productsWithOffersActive = Product::whereIn('id', $offer->products->pluck('id'))
                ->whereHas('offersActive')
                ->exists();

            if ($productsWithOffersActive) {
                return response()->json([
                    'message' => 'No se puede activar la oferta debido tiene productos agregados que ya tienen un oferta activa'
                ], 422);
            }
        }


        $offer->status = !$offer->status;
        $offer->save();
        return $offer;
    }
}

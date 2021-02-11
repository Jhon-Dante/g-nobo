<?php

namespace App\Http\Controllers\Admin;

use App\Models\Discount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\DiscountRequest;
use App\Models\Category;
use App\Models\Product;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $discounts = Discount::latest()->get();
        $categories = Category::where('status', '1')
            ->whereHas('products', function ($q) {
                $q->where('status', '1');
            })
            ->with(['products' => function ($q) {
                $q->where('status', '1');
            }])
            ->get();

        return view('admin.discounts.index', [
            'categories' => $categories,
            'products' => Product::where('status', '1')->get(),
            'discounts' => $discounts
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
                    ->doesntHave('discountsActive');
            })
            ->with(['products' => function ($q) {
                $q->where('status', '1')
                    ->doesntHave('discountsActive');
            }])
            ->get();

        return view('admin.discounts.create', [
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
    public function store(DiscountRequest $request)
    {
        $discount = Discount::create($request->all());

        if ($discount->type === 'quantity_product') {
            $discount->products()->attach($request->products_id);
        } else {

            $existsOtherActive = Discount::where('type', $discount->type)
                ->where('status', Discount::ACTIVE)
                ->where('id', '!=', $discount->id)
                ->exists();

            if ($existsOtherActive) { // Solo puede haber uno activo de estos tipos
                $discount->status = Discount::INACTIVE;
                $discount->save();
            }
        }

        return $discount;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function show(Discount $discount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function edit(Discount $discount)
    {
        $categories = Category::where('status', '1')
            ->whereHas('products', function ($q) use ($discount) {
                $q->where('status', '1')
                    ->where(function ($q) use ($discount) {
                        $q->whereIn('id', $discount->products()->pluck('id'))
                            ->orWhere(function ($q) {
                                $q->doesntHave('discountsActive');
                            });
                    });;
            })
            ->with(['products' => function ($q) use ($discount) {
                $q->where('status', '1')
                    ->where(function ($q) use ($discount) {
                        $q->whereIn('id', $discount->products()->pluck('id'))
                            ->orWhere(function ($q) {
                                $q->doesntHave('discountsActive');
                            });
                    });;
            }])
            ->get();

        return view('admin.discounts.edit', [
            'categories' => $categories,
            'products' => Product::where('status', '1')->get(),
            'discount' => $discount->load('products')
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function update(DiscountRequest $request, Discount $discount)
    {
        $discount->fill($request->all());

        $discount->save();

        $discount->products()->sync($request->products_id);

        return $discount->load('products');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function destroy(Discount $discount)
    {
        $discount->delete();
    }

    public function status(Discount $discount)
    {
        if ($discount->type != 'quantity_product' && $discount->status == Discount::INACTIVE) {
            $existsOtherActive = Discount::where('type', $discount->type)
                ->where('status', Discount::ACTIVE)
                ->exists();

            if ($existsOtherActive) {
                return response()->json([
                    'message' => 'Existe otro descuento de este tipo activo en este momento'
                ], 422);
            }
        }
        $discount->status = !$discount->status;
        $discount->save();
        return $discount;
    }
}

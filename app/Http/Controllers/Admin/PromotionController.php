<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PromotionRequest;
use App\Models\Category;
use App\Models\ProductAmount;
use App\Models\Promotion;
use App\Models\PromotionProduct;
use App\Libraries\SetNameImage;
use App\Libraries\ResizeImage;

class PromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $width_file = 550;
    private $height_file = 800;

    public function index()
    {

        $promotions = Promotion::with(['products' => function($query) {
            $query->select('id', 'product_id', 'promotion_id', 'amount')->with(['product_amount' => function($query2) {
                $query2->select('id', 'amount');
            }]);
        }])->orderBy('id', 'desc')->get();

        foreach($promotions as $promotion) {
            foreach($promotion->products as $product) {
				if(isset($product->product_amount)) {
					if($promotion->status != Promotion::STATUS_SOLD_OUT) {
						$promotion->status = $product->product_amount->amount >= $product->amount ? $promotion->status : Promotion::STATUS_SOLD_OUT;
					}
				}
			}
        }

        return view('admin.promotions.index')->with([
            'promotions' => $promotions
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
                ->whereHas('colors', function ($q2) {
                    $q2->has('amounts');
                });
            })
            ->with(['products' => function ($q) {
                $q->where('status', '1')
                ->with([
                    'colors' => function ($colors) {
                        $colors->select('id', 'name', 'name_english', 'product_id')
                            ->where('status', '1')
                            ->with([
                                'amounts' => function ($q) {
                                    $q->select('id as product_id', 'amount', 'min', 'max', 'cost', 'umbral', 'price', 'unit', 'presentation', 'product_color_id', 'category_size_id')
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
                ->orderBy('id', 'DESC');
            }])
            ->get();

        return view('admin.promotions.create')->with([
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PromotionRequest $request)
    {

        $promotion = Promotion::create($request->all());

        $url = "img/promotions/";
        $main = $request->file('image');
        $main_name = SetNameImage::set($main->getClientOriginalName(), $main->getClientOriginalExtension());
        $main->move($url, $main_name);
        // ResizeImage::dimenssion($main_name, $main->getClientOriginalExtension(), $url, $this->width_file, $this->height_file);
        $imagePath = $url.$main_name;
        $promotion->image = $imagePath;

        $promotion->save();

        $products = json_decode($request->products);

        foreach ($products as $product) {
            $newPromotionProduct = new PromotionProduct();
                $newPromotionProduct->promotion_id = $promotion->id;
                $newPromotionProduct->product_id = $product->product_id;
                $newPromotionProduct->amount = $product->total;
            $newPromotionProduct->save();
        }

        return $promotion;
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
    public function edit(Promotion $promotion)
    {
        $categories = Category::where('status', '1')
        ->whereHas('products', function ($q) {
            $q->where('status', '1')
            ->whereHas('colors', function ($q2) {
                $q2->has('amounts');
            });
        })
        ->with(['products' => function ($q) {
            $q->where('status', '1')
            ->with([
                'colors' => function ($colors) {
                    $colors->select('id', 'name', 'name_english', 'product_id')
                        ->where('status', '1')
                        ->with([
                            'amounts' => function ($q) {
                                $q->select('id as product_id', 'amount', 'min', 'max', 'cost', 'umbral', 'price', 'unit', 'presentation', 'product_color_id', 'category_size_id')
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
            ->orderBy('id', 'DESC');
        }])
        ->get();

        $productAmounts = ProductAmount::whereIn('id', $promotion->products()->pluck('product_id'))
        ->with(['product' => function($query){
            $query->select('id', 'name');
        }])
        ->get();

        return view('admin.promotions.edit', [
            'categories' => $categories,
            'promotion' => $promotion->load('products'),
            'productAmounts' => $productAmounts
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PromotionRequest $request, Promotion $promotion)
    {
        $promotion->fill($request->all());
        if($request->hasFile('image')) {
            $url = "img/promotions/";
            $main = $request->file('image');
            $main_name = SetNameImage::set($main->getClientOriginalName(), $main->getClientOriginalExtension());
            $main->move($url, $main_name);
            // ResizeImage::dimenssion($main_name, $main->getClientOriginalExtension(), $url, $this->width_file, $this->height_file);
            $imagePath = $url.$main_name;
            $promotion->image = $imagePath;
        } else {
            $promotion->image = $request->image;
        }
        $promotion->save();

        $products = json_decode($request->products);

        PromotionProduct::where('promotion_id', $promotion->id)->delete();

        foreach ($products as $product) {
            $newPromotionProduct = new PromotionProduct();
                $newPromotionProduct->promotion_id = $promotion->id;
                $newPromotionProduct->product_id = $product->product_id;
                $newPromotionProduct->amount = $product->total;
            $newPromotionProduct->save();
        }

        return $promotion->load('products');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Promotion $promotion)
    {
        $promotion->delete();
    }

    public function status(Promotion $promotion)
    {
        $data = $promotion->load('products.product_amount.product');
        foreach ($data['products'] as $key => $product) {
            if($product['amount'] > $product['product_amount']['amount']){
                return response()->json([
                    'message' => 'El producto: '.$product['product_amount']['product']['en_name'].' ya no cuenta con el stock suficiente'
                ], 422);
            }
        } 
        $promotion->status = !$promotion->status;
        $promotion->save();
        return $promotion;
    }
}

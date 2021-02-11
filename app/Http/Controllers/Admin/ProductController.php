<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Design;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductColor;
use App\Models\ProductAmount;
use App\Http\Requests\ProductRequest;
use App\Libraries\SetNameImage;
use App\Libraries\ResizeImage;
use App\Models\Subcategory;
use App\Models\Taxe;
use Carbon\Carbon;
use File;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
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
        $categories = Category::select('id', 'name', 'name_english')
            ->where('status', '1')
            ->with([
                'subcategories' => function ($sql) {
                    $sql->select('subcategories.id', 'subcategories.name', 'subcategories.name_english', 'subcategories.category_id')
                    ->where('subcategories.status', '1');
                },
                'sizes' => function ($sizes) {
                    $sizes->select('category_sizes.id', 'name');
                }
            ])
            ->orderBy('name', 'DESC')
            ->get();

        $collections = Collection::select('id', 'name', 'name_english')
            ->where('status', '1')
            ->get();

        $designs = Design::select('id', 'name', 'name_english', 'collection_id')
            ->where('status', '1')
            ->get();

        $taxes = Taxe::where('status', Taxe::STATUS_ACTIVE)->get();

        return view('admin.products.index')->with([
            'categories' => $categories,
            'collections' => $collections,
            'designs' => $designs,
            'taxes' => $taxes
        ]);
    }

    public function getProducts(Request $request){
        $products = Product::select('products.*')
            ->join('product_amount', 'product_amount.product_color_id', '=', 'products.id')
            ->with([
                'categories' => function ($category) {
                    $category->select('id', 'name', 'name_english');
                },
                'subcategories' => function ($subcategory) {
                    $subcategory->select('id', 'name', 'name_english');
                },
                'images',
                'colors' => function ($colors) use($request) {
                    $colors->select('id', 'name', 'name_english', 'product_id')
                        ->where('status', '1')
                        ->with([
                            'amounts' => function ($q) use($request) {
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
                                    ])
                                    ->when(!is_null($request->inventory), function ($query) use ($request) {
                                        $inventory = intval($request->inventory);
                                        if($inventory == 2){ //Con Poca Existencia
                                            $query->whereRaw('product_amount.amount > 0')
                                                ->whereRaw('product_amount.amount <= product_amount.umbral')
                                                ->whereNull('product_amount.deleted_at');
                                        }else if($inventory == 1){ // Con Existencia
                                            $query->whereRaw('product_amount.amount > 0')
                                                ->whereRaw('product_amount.amount > product_amount.umbral')
                                                ->whereNull('product_amount.deleted_at');
                                        }else{ // Agotado
                                            $query->where('product_amount.amount', 0)
                                                ->whereNull('product_amount.deleted_at');
                                        }
                                    });
                            }
                        ]);
                }
            ])
            ->when(!is_null($request->status), function ($query) use ($request) {
                $query->where('products.status', $request->status);
            })
            ->when(is_null($request->status), function ($query) {
                $query->whereIn('products.status', ['0', '1']);
            })
            ->when(!is_null($request->category), function ($query) use ($request) {
                $query->where('products.category_id', $request->category);
            })
            ->when(!is_null($request->subcategory), function ($query) use ($request) {
                $query->where('products.subcategory_id', $request->subcategory);
            })
            ->when(!is_null($request->typeProduct), function ($query) use ($request) {
                $query->where('products.variable', $request->typeProduct);
            })
            ->when(isset($request->search), function ($query) use ($request) {
                $query->where(function($querySearch) use ($request) {
                    $querySearch->where('products.name', 'like', '%'.$request->search.'%')
                        ->orWhere('products.name_english', 'like', '%'.$request->search.'%');
                });
            })
            ->when(!is_null($request->inventory), function ($query) use ($request) {
                $inventory = intval($request->inventory);
                if($inventory == 2){ //Con Poca Existencia
                    $query->whereRaw('product_amount.amount > 0')
                        ->whereRaw('product_amount.amount <= product_amount.umbral')
                        ->whereNull('product_amount.deleted_at');
                }else if($inventory == 1){ // Con Existencia
                    $query->whereRaw('product_amount.amount > 0')
                        ->whereRaw('product_amount.amount > product_amount.umbral')
                        ->whereNull('product_amount.deleted_at');
                }else{ // Agotado
                    $query->where('product_amount.amount', 0)
                        ->whereNull('product_amount.deleted_at');
                }
            })
            ->orderBy('products.id', 'DESC')
            ->groupBy('products.id')
            ->paginate(10);
        return response()->json([
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

    public function active(Request $request){

        Product::whereIn('id', $request->checks)
            ->update([
                'status' => strval($request->status)
            ]);
        return response()->json([
            'msg' => 'Productos actualizados exitosamente'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        /**
         * Developer Leeme!
         * 
         * La BD viene de un proyecto anterior (wara) que vendia ropa (en tallas y colores) por eso se hicieron ajustes
         * para hacerlo funcional a este proyecto.
         * 
         * La existencias de productos osea las cantidades se encuentran en la tabla que corresponde al modelo "ProductAmount"
         * pero esa existencia obedecia a los colores de dicho productor "ProductColor", al mismo tiempo las categorias
         * estan disponibles para algunas tallas nada mas (S,M,etc) por defecto todas las categorias estan relacionadas
         * a una talla (S) única en la BD. pero cada categoria tiene si registro en "CategoriSizes" que relaciona categoria con talla.
         * "ProductoAmount" incluye el campo category_size_id que nos es mas sino un registro de "CategoriSizes".
         */
        $product = new Product;
        $product->name = $request->name;
        $product->name_english = $request->name_english;
        $product->description = $request->description;
        $product->description_english = $request->description_english;
        $product->coin = $request->coin;
        $product->price_1 = $request->price_1 ? $request->price_1 : 0;
        $product->price_2 = $request->price_1 ? $request->price_1 : 0; // Se coloca por defecto precio uno pero el dos corresponde al mayor
        $product->category_id = $request->category_id;
        $product->subcategory_id = $request->subcategory_id != "" ? $request->subcategory_id : NULL;
        $product->collection_id = $request->collection_id;
        $product->design_id = $request->design_id != "" ? $request->design_id : NULL;
        $product->retail = $request->retail;
        $product->wholesale = $request->wholesale;
        $product->variable = $request->variable;
        $product->taxe_id = $request->taxe_id;
        $product->pro = $request->pro;
        $product->save();

        // Debido a la estructura previa de la BD es necesario crear un color por cada producto
        $color = new ProductColor;
        $color->name = 'por defecto';
        $color->name_english = 'default';
        $color->product_id = $product->id;
        $color->save();

        if ($request->variable == 1) {
            foreach (json_decode($request->presentations) as $presentation) {
                $amount = new ProductAmount;
                $amount->amount = $presentation->amount;
                $amount->product_color_id = $color->id;
                $amount->category_size_id = $request->category_size_id;
                $amount->price = $presentation->price;
                $amount->unit = $presentation->unit;
                $amount->presentation = $presentation->presentation;
                $amount->min = $presentation->min;
                $amount->max = $presentation->max;
                $amount->cost = $presentation->cost;
                $amount->umbral = $presentation->umbral;
                $amount->save();
            }
        } else {
            $size = new ProductAmount;
            $size->amount = $request->amount;
            $size->product_color_id = $color->id;
            $size->category_size_id = $request->category_size_id;
            $size->min = $request->min;
            $size->max = $request->max;
            $size->cost = $request->cost;
            $size->umbral = $request->umbral;
            $size->price = $request->price_1;
            $size->save();
        }

        // foreach (json_decode($request->colors) as $colors) {
        //     $color = new ProductColor;
        //     $color->name = $colors->name;
        //     $color->name_english = $colors->name_english;
        //     $color->product_id = $product->id;
        //     $color->save();

        //     foreach ($colors->sizes as $sizes) {
        //         $size = new ProductAmount;
        //         $size->amount = $sizes->amount;
        //         $size->product_color_id = $color->id;
        //         $size->category_size_id = $sizes->id;
        //         $size->save();
        //     }
        // }

        // Images
        if ($request->hasFile('main')) {
            $url = "img/products/";
            $main = $request->file('main');
            $main_name = SetNameImage::set($main->getClientOriginalName(), $main->getClientOriginalExtension());
            $main->move($url, $main_name);
            ResizeImage::dimenssion($main_name, $main->getClientOriginalExtension(), $url, $this->width_file, $this->height_file);
            $first = new ProductImage;
            $first->file = $main_name;
            $first->product_id = $product->id;
            $first->main = '1';
            $first->save();
        } else {
            $product->status = Product::STATUS_INACTIVE;
            $product->save();
        }
        for ($i = 1; $i <= $request->count; $i++) {
            $file = $request->file('file' . $i);
            $file_name = SetNameImage::set($file->getClientOriginalName(), $file->getClientOriginalExtension());
            $file->move($url, $file_name);
            ResizeImage::dimenssion($file_name, $file->getClientOriginalExtension(), $url, $this->width_file, $this->height_file);
            $second = new ProductImage;
            $second->file = $file_name;
            $second->product_id = $product->id;
            $second->main = '0';
            $second->save();
        }

        return response()->json(['result' => true, 'message' => 'Producto almacenado exitosamente.']);
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
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        $colors_delete = json_decode($request->colors_delete);

        if (count($colors_delete) > 0) {
            foreach ($colors_delete as $color) {
                foreach ($color->sizes as $size) {
                    if ($size->amount > 0) {
                        return response()->json(["error" => "No se pudo eliminar el color " . $color->name . ". Coloque 0 en inventario por talla antes de eliminar"], 422);
                    }
                }
            }
        }

        $product = Product::where('id', $id)
            ->with(['colors' => function ($colors) {
                $colors->where('status', '1')->with('amounts');
            }])
            ->orderBy('id', 'DESC')
            ->first();

        $product->name = $request->name;
        $product->name_english = $request->name_english;
        $product->description = $request->description;
        $product->description_english = $request->description_english;
        $product->coin = $request->coin;
        $product->price_1 = $request->price_1;
        $product->price_2 = $request->price_2;
        $product->category_id = $request->category_id;
        $product->subcategory_id = $request->subcategory_id != "" && $request->subcategory_id != 'null' ? $request->subcategory_id : NULL;
        $product->taxe_id = $request->taxe_id;
        $product->collection_id = $request->collection_id;
        $product->design_id = $request->design_id != "" && $request->design_id != 'null' ? $request->design_id : NULL;
        $product->retail = $request->retail;
        $product->wholesale = $request->wholesale;
        $product->pro = $request->pro;
        $product->save();

        if ($request->variable == 1) {
            foreach (json_decode($request->presentations) as $presentation) {
                if ($presentation->amount_id != 0) {
                    if ($presentation->deleted == 1) {
                        ProductAmount::where('id', $presentation->amount_id)->delete();
                        continue;
                    }
                    $productAmount = ProductAmount::where('id', $presentation->amount_id)
                        ->update([
                            'unit' => $presentation->unit,
                            // 'amount' => $presentation->amount,
                            'price' => $presentation->price,
                            'presentation' => $presentation->presentation,
                            'min' => $presentation->min,
                            'max' => $presentation->max,
                            'cost' => $presentation->cost,
                            'umbral' => $presentation->umbral
                        ]);
                } else {
                    $amount = new ProductAmount;
                    $amount->amount = $presentation->amount;
                    $amount->product_color_id = $request->color_id;
                    $amount->category_size_id = $request->category_size_id;
                    $amount->price = $presentation->price;
                    $amount->unit = $presentation->unit;
                    $amount->min = $presentation->min;
                    $amount->max = $presentation->max;
                    $amount->cost = $presentation->cost;
                    $amount->umbral = $presentation->umbral;
                    $amount->presentation = $presentation->presentation;
                    $amount->save();
                }
            }
        } else {

            $amountId = $product->colors[0]->amounts[0]->id;
            ProductAmount::where('id', $amountId)
                ->update([
                    // 'amount' => $request->amount,
                    'price' => $request->price_1,
                    'min' => $request->min,
                    'max' => $request->max,
                    'cost' => $request->cost,
                    'umbral' => $request->umbral
                ]);

            // $productAmount = ProductAmount::where('id', $request->product_amount_id)
            //     ->update(['amount' => $request->amount, 'price' => $request->price_1]);
        }


        // $color_ids = [];
        // $color_decode = json_decode($request->colors);
        // foreach ($color_decode as $key => $colors) {
        //     if($colors->id > 0){
        //         $color = ProductColor::find($colors->id)->update(['name' => $colors->name, 'name_english' => $colors->name_english]);
        //         $color_id = $colors->id;
        //     }else{
        //         $color = $product->colors()->create(['name' => $colors->name, 'name_english' => $colors->name_english]);
        //         $color_id = $color->id;
        //     }
        //     $color_ids[] = $color_id;
        //     foreach ($colors->sizes as $key => $sizes) {
        //         if ($sizes->amount_id != 0) {
        //             $size = ProductAmount::find($sizes->amount_id);
        //             $size->amount = $sizes->amount;
        //             $size->save();
        //         } else {
        //             $size = new ProductAmount;
        //             $size->amount = $sizes->amount;
        //             $size->product_color_id = $color_id;
        //             $size->category_size_id = $sizes->id;
        //             $size->save();
        //         }

        //     }
        // }

        // return $productos_color_validate = ProductColor::where('product_id', $id)->whereNotIn('id', $color_ids)->with(['amounts'])->get();

        // foreach ($productos_color_validate as $product) {
        //     foreach ($product->amounts as $amount) {
        //         if($amount->amount > 0){
        //             return response()->json(["error" => "Este color no puede "]);
        //         }
        //     }
        // }

        // ProductColor::where('product_id', $id)->whereNotIn('id', $color_ids)->whereHas('amounts', function ($query) {
        //         $query->where('amount', '=', '0');
        //     })->update(['status' => '0']);

        // Images
        // $url = "img/products/";
        // if ($request->hasFile('main')) {
        //     $main = $request->file('main');
        //     $main_name = SetNameImage::set($main->getClientOriginalName(), $main->getClientOriginalExtension());
        //     $main->move($url, $main_name);
        //     ResizeImage::dimenssion($main_name, $main->getClientOriginalExtension(), $url, $this->width_file, $this->height_file);
        //     $first = ProductImage::where('product_id', $id)->where('main', '1')->first();
        //     $old_main = $first->file;
        //     $first->file = $main_name;
        //     $first->save();
        //     File::delete(public_path($url.$old_main));
        // }

        return response()->json(['result' => true, 'message' => 'Producto actualizado exitosamente.']);
    }

    public function updateImage(Request $request)
    {
        $url = "img/products/";
        $hasMain = ProductImage::where('product_id', $request->product_id)->where('main', '1')->exists();
        if (!$hasMain) {
            $file = $request->file('file');
            $file_name = SetNameImage::set($file->getClientOriginalName(), $file->getClientOriginalExtension());
            $file->move($url, $file_name);

            ResizeImage::dimenssion($file_name, $file->getClientOriginalExtension(), $url, $this->width_file, $this->height_file);
            $detail = new ProductImage;
            $detail->file = $file_name;
            $detail->main = '1';
            $detail->product_id = $request->product_id;
            $detail->save();
            $fileId = $detail->id;
            return response()->json(['result' => true, 'id' => $fileId, 'file' => $file_name]);
        }

        if ($request->id == NULL || $request->id == 'null') {

            $item = ProductImage::where('product_id', $request->product_id)->where('main', '1')->first();
            $odlFile = $item->file;
            $file = $request->file('file');
            $file_name = SetNameImage::set($file->getClientOriginalName(), $file->getClientOriginalExtension());
            $file->move($url, $file_name);

            ResizeImage::dimenssion($file_name, $file->getClientOriginalExtension(), $url, $this->width_file, $this->height_file);

            if (File::exists($url . $odlFile)) {
                File::delete(public_path($url . $odlFile));
            }

            $item->file = $file_name;
            $item->save();
            $fileId = $item->id;
        } else if ($request->id == 0) {
            $file = $request->file('file');
            $file_name = SetNameImage::set($file->getClientOriginalName(), $file->getClientOriginalExtension());
            $file->move($url, $file_name);

            ResizeImage::dimenssion($file_name, $file->getClientOriginalExtension(), $url, $this->width_file, $this->height_file);
            $detail = new ProductImage;
            $detail->file = $file_name;
            $detail->product_id = $request->product_id;
            $detail->save();
            $fileId = $detail->id;
        } else {
            $item = ProductImage::find($request->id);
            $odlFile = $item->file;
            $file = $request->file('file');
            $file_name = SetNameImage::set($file->getClientOriginalName(), $file->getClientOriginalExtension());
            $file->move($url, $file_name);
            ResizeImage::dimenssion($file_name, $file->getClientOriginalExtension(), $url, $this->width_file, $this->height_file);
            File::delete(public_path($url . $odlFile));
            $item->file = $file_name;
            $item->save();
            $fileId = $request->id;
        }

        return response()->json(['result' => true, 'id' => $fileId, 'file' => $file_name]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $destroy = Product::find($id);
        $destroy->status = "2";
        $destroy->save();

        return response()->json(["result" => true, "message" => "Producto eliminado exitosamente."]);
    }

    public function delete(Request $request)
    {
        $url = "img/products/";
        $item = ProductImage::find($request->id);
        $file = $item->file;
        File::delete(public_path($url . $file));
        $item->delete();

        return response()->json(['result' => true]);
    }

    public function postear($id)
    {
        $destroy = Product::find($id);
        $destroy->status = $destroy->status == "0" ? "1" : "0";
        $destroy->save();

        $negation = $destroy->status == "1" ? "" : "no";

        return response()->json(["result" => true, "message" => "El producto ya $negation es visible para los clientes.", "status" => $destroy->status]);
    }

    public function pro($id)
    {
        $product = Product::find($id);
        $product->pro = $product->pro == 0 ? 1 : 0;
        $product->save();

        return response()->json(["result" => true, "message" => "Se ha actualizado el producto", "pro" => $product->pro]);
    }

    public function getUnitType($unit)
    {
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
        }
    }

    public function exportExcel(Request $request)
    {
        $data = Product::select('products.*')
            ->join('product_amount', 'product_amount.product_color_id', '=', 'products.id')
            ->with([
                'categories' => function ($category) {
                    $category->select('id', 'name', 'name_english');
                },
                'subcategories' => function ($subcategory) {
                    $subcategory->select('id', 'name', 'name_english');
                },
                'images',
                'colors' => function ($colors) use($request) {
                    $colors->select('id', 'name', 'name_english', 'product_id')
                        ->where('status', '1')
                        ->with([
                            'amounts' => function ($q) use($request) {
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
                                    ])
                                    ->when(!is_null($request->inventory), function ($query) use ($request) {
                                        $inventory = intval($request->inventory);
                                        if($inventory == 2){ //Con Poca Existencia
                                            $query->whereRaw('product_amount.amount > 0')
                                                ->whereRaw('product_amount.amount <= product_amount.umbral')
                                                ->whereNull('product_amount.deleted_at');
                                        }else if($inventory == 1){ // Con Existencia
                                            $query->whereRaw('product_amount.amount > 0')
                                                ->whereRaw('product_amount.amount > product_amount.umbral')
                                                ->whereNull('product_amount.deleted_at');
                                        }else{ // Agotado
                                            $query->where('product_amount.amount', 0)
                                                ->whereNull('product_amount.deleted_at');
                                        }
                                    });
                            }
                        ]);
                }
            ])
            ->whereIn('status', ['1', '0'])
            ->when(!is_null($request->status), function ($query) use ($request) {
                $query->where('products.status', $request->status);
            })
            ->when(!is_null($request->category), function ($query) use ($request) {
                $query->where('products.category_id', $request->category);
            })
            ->when(!is_null($request->subcategory), function ($query) use ($request) {
                $query->where('products.subcategory_id', $request->subcategory);
            })
            ->when(!is_null($request->typeProduct), function ($query) use ($request) {
                $query->where('products.variable', $request->typeProduct);
            })
            
            ->when(isset($request->search), function ($query) use ($request) {
                $query->where('products.name', 'like', '%'.$request->search.'%')
                    ->orWhere('products.name_english', 'like', '%'.$request->search.'%');
            })
            ->when(!is_null($request->inventory), function ($query) use ($request) {
                $inventory = intval($request->inventory);
                if($inventory == 2){ //Con Poca Existencia
                    $query->whereRaw('product_amount.amount > 0')
                        ->whereRaw('product_amount.amount <= product_amount.umbral')
                        ->whereNull('product_amount.deleted_at');
                }else if($inventory == 1){ // Con Existencia
                    $query->whereRaw('product_amount.amount > 0')
                        ->whereRaw('product_amount.amount > product_amount.umbral')
                        ->whereNull('product_amount.deleted_at');
                }else{ // Agotado
                    $query->where('product_amount.amount', 0)
                        ->whereNull('product_amount.deleted_at');
                }
            })
            ->orderBy('products.id', 'DESC')
            ->groupBy('products.id')
            ->get();
        $today = Carbon::parse()->format('d-m-Y h:i A');

        $data = collect($data)->map(function ($item) {

            $item['presentations'] = collect($item['colors'][0]['amounts'])->map(function ($presentation) {
                $presentation['unitType'] = $presentation['presentation'] . ' ' . $this->getUnitType($presentation['unit']);

                return $presentation;
            });

            return $item;
        });
        $file = Excel::create('Reporte', function ($excel) use ($data, $today) {
            $excel->setCreator('LimonByte')->setCompany('Viveres&Abarrotes');
            $excel->setDescription('Reporte de Productos');
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

                $sheet->loadView('admin.excel.products')->with([
                    'products' => $data,
                    'today' => $today,
                ]);
            });
        })->download();

        return $file;
    }

    public function import(Request $request)
    {
        Excel::load($request->file, function ($reader) {
            $rows = $reader->get();
            $categorySizeId = 0;
            $colorId = 0;
            $productError = false;
            foreach ($rows as $row) {
                // \Log::info(print_r($row->variable, true));
                if (empty($row->presentacion)) {
                    $product = new Product;
                    $product->name = $row->nombre;
                    $product->name_english = $row->nombre;
                    $product->description = $row->descripcion;
                    $product->description_english = $row->descripcion;
                    $product->coin = 2;
                    $product->price_1 = $row->variable != 1 ? $row->precio : 0;
                    $product->price_2 = $row->variable != 1 ? $row->precio : 0;
                    $product->variable = $row->variable;
                    $subcategory = Subcategory::find($row->subcategoria);
                    if ($subcategory == null) {
                        $productError = true;
                        continue;
                    }
                    $productError = false;
                    $product->subcategory_id = $row->subcategoria;
                    $product->category_id = $subcategory->category_id;
                    $product->taxe_id = $row->impuesto;
                    $product->collection_id = 1;
                    $product->design_id = null;
                    $product->pro = $row->pro == 1;
                    $product->status = '1';
                    $product->save();

                    $color = new ProductColor;
                    $color->name = 'por defecto';
                    $color->name_english = 'default';
                    $color->product_id = $product->id;
                    $color->save();
                    $colorId = $color->id;

                    $first = new ProductImage;
                    $first->file = $row->imagen;
                    $first->product_id = $product->id;
                    $first->main = '1';
                    $first->save();

                    $categorySizeId = $subcategory->categories->sizes[0]->id;
                    if ($row->variable != 1) {
                        $amount = new ProductAmount;
                        $amount->amount = $row->existencia;
                        $amount->product_color_id = $colorId;
                        $amount->category_size_id = $categorySizeId;
                        $amount->unit = $row->unidad;
                        $amount->price = $row->precio;
                        $amount->min = $row->minimo;
                        $amount->max = $row->maximo;
                        $amount->cost = $row->costo;
                        $amount->umbral = $row->umbral;
                        $amount->save();
                    }
                } else if (!$productError) {

                    // \Log::info('categorySize: ' . $categorySizeId);
                    // \Log::info('color: ' . $colorId);
                    $amount = new ProductAmount;
                    $amount->amount = $row->existencia;
                    $amount->product_color_id = $colorId;
                    $amount->category_size_id = $categorySizeId;
                    $amount->price = $row->precio;
                    $amount->unit = $row->unidad;
                    $amount->presentation = $row->presentacion;
                    $amount->min = $row->minimo;
                    $amount->max = $row->maximo;
                    $amount->cost = $row->costo;
                    $amount->umbral = $row->umbral;
                    $amount->save();
                }
            }
        });
    }
}

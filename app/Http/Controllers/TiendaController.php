<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductAmount;
use App\Models\Category;
use App\Libraries\Cart;
use App\Models\Estado;
use App\Models\Discount;
use App\Models\Slider;
use App\Models\BankAccount;
use App\Models\Subcategory;
use App\Models\PurchaseDetails;
use App\Models\Purchase;
use App\Models\Setting;
use App\Models\Promotion;
use App\Models\Offer;
use App\Traits\Payable;
use Carbon\Carbon;
use Validator;
use Lang;
use Auth;

class TiendaController extends Controller
{

	use Payable;

	public function get(Request $request)
	{
		$categories=\DB::table('categories')->where('status',2)->get();
		$view_categories = $request->get('view_categories');
		$category_id = $request->get('category_id');
		$subcategory_id = $request->get('subcategory_id');
		$discount_id = $request->get('discount_id');
		$showPro = $request->get('showPro');
		$showPromotion = $request->get('showPromotion');
		$showOffer = $request->get('showOffer');
		$slider = Slider::inRandomOrder()->get();
		$minimumDiscount = $this->getMinimumDiscount(); // Trait Payable
		$quantityDiscount = $this->getQuantityDiscount(); // Trait Payable
		$carritoController = new CarritoController;
		$carrito = $carritoController->getCarrito();

		$this->completedDates();

		return View('tienda.home', [
			'_no_footer' => true,
			'slider' => $slider,
			'categories' => $categories,
			'view_categories' => $view_categories,
			'category_id' => $category_id,
			'subcategory_id' => $subcategory_id,
			'discount_id' => $discount_id,
			'showOffer' => $showOffer,
			'minimunPurchase' => Setting::getMinimunPurchase()->value,
			'showPro' => $showPro,
			'showPromotion' => $showPromotion,
			'minimumDiscount' => $minimumDiscount,
			'quantityDiscount' => $quantityDiscount,
			'carrito' => $carrito,
			'promotions' => [],
		]);
	}

	public function completedDates()
	{
		$now=Carbon::now()->format('Y-m-d');

		//COMPROBAR FECHAS YA VENCIDAS Y PASARLAS A INACTIVO
		//OFERTAS
		$ofertas=Offer::where('status',1)->get();
		if (!is_null($ofertas)) {
			for ($i=0; $i < count($ofertas); $i++) {
				$ofertas2=Offer::find($ofertas[$i]->id);
				// dd($now. '   ' .$ofertas2->end);
				if ($now > $ofertas2->end) {
					$ofertas2->status=0;
					$ofertas2->save();
				}
			}
		}

		//DESCUENTOS
		$descuentos=Discount::where('status',1)->get();
		if (!is_null($descuentos)) {
			for ($i=0; $i < count($descuentos); $i++) {
				$descuentos2=Discount::find($descuentos[$i]->id);
				if ($now > $descuentos2->end) {
					$descuentos2->status=0;
					$descuentos2->save();
				}
			}
		}

		//PROMOTIONS
		$promotions=Promotion::where('status',1)->get();
		if (!is_null($promotions)) {
			for ($i=0; $i < count($promotions); $i++) {
				$promotions2=Promotion::find($promotions[$i]->id);
				if ($now > $promotions2->end_date) {
					$promotions2->status=0;
					$promotions2->save();
				}
			}
		}

		return true;
	}

	public function getPromotions(){
		$currentDay = Carbon::now()->format('Y-m-d');
		$promotions = Promotion::with(['products' => function($q){
			$q->with(['product_amount' => function($q){
				$q->with(['product', 'product_color']);
			}]);
		}, 'uses'])
			->whereHas('products')
			->where('start_date', '<=', $currentDay)
			->where('end_date', '>=', $currentDay)
			->where('status', Promotion::STATUS_ACTIVE)
			->get();
		$proms = [];
		foreach($promotions as $promotion) {
			foreach($promotion->products as $product) {
				if(isset($product->product_amount)) {
					if($promotion->status != Promotion::STATUS_SOLD_OUT) {
						$promotion->status = $product->product_amount->amount >= $product->amount ? $promotion->status : Promotion::STATUS_SOLD_OUT;
					}
				}
			}
			if($promotion->status == Promotion::STATUS_ACTIVE) {
				array_push($proms, $promotion);
			}
		}
		return $proms;
	}

	public function search(Request $request)
	{
		$slider = Slider::all();
		$carritoController = new CarritoController;
		$carrito = $carritoController->getCarrito();
		$currentDay = Carbon::now()->format('Y-m-d');
		$proms = [];
		if(is_null($request->get('query'))){
			$promotions = Promotion::with(['products' => function($q){
				$q->with(['product_amount' => function($q){
					$q->with(['product', 'product_color']);
				}]);
			}, 'uses'])
				->whereHas('products')
				->where('start_date', '<=', $currentDay)
				->where('end_date', '>=', $currentDay)
				->where('status', Promotion::STATUS_ACTIVE)
				->get();
			
			foreach($promotions as $promotion) {
				foreach($promotion->products as $product) {
					if(isset($product->product_amount)) {
						if($promotion->status != Promotion::STATUS_SOLD_OUT) {
							$promotion->status = $product->product_amount->amount >= $product->amount ? $promotion->status : Promotion::STATUS_SOLD_OUT;
						}
					}
				}
				if($promotion->status == Promotion::STATUS_ACTIVE) {
					array_push($proms, $promotion);
				}
			}
		}
		return View('tienda.home', [
			'query' => $request->get('query'),
			'slider' => $slider,
			'minimunPurchase' => Setting::getMinimunPurchase()->value,
			'minimumDiscount' => $this->getMinimumDiscount(),
			'quantityDiscount' => $this->getQuantityDiscount(),
			'carrito' => $carrito,
			'promotions' => $proms
		]);
	}

	public function ver($id)
	{
		$categories=\DB::table('categories')->where('status',2)->get();
		// dd($categories);
		return View('tienda.ver')->with(['id' => $id,'categories'=>$categories]);
	}

	public function add(Request $request)
	{
		// $reglas = [
		// 	'talla' => 'required',
		// 	'color' => 'required'
		// ];
		// $atributos = [
		// 	'talla' => Lang::get('Controllers.Atributos.Talla'),
		// 	'color' => Lang::get('Controllers.Atributos.Color')
		// ];
		// $validacion = Validator::make($request->all(), $reglas);
		// $validacion->setAttributeNames($atributos);
		// if ($validacion->fails()) {
		// 	return response()->json([
		// 		'result' => false,
		// 		'error' => $validacion->messages()->first()
		// 	]);
		// } else {
		if (Auth::check() && Auth::user()->type == '2' && $request->cantidad < 12) {
			return response()->json([
				'result' => false,
				'error' => Lang::get('Page.Carrito.Piezas')
			]);
		}

		$amountId = $request->amount_id;
		$item = [
			'cantidad' => $request->cantidad,
			'id' => $request->id,
			// 'talla' => $request->talla,
			// 'color' => $request->color,
			'amount_id' => $amountId,
		];

		$producto = Product::find($request->id);
		$discount = $producto->discount;
		$producto->amount = ProductAmount::where('id', $amountId)
			->first();

		if ($request->cantidad > $producto->amount->amount) {
			return response()->json([
				'result' => false,
				'error' => Lang::get('Page.Carrito.NoCantidad') . $producto->amount->amount
			]);
		}

		Cart::set($item);
		return response()->json([
			'discountAvialable' => $this->discountAvialable($amountId, $discount),
			'result' => true,
			'carrito' => Cart::get()
		]);
		// }
	}

	public function getProducto(Request $request)
	{
		$producto = Product::with(['images', 'subcategories', 'categories' => function ($q) {
			$q->with(['sizes' => function ($q) {
				$q->where('status', '1');
			}]);
		}, 'colors' => function ($q) {
			$q->with(['amounts' => function($q){
				$q->where('amount', '>', 0);
			}])
				->where('status', '1');
		}])->where('status', '1')->where('id', $request->id)->first();

		$related = Product::with(['images', 'subcategories', 'categories' => function ($q) {
			$q->with(['sizes' => function ($q) {
				$q->where('status', '1');
			}]);
		}, 'favorites' => function ($q) {
			$user_id = Auth::guest() ? 0 : Auth::user()->id;
			$q->where('user_id', $user_id);
		}, 'colors' => function ($q) {
			$q->with(['amounts' => function($q){
				$q->where('amount', '>', 0);
			}])
				->where('status', '1');
		}])->whereHas('categories', function ($q) use ($producto) {
			$q->where('categories.id', $producto->category_id);
		})
			->whereHas('colors', function ($q) {
				$q->whereHas('amounts', function ($q) {
					$q->where('amount', '>', '0');
				});
			})
			->where('id', '!=', $request->id)->where('status', '1')->inRandomOrder()->get()->take(4);

		return response()->json([
			'result' => true,
			'producto' => $producto,
			'carrito' => Cart::get(),
			'related' => $related
		]);
	}

	public function ajax(Request $request)
	{
		$products_ids = Product::select('id')
		->when($request->query, function ($query) use ($request) {
			return $query->where('name', 'like', '%' . strtolower($request->get('query')) . '%')
			->orWhere(function($query2) use ($request) {
				$query2->whereHas('subcategories', function($query3) use ($request) {
					$query3->where('name', 'like', '%' . strtolower($request->get('query')) . '%');
				});
			})
			->orWhere(function($query2) use ($request) {
				$query2->whereHas('categories', function($query3) use ($request) {
					$query3->where('name', 'like', '%' . strtolower($request->get('query')) . '%');
				});
			});
		})
		->pluck('id');

		$categories = Category::select('id', 'name')->with([
			// 'products' => function ($q) use ($request, $withProducts) {
			// 	$q->select('id', 'name', 'name_english', 'price_1', 'taxe_id', 'category_id', 'subcategory_id', 'coin', 'pro', 'variable')
			// 		// ->where(function ($q) use ($request) {
			// 		// 	$q->when(!$request->category_id, function ($q) {
			// 		// 		$q->limit(12);
			// 		// 	});
			// 		// })
			// 		->when($request->query, function ($query) use ($request) {
			// 			return $query->where('name', 'like', '%' . strtolower($request->get('query')) . '%');
			// 		})->with($withProducts)
			// 		->when(!is_null($request->subcategory_id), function ($query) use ($request) {
			// 			$query->whereHas('subcategories', function ($q) use ($request) {
			// 				$q->where('id', $request->subcategory_id);
			// 			});
			// 		})
			// 		->where('status', "1")
			// 		->whereHas('colors', function ($q) {
			// 			$q->whereHas('amounts', function ($q) {
			// 				$q->where('amount', '>', '0');
			// 			});
			// 		});
			// },
			'subcategories' => function ($q) {
				$q->where('status', "1");
			}
		])
			->when($request->category_id, function ($query) use ($request) {
				$query->where('id', $request->category_id);
			})
			->whereHas('products', function ($query) use ($request, $products_ids) {
				$query->when($request->query, function ($query) use ($products_ids) {
					return $query->whereIn('id', $products_ids);
				});
			})
			->orderBy('name', 'asc')->where('status', '1')
			->paginate(4);

		$withProducts = $this->withProducts();

		$categories->each(function ($category) use ($request, $withProducts, $products_ids) {
			$category->products = Product::where('category_id', $category->id)->inRandomOrder()
				->select('id', 'name', 'name_english', 'price_1', 'taxe_id', 'category_id', 'subcategory_id', 'coin', 'pro', 'variable')
				->with($withProducts)
				->when(!is_null($request->subcategory_id), function ($query) use ($request) {
					$query->whereHas('subcategories', function ($q) use ($request) {
						$q->where('id', $request->subcategory_id);
					});
				})
				->whereIn('id', $products_ids)
				->where('status', "1")
				->whereHas('colors', function ($q) {
					$q->whereHas('amounts', function ($q) {
						$q->where('amount', '>', '0');
					});
				})
				->when((!$request->category_id) && (!$request->query), function ($q) {
					$q->take(12);
				})
				->get();
		});

		return response()->json([
			'result' => true,
			'categories' => $categories,
			'subcategory_id' => $request->subcategory_id,
			// 'products_pro' => $products_pro,
		]);
	}

	public function filters(Request $request)
	{
		$filters = Category::select('id', 'name')->with([
			'subcategories' => function ($q) {
				$q->select('id', 'name', 'category_id')
					->where('status', "1");
			}
		])	
			->where('status', "1")
			->whereHas('products')
			->orderBy('name', 'asc')
			->get();

		return response()->json([
			'result' => true,
			'filters' => $filters,
		]);
	}

	public function discounts(Request $request)
	{
		$withProducts = $this->withProducts();

		$products_offer = Product::select('id', 'name', 'name_english', 'price_1', 'taxe_id', 'category_id', 'subcategory_id', 'coin', 'pro', 'variable')->whereHas('offersActive', function ($q) {
			$q->whereDate('start', '<=', date('Y-m-d'));
		})->inRandomOrder()
			->whereHas('colors', function ($q) {
				$q->whereHas('amounts', function ($q) {
					$q->where('amount', '>', '0');
				});
			})->with($withProducts)->where('status', '1')
			
			->get();

		$discounts = Discount::where('status', 1)->where('start', '<=', date('Y-m-d'))
			->with(['products' => function ($q) use ($withProducts) {
				$q->select('id', 'name', 'name_english', 'price_1', 'taxe_id', 'category_id', 'subcategory_id', 'coin', 'pro', 'variable')
					->where('status', "1")
					->with($withProducts)
					->whereHas('colors', function ($q) {
						$q->whereHas('amounts', function ($q) {
							$q->where('amount', '>', '0');
						});
					});
			}])
			->get();

		return response()->json([
			'products_offer' => $products_offer,
			'discounts' => $discounts,
		]);
	}

	public function getPro(Request $request)
	{
		$withProducts = $this->withProducts();

		$products_pro = Product::select('id', 'name', 'name_english', 'price_1', 'taxe_id', 'category_id', 'subcategory_id', 'coin', 'pro', 'variable')
			->where('pro', 1)->with($withProducts)->where('status', '1')
			->whereHas('colors', function ($q) {
				$q->whereHas('amounts', function ($q) {
					$q->where('amount', '>', '0');
				});
			})
			->when($request->showPro != '1', function ($q) {
				$q->limit(12);
			})
			->inRandomOrder()
			->get();

		return response()->json([
			'products_pro' => $products_pro,
		]);
	}

	public function favoritos(Request $request)
	{
		return 'Pagina en construcción';
	}

	public function verificacion(Request $request)
	{
		// if (Cart::count() == 0) {
		// 	return redirect('/');
		// }

		// $total = $this->getTotalToPay(
		// 	\App('\App\Http\Controllers\CarritoController')->getCarrito(),
		// 	0
		// );

		// if ($total < floatval(Setting::getMinimunPurchase()->value)) {
		// 	return redirect('/');
		// }

		$zelles = BankAccount::where('method', BankAccount::ZELLE)
			->get();

		$banks = BankAccount::where('status', 1)->with('bank')
			->whereNotIn('id', $zelles->pluck('id'))
			->get();
		// $carritoController = new CarritoController;
		// $carrito = $carritoController->getCarrito();
		$estados = Estado::where('status', 1)->get()->pluck('nombre', 'id');

		$minimumDiscount = $this->getMinimumDiscount(); // Trait Payable
		$quantityDiscount = $this->getQuantityDiscount(); // Trait Payable

		return view('tienda.verificacion', [
			'carrito' => [],
			'banks' => $banks,
			'estados' => $estados,
			'zelles' => $zelles,
			'minimumDiscount' => $minimumDiscount,
			'quantityDiscount' => $quantityDiscount
		]);
	}

	public function getAutocomplete(Request $request)
	{
		$total = 15;
		$suggestions = Product::where('name', 'like', '%' . $request->search . '%')
			->whereHas('colors', function ($q) {
				$q->whereHas('amounts', function ($q) {
					$q->where('amount', '>', '0');
				});
			})
			// ->get(['name'])
			->with(['categories'=> function ($query) {
				$query->select('id', 'name');
			}])
			->take($total)
			->where('status', '1')
			->get(['name', 'id', 'category_id']);
		$categories = [];
		$subtotal = $total - count($suggestions);
		if($subtotal > 0){
			$categories = Category::where('name', 'like', '%' . $request->search . '%')
				->take($subtotal)
				->where('status', '1')
				->get(['name', 'id']);
		}
		$subtotal = $total - count($categories);
		if($subtotal > 0){
			$subCategories = Subcategory::where('name', 'like', '%' . $request->search . '%')
				->with(['categories' => function ($query) {
					$query->select('id', 'name');
				}])
				->take($subtotal)
				// ->groupBy('name')
				->where('status', '1')
				->get(['name', 'id', 'category_id']);
		}

		return response()->json([
			'suggestions' => $suggestions,
			'categories' => $categories,
			'subCategories' => $subCategories
		]);
	}

	private function withProducts()
	{
		return [
			'images',
			'taxe' => function ($q) {
				$q->select('id', 'name');
			},
			// 'categories' => function ($q) {
			// 	$q->with(['sizes' => function ($q) {
			// 		$q->where('status', '1');
			// 	}]);
			// },
			'colors' => function ($q) {
				$q->with(['amounts' => function ($q) {
					$q->where('amount', '>', '0');
				}])->where('status', '1');
			},
			'favorites' => function ($q) {
				$user_id = Auth::guest() ? 0 : Auth::user()->id;
				$q->where('user_id', $user_id);
			}
		];
	}
}

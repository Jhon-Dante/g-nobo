<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TiendaController;

// Route::get('/','HomeController@intro')->middleware('intro');
Route::get('/', 'TiendaController@get');
Route::get('/search', 'TiendaController@search')->name('search');
Route::get('/getPromotions', 'TiendaController@getPromotions')->name('getPromotions');
Route::get('home', 'HomeController@get');
Route::get('nosotros', 'HomeController@nosotros');

// Route::group(['middleware' => 'active:2'],function() {
// 	Route::get('mundo','BlogController@get');
// 	Route::get('mundo/view/{id}','BlogController@view');
// });

// Route::get('aliados','HomeController@aliados')->middleware('active:4');
// Route::get('condiciones', 'HomeController@condiciones');
Route::get('terminos', 'HomeController@terminos');
Route::get('contacto', 'HomeController@getContacto');
Route::post('contacto', 'HomeController@postContacto');



// Monedas
Route::get('change-currency/{currency}', 'CurrencyController@change');

Route::get('shipping-fees/get', 'Admin\ShippingFeeController@getAll');

// Route::group(['middleware' => 'natural'], function () {

	// Tienda
	// Route::group(['middleware' => 'active:3'], function () {

		Route::get('favoritos', 'FavoriteController@index')->middleware('auth');
		Route::get('favoritos/ajax', 'FavoriteController@ajax');
		Route::post('favoritos/store', 'FavoriteController@store');
		Route::post('favoritos/destroy', 'FavoriteController@destroy');
		Route::get('tienda', 'TiendaController@get');
		Route::post('tienda/ajax', 'TiendaController@ajax');
		Route::post('tienda/filters', 'TiendaController@filters');
		Route::post('tienda/discounts', 'TiendaController@discounts');
		Route::post('tienda/get-pro', 'TiendaController@getPro');
		Route::post('tienda/autocomplete', 'TiendaController@getAutocomplete');
		Route::get('tienda/ver/{id}', 'TiendaController@ver');
		Route::post('tienda/get', 'TiendaController@getProducto');
		Route::post('tienda/add', 'TiendaController@add');
		Route::get('verificacion', 'TiendaController@verificacion');
	// });

	// Carrito
	// Route::group(['middleware' => 'active:5'], function () {

		Route::post('carrito/ajax', 'CarritoController@ajax');
		Route::post('carrito/delete', 'CarritoController@delete');
		Route::post('carrito/check', 'CarritoController@check');
		Route::get('mercadopago', 'MPController@create');
		Route::get('transferencia', 'TransferenciaController@get');
		Route::post('transferencia', 'TransferenciaController@post');
		Route::get('carrito/response', 'MPController@response');
		Route::post('paypal/purchase-data', 'PaypalController@purchaseData');
		Route::post('stripe/payment', 'StripeController@payment');
	// });

	// Perfil
	// Route::group(['middleware' => ['active:6', 'auth']], function () {

		Route::get('perfil', 'PerfilController@get');
		Route::post('perfil', 'PerfilController@post');
		Route::post('password', 'PerfilController@password');
		Route::post('perfil/pedidos', 'PerfilController@pedidos');
		Route::get('verificacion', 'TiendaController@verificacion');
	// });

	// Route::group(['middleware' => 'auth'], function () {
		Route::get('payment', [
			'as' => 'payment',
			'uses' => 'PaypalController@postPayment',
		]);

		Route::get('payment/status', [
			'as' => 'payment.status',
			'uses' => 'PaypalController@getPaymentStatus',
		]);
	// });
// });

// Route::group(['middleware' => 'juridico', 'prefix' => 'juridico', 'namespace' => 'Juridico'], function () {

	// Tienda
	// Route::group(['middleware' => 'active:3'], function () {

		Route::get('tienda', 'TiendaController@get');
		Route::post('tienda/ajax', 'TiendaController@ajax');
		Route::get('tienda/ver/{id}', 'TiendaController@ver');
		Route::post('tienda/get', 'TiendaController@getProducto');
		Route::post('tienda/add', 'TiendaController@add');
	// });

	// Carrito
	// Route::group(['middleware' => 'active:5'], function () {

		Route::get('carrito', 'CarritoController@get');
		Route::post('carrito/ajax', 'CarritoController@ajax');
		Route::post('carrito/delete', 'CarritoController@delete');
		Route::post('carrito/check', 'CarritoController@check');
		Route::get('mercadopago', 'MPController@create');
		Route::get('transferencia', 'TransferenciaController@get');
		Route::post('transferencia', 'TransferenciaController@post');
		Route::get('carrito/response', 'MPController@response');
	// });

	// Perfil
	// Route::group(['middleware' => ['active:6', 'auth']], function () {

		Route::get('perfil', 'PerfilController@get');
		Route::post('perfil', 'PerfilController@post');
		Route::post('password', 'PerfilController@password');
		Route::post('perfil/pedidos', 'PerfilController@pedidos');
	// });

	Route::group(['middleware' => 'auth'], function () {
		Route::get('payment', [
			'as' => 'payment.juridico',
			'uses' => 'PaypalController@postPayment',
		]);

		Route::get('payment/status', [
			'as' => 'payment.juridico.status',
			'uses' => 'PaypalController@getPaymentStatus',
		]);
	});
// });

// Auth
// Route::group(['middleware' => 'login'], function () {

	// Route::get('login','AuthController@getLogin');
	Route::post('login', 'AuthController@postLogin');
	Route::get('register', 'AuthController@getRegister');
	Route::post('register', 'AuthController@postRegister');

	Route::get('recuperar', 'ResetController@get');
	Route::post('recuperar/send', 'ResetController@send');
	Route::post('recuperar/codigo', 'ResetController@codigo');
	Route::post('recuperar/reenviar', 'ResetController@reenviar');
	Route::post('recuperar/recuperar', 'ResetController@recuperar');
// });

Route::get('logout', 'AuthController@logout');

// Lang
Route::get('lang/{lang}', 'LangController@change');

// Admin

Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
	// Home
	Route::get('/', 'AdminController@index');
	Route::post('login', 'AuthController@singIn');

	Route::group(['middleware' => ['Auth', 'isAdmin']], function () {
		// Exchange rate
		Route::resource('exchange_rate', 'ExchangeRateController');
		Route::get('home', 'AdminController@home');
		// Sizes
		Route::resource('sizes', 'SizeController');
		Route::get('sizes-all', 'SizeController@all');
		// Subcategories
		Route::post('subcategory/{id}', 'CategoryController@getSubCategory');
		// Categories
		Route::resource('categories', 'CategoryController');
		// Collection
		Route::resource('collections', 'CollectionController');
		// Desigs
		Route::resource('designs', 'DesignController');
		Route::get('designs-all', 'DesignController@allData');
		// Products
		Route::post('products/export', 'ProductController@exportExcel');
		Route::post('products/active', 'ProductController@active');
		Route::post('products/get', 'ProductController@getProducts');
		Route::post('products/import', 'ProductController@import');
		Route::post('product/postear/{id}', 'ProductController@postear');
		Route::post('product/pro/{id}', 'ProductController@pro');
		Route::post('update-images', 'ProductController@updateImage');
		Route::resource('products', 'ProductController');
		//Replenishemtns
		Route::resource('replenishment', 'ReplenishmentController');
		Route::post('replenishment/filter', 'ReplenishmentController@filter');
		Route::post('replenishment/export', 'ReplenishmentController@export');
		Route::post('replenishment/pdf', 'ReplenishmentController@pdf');
		// Report 
		Route::get('reports/{report}', 'ReportController@index');
		Route::get('reports/purchases/{type}/{from}/{to}', 'ReportController@purchases');
		Route::get('reports/orders/{from}/{to}', 'ReportController@orders');
		Route::get('reports/products/{from}/{to}', 'ReportController@products');
		Route::post('reports/{report}/excel', 'ReportController@excel');
		Route::post('reports/{report}/pdf', 'ReportController@pdf');
		//pdf 
		Route::post('pedidos-pdf/{purchase}', 'PdfPedidosController@pdfview')->name('pedidos-pdf');
		// Us
		Route::resource('us', 'UsController');
		// Allies
		Route::resource('allies', 'AllyController');
		Route::post('allies/update-image', 'AllyController@updateImages');
		Route::post('allies/delete-images', 'AllyController@delete');
		// Blogs
		Route::resource('blogs', 'GenerateBlogController');
		Route::post('blogs/update-image', 'GenerateBlogController@updateImage');
		Route::post('blogs/delete-image', 'GenerateBlogController@deleteImage');

		//Wholesalers
		Route::post('wholesalers/update-images', 'WholesalerController@updateImages');
		Route::post('wholesalers/delete-images', 'WholesalerController@deleteImages');
		Route::resource('wholesalers', 'WholesalerController');
		Route::get('filters/get', 'WholesalerController@getFilters');
		Route::get('purchases', 'PurchaseController@index');
		Route::post('purchases/search', 'PurchaseController@date');
		Route::post('purchases/getDetails', 'PurchaseController@getDetails');
		Route::post('purchases/approve/{id}', 'PurchaseController@approve');
		Route::post('purchases/reject/{id}', 'PurchaseController@reject');
		Route::post('purchases/export', 'PurchaseController@exportExcel');

		// Banners
		Route::get('banners', 'BannerController@index');
		Route::post('banners/upload', 'BannerController@upload');
		Route::post('banners/delete', 'BannerController@destroy');

		// Social Networrk
		Route::resource('social', 'SocialController');

		/*Route::resource('blogs', 'BlogController');
			Route::post('blogs/update-images', 'BlogController@updateImages');
			Route::post('blogs/delete-images', 'BlogController@delete');
			*/
		Route::get('clients', 'ClientController@index');
		Route::post('clients/export', 'ClientController@exportExcel');
		Route::post('clients/switch/{id}', 'ClientController@changeStatus');
		Route::post('clients/delete/{id}', 'ClientController@delete');
		Route::post('clients/update', 'ClientController@update');
		Route::get('clients/all', 'ClientController@getAll');

		Route::get('profile', 'ProfileController@profile');
		Route::post('profile', 'ProfileController@update');
		Route::resource('banks', 'BankController');
		Route::post('banks/switch/{id}', 'BankController@estatus');

		Route::get('terms', 'TermController@index');
		Route::post('terms', 'TermController@store');

		Route::resource('municipalities', 'MunicipalityController');
		Route::post('municipalities/{id}/free', 'MunicipalityController@free');
		Route::get('municipalities/get-by-state/{id}', 'MunicipalityController@getByState');
		Route::resource('estados', 'EstadosController');
		Route::post('estados/{id}/free', 'EstadosController@free');
		Route::post('estados/{id}/status', 'EstadosController@status');

		//Shipping Fees
		Route::resource('shipping-fees', 'ShippingFeeController');
		Route::get('shipping-fees/get', 'ShippingFeeController@getAll');

		// Promotions
		Route::get('promotions/get', 'PromotionController@getAll');
		Route::resource('promotions', 'PromotionController');
		Route::post('promotions/{promotion}/status', 'PromotionController@status');


		Route::resource('settings', 'SettingController');
		Route::resource('offers', 'OfferController');
		Route::post('offers/{offer}/status', 'OfferController@status');
		Route::resource('discounts', 'DiscountController');
		Route::post('discounts/{discount}/status', 'DiscountController@status');
		Route::resource('taxes', 'TaxeController');
		Route::post('taxes/{taxe}/status', 'TaxeController@status');
	});
});

Route::get('remove-expired-offers', function () {
	Artisan::call('offers:remove');

	return redirect('/');
});

Route::get('remove-expired-promotions', function () {
	Artisan::call('promotions:remove');
	dd('Verificando promociones expiradas');
});

// Route::get('/test-mail', function() {
// 	return view('emails.compra-aprobada', [
// 		'user' => Auth::user(),
// 		'password' => 'sherzo1607',
// 		'compra' => \App\Models\Purchase::find(13),
// 		'codigo' => 'test',
// 		'statusName' => 'Aprobada'
// 	]);
// });

Route::get('/cacheter', function() {
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    return 'DONE'; //Return anything
});

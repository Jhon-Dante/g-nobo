<?php

	namespace App\Http\Controllers;

	use Illuminate\Http\Request;
	use ArmorPayments\ArmorPayments;
	use App\Libraries\IpCheck;
	use App\Libraries\Cart;

	class PayoneerController extends Controller {
	    
	  //   private $client;

	  //   public function __construct() {
	  //   	if (IpCheck::get() == 'VE' || Cart::count() <= 0) {
	  //   		return abort(403);
	  //   	}
			// $this->_api_context = new \ArmorPayments\Api(env('PAYONEER_KEY'), env('PAYONEER_SECRET'), env('PAYONEER_SANDBOX'));
	  //   }

	  //   public function get() {

	  //   }
	}

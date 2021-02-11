<?php
	
	namespace App\Libraries;
	use Session;

	class Money {

		public static function get($cant) {
			$currency = Session::get('currentCurrency', '2');
			return number_format($cant,2,'.',',').($currency == '1' ? ' Bs.' : ' USD');
		}

		public static function getByCurrency($cant, $currency) 
		{
			return number_format($cant,2,'.',',').($currency == '1' ? ' Bs.' : ' USD');
		}
	}
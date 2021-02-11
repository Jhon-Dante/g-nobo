<?php

	namespace App\Libraries;
	use App\Libraries\IpCheck;
	use Session;

	class CalcPrice {

		public static function get($precio,$coin,$exchange) {
			$currency = Session::get('currentCurrency', '2');
			$price = $precio;
			if ($coin == '1' && $currency == '2') {
				$price = $price / $exchange;
			}
			else if ($coin == '2' && $currency == '1') {
				$price = $price * $exchange;
			}
			return $price;
		}

		public static function getByCurrency($precio,$coin,$exchange, $currency) {
			$price = $precio;
			if ($coin == '1' && $currency == '2') {
				$price = $price / $exchange;
			}
			else if ($coin == '2' && $currency == '1') {
				$price = $price * $exchange;
			}
			return $price;
		}
	}
<?php

	namespace App\Libraries;
	use Request;

	class IpCheck {

		public static function get() {
			$ip = Request::ip();
			$query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));
			$_ip = 'VE';
			if ($query && $query['status'] == 'success') {
			  $_ip = $query['countryCode'];
			}
			return $_ip;
		}
	}
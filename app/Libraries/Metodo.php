<?php

	namespace App\Libraries;

	class Metodo {

		public static function get($num) {
			$respuesta = '';
			switch ($num) {
				case 1:
					$respuesta = "MercadoPago";
					break;

				case 2:
					$respuesta = "PayPal";
					break;

				case 3:
					$respuesta = 'Transferencia';
					break;
			}
			return $respuesta;
		}
	}
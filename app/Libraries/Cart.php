<?php

	namespace App\Libraries;
	use Cookie;
	use Auth;

	class Cart {

		public static $items = [];
		public static $name = "WaraCarrito";

		private static function init() {
			if (Cookie::get(self::$name) != null) {
				self::$items = Cookie::get(self::$name);
			}
		}

		public static function count() {
			self::init();
			return count(self::$items);
		}

		public static function get() {
			self::init();
			return self::$items;
		}

		public static function in($producto,$_index = false) {
			self::init();
			$in = false;
			$index = null;
			foreach(self::$items as $key => $item) {
				if (!Auth::check() || Auth::user()->type == 1) {
					try {
						if ($producto['amount_id'] == $item['amount_id']) {
							$in = true;
							$index = $key;
						}
					}
					catch(\Exception $e) {
						
					}					
				}
				else {
					if ($producto['id'] == $item['id']) {
						$in = true;
						$index = $key;
					}
				}				
			}
			if ($_index)
				return $index;
			else
				return $in;
		}

		public static function set($item) {
			self::init();
			if (self::in($item)) {
				self::$items[self::in($item,true)] = $item;
			}
			else {
				self::$items[] = $item;
			}
			Cookie::queue(self::$name,self::$items, 2628000);
		}

		public static function delete($item) {
			self::init();
			array_splice(self::$items,self::in($item,true),1);
			Cookie::queue(self::$name,self::$items, 2628000);
		}

		public static function destroy() {
			Cookie::queue(
			    Cookie::forget(self::$name)
			);
		}
	}
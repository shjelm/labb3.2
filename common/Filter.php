<?php

namespace common;

class Filter {
	public static function sanitizeString($string) {
		$original = $string;
		$string = ltrim($string);
		$string = rtrim($string);
		$string = strip_tags($string);
		return $string;
	}
	
	public static function hasTags($string) {
		$sanitized = strip_tags($string);
		
		if ($sanitized != $string) {
			return true;
		}
		return false;
	}
}

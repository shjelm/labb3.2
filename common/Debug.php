<?php

require_once("common/model/DebugItem.php");

class Debug {

	private static $debugItems = array();
	

	public static function log($string, $trace = false, $object = null) {
		self::$debugItems[] = new \common\model\DebugItem($string, $trace, $object);
	}
	
	public static function getList() {
		return self::$debugItems;
	}
}

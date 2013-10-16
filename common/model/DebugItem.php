<?php

namespace common\model;

class DebugItem {

	public $item;

	public $object;

	public $debugBacktrace;

	public $calledFromFile;
	
	
	public function __construct($string, $trace = false, $object = null) {
		$this->item = $string;
		if ($object != null)
			$this->object = var_export($object, true);
		
		$this->debugBacktrace = debug_backtrace();
		$this->calledFromFile = $this->cleanFilePath($this->debugBacktrace[1]["file"]);
		if (!$trace) {
			$this->debugBacktrace = null;
		}
		
	}
	
	private function cleanFilePath($path) {
		return $path;
	}
	 
}
<?php

namespace common\model;


class PHPFileStorage {
	
	public function __construct($filePath) {
		$this->filePath = $filePath;

		if (file_exists($this->filePath) == FALSE) {
			file_put_contents($this->filePath, "<?php");
		}
	}

	public function writeItem($key, $content) {
		$safeKey = urlencode($key);
		$safeContent = urlencode($content);

		$newLine = "\n//<>$safeKey<>$safeContent";
		
		$fileHandle = fopen($this->filePath, "a");
		fwrite($fileHandle, $newLine, strlen($newLine)); 
		fclose($fileHandle);

		\Debug::log("Wrote to PHPFileStorage", true, $content);
	}

	public function readItem($key) {
		$safeKey = urlencode($key);

		$fileContents = file_get_contents($this->filePath);
		$fileLines = explode("\n", $fileContents);
		$numLines = count($fileLines);
		for ($i = 1; $i < $numLines; $i++) {
			$line = &$fileLines[$i];
			$lineParts = explode("<>", $line);

			if (strcmp($safeKey, $lineParts[1]) == 0) {
				$contentFound = $lineParts[2];
			}
		}
		if (isset($contentFound)) {
			return urldecode($contentFound);
		}
		throw new \Exception("Content not found");
	}

	public function readAll() {
		$ret = array();

		$fileContents = file_get_contents($this->filePath);
		$fileLines = explode("\n", $fileContents);
		$numLines = count($fileLines);
		for ($i = 1; $i < $numLines; $i++) {
			$line = &$fileLines[$i];
			$lineParts = explode("<>", $line);

			$ret[urldecode($lineParts[1])] = urldecode($lineParts[2]); 
		}
		
		return $ret;
	}
}

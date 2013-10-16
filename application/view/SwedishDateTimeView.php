<?php

namespace application\view;

class SwedishDateTimeView {

	public function getTimeString($timeStamp) {
		$month = date("m");
		$day = date("w");
		$dayofMonth = date("j");
		$year = date("Y");
		$timeSeconds = date("H:i:s"); 
		
		$dayname = $this->translateDay($day);
		$monthName = $this->translateMonth($month);
		
		return "<p>$dayname, den $dayofMonth $monthName år $year. Klockan är [$timeSeconds]. " ;
	}
	
	
	private function translateDay($dayOfWeekInteger) {
		assert($dayOfWeekInteger >= 1);
		assert($dayOfWeekInteger <= 7);

		$dayname = "";
		switch ($dayOfWeekInteger) {
			case 1 : $dayname = "Måndag"; break;
			case 2 : $dayname = "Tisdag"; break;
			case 3 : $dayname = "Onsdag"; break;
			case 4 : $dayname = "Torsdag"; break;
			case 5 : $dayname = "Fredag"; break;
			case 6 : $dayname = "Lördag"; break;
			case 7 : $dayname = "Söndag"; break;
		}
		return $dayname;
	}
	
	private function translateMonth($monthsInteger) {
		assert($monthsInteger >= 1);
		assert($monthsInteger <= 12);
		$monthNames = array("Januari", 
							"Februari",
							"Mars",
							"April",
							"Maj",
							"Juni",
							"Juli",
							"Augusti", 
							"September", 
							"Oktober", 
							"November", 
							"December");
	 	return $monthNames[$monthsInteger-1];
	}
}
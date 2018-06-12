<?php

namespace Yii2NsLib\NsDate;

// Trasformazione date dal formato Mysql in formato Italiano e viceversa
class NsDate {
	static function ymd2dmy($d) {
		return substr($d,8,2).'-'.substr($d,5,2).'-'.substr($d,0,4);
	}

	static function dmy2ymd($d) {
		return substr($d,6,4).'-'.substr($d,3,2).'-'.substr($d,0,2);
	}

	static function mese($n) {
		$m = "";
		switch ($n) {
			case 1:
				$m = "Gen";
				break;
			case 2:
				$m = "Feb";
				break;
			case 3:
				$m = "Mar";
				break;
			case 4:
				$m = "Apr";
				break;
			case 5:
				$m = "Mag";
				break;
			case 6:
				$m = "Giu";
				break;
			case 7:
				$m = "Lug";
				break;
			case 8:
				$m = "Ago";
				break;
			case 9:
				$m = "Set";
				break;
			case 10:
				$m = "Ott";
				break;
			case 11:
				$m = "Nov";
				break;
			case 12:
				$m = "Dic";
				break;
		}
		return $m;
	}

	static function mesedalladata($d,$y=false) {
		$n = date("n",strtotime(NsDate::dmy2ymd($d)));
		switch ($n) {
			case 1:
				$m = "Gennaio";
				break;
			case 2:
				$m = "Febbraio";
				break;
			case 3:
				$m = "Marzo";
				break;
			case 4:
				$m = "Aprile";
				break;
			case 5:
				$m = "Maggio";
				break;
			case 6:
				$m = "Giugno";
				break;
			case 7:
				$m = "Luglio";
				break;
			case 8:
				$m = "Agosto";
				break;
			case 9:
				$m = "Settembre";
				break;
			case 10:
				$m = "Ottobre";
				break;
			case 11:
				$m = "Novembre";
				break;
			case 12:
				$m = "Dicembre";
				break;
		}
		if ($y) {
			$m .= " ".date("Y",strtotime(NsDate::dmy2ymd($d)));
		}
		return $m;
	}

	static function settimanadalladata($d,$y=false) {
		$n = date("W",strtotime(NsDate::dmy2ymd($d)));
		$m = "Settimana ".$n;
		if ($y) {
			$m .= "/".date("o",strtotime(NsDate::dmy2ymd($d)));
		}
		
		return $m;
	}

	static function giornodalladata($d) {
		$n = date("w",strtotime(NsDate::dmy2ymd($d)));
		$m = NsDate::giorno($n);
	
		return $m;
	}
	
	static function giorno($n) {
		$m = "";
		switch ($n) {
			case 0:
				$m = "Domenica";
				break;
			case 1:
				$m = "Lunedì";
				break;
			case 2:
				$m = "Martedì";
				break;
			case 3:
				$m = "Mercoledì";
				break;
			case 4:
				$m = "Giovedì";
				break;
			case 5:
				$m = "Venerdì";
				break;
			case 6:
				$m = "Sabato";
				break;
		}
		return $m;
	}

	
	// Calcolo differenza tra 2 date in Anni-Mesi-Settimane-Giorni
	static function annitraduedate($datai, $dataf) {
		$datai = NsDate::dmy2ymd($datai);
		$dataf = NsDate::dmy2ymd($dataf);
		if (NsDate::verifyDate($datai) and NsDate::verifyDate($dataf)) {
			$date1 = new DateTime($datai);
			$date2 = new DateTime($dataf);
		
			$interval = $date1->diff($date2);
	
			return $interval->y;
		} else {
			return 0;
		}
	}	

	// Calcolo differenza tra 2 date in Giorni
	static function giornitraduedate($datai, $dataf) {
		$datai = NsDate::dmy2ymd($datai);
		$dataf = NsDate::dmy2ymd($dataf);
		if (NsDate::verifyDate($datai) and NsDate::verifyDate($dataf)) {
			$date1 = new DateTime($datai);
			$date2 = new DateTime($dataf);
	
			$interval = $date1->diff($date2)->format("%r%a");
	
			return $interval;
		} else {
			return 0;
		}
	}
	
	static function verifyDate($xdate) {
		$dateTime = DateTime::createFromFormat('Y-m-d', $xdate);
		$errors = DateTime::getLastErrors();
		if (!empty($errors['warning_count'])) {
			return false;
		}
		return $dateTime !== false;
	}
	
	static function oraattuale() {
		return (date("H:i", time()));
	}
	
	// Aggiunge n giorni ad una data in formato g-m-Y tornando una data in g-m-Y
	static function addGiorni($data, $n) {
		$data = NsDate::dmy2ymd($data);

		return date("d-m-Y", strtotime("$data +$n days"));
	}
	
}

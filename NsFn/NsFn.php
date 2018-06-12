<?php

namespace Yii2NsLib\NsFn;

// Funzioni Pubbliche
class NsFn {
	static $tmpMessage;
	
	// Gestione Timeout Ajax
	static function ajaxUserTimeout() {
		if (Yii::app()->components['user']->loginRequiredAjaxResponse) {
			Yii::app()->clientScript->registerScript('ajaxLoginRequired', '
				function loginRequiredAjaxResponse(event, request, options) {
					if (request.responseText == "'.Yii::app()->components['user']->loginRequiredAjaxResponse.'") {
						window.location.reload(true);
					}
				}'
			);
		}
	}
	
	// Ip del client collegato
	static function get_client_ip() {
		$ipaddress = '';
		if ($_SERVER['HTTP_CLIENT_IP'])
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if($_SERVER['HTTP_X_FORWARDED_FOR'])
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if($_SERVER['HTTP_X_FORWARDED'])
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if($_SERVER['HTTP_FORWARDED_FOR'])
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if($_SERVER['HTTP_FORWARDED'])
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if($_SERVER['REMOTE_ADDR'])
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}	
	
	// Genera una stringa Random
	static function buildrandom($len=10,$lower=true){
		if ($lower) {
			$stringa="qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890";
		} else {
			$stringa="QWERTYUIOPASDFGHJKLZXCVBNM1234567890";
		}
		$ls=strlen($stringa);
		$code='';
		for($x=1;$x<=$len;$x++){
			$rnd=mt_rand(0,$ls-1);
			$code.=substr($stringa,$rnd,1);
		}
		return $code;
	}
	
	// Imposta la data odierna
	static function setOggi() {
		Yii::App()->user->setState('idOggi', date("d-m-Y"));
	}
	
	// Torna Stringa Vuota in Caso di 0
	static function nonZero($val) {
		if ($val == 0) {
			return "";
		} else {
			return $val;
		}
	}
	
	// Crea se non esiste le directory del path
	static function creaPath($p) {
		$ultimo = substr($p, -1);
		
		// Eliminazione eventuale ultimo carattere
		if ($ultimo == '/') {
			$p = substr($p, 0, -1);
		}

		// Creazione ricorsiva del path
		if (!file_exists($p)) {
			mkdir($p, 0777, true);
		}
	}
	
	// Messaggio Disabilitazione Registro
	static function messaggioRegistroDisabilitato($mess) {
		
		$text  = "<tr><td style='border-bottom:1px solid #DDD;'>";
		$text .= "<div class='text text-center text-danger'><strong>".$mess."</strong></div>";
		$text .= "</td></tr>";
		
		return $text;
	}

	// Messaggio Registro Parametri Insufficienti
	static function messaggioRegistroParametri($mess) {
	
		$text  = "<tr><td class='alert alert-warning xcol-intestazione' style='border-bottom:1px solid #DDD; vertical-align:middle; text-align:center; padding:2px;' height='50px'>";
		$text .= "<h3><span class='xalert xalert-info'>$mess</span></h3>";
		$text .= "</td></tr>";
		
		return $text;
	}
	
	// Messaggio Registro Intestazione
	static function messaggioRegistroIntestazione($riga1, $riga2) {
	
		$text  = "<tr><td class='alert alert-warning xcol-intestazione' style='border-bottom:1px solid #DDD; vertical-align:middle; text-align:center; padding:2px;' height='50px'>";
		$text .= "<span style='font-size:120%; line-height:25px;'>$riga1</span>";
		$text .= "<br><span style='font-size:90%'>$riga2</span>";
		$text .= "</td></tr>";
			
		return $text;
	}
	
	// Codifica Response Select2
	static function codificaResponse($testo) {
			
		return $testo;
	}

	// DeCodifica Response Select2
	static function decodificaResponse($testo) {
			
		// Eliminazione New Lines e Line Break
		$testo = NsFn::eliminaLineBreaks($testo);
		
		return addslashes($testo);
	}

	// Elimina Line Breaks e New Lines
	static function eliminaLineBreaks($testo) {
			
		return trim(preg_replace('/\s+/', ' ', $testo));
	}
	
	// MessaggiDi Risposta Header HTTP
	static function rispostaHeader($codice) {
		switch ($codice) {
			case 100:
				$risposta = "Header OK. Continuare l'invio dati.";
				break;
			case 101:
				$risposta = "Richiesto cambio di protocollo.";
				break;
			case 200:
				$risposta = "OK";
				break;
			case 202:
				$risposta = "Richiesta accettata. In elaborazione.";
				break;
			case 301:
				$risposta = "Richieste inoltrate ad altro URI.";
				break;
			case 307:
				$risposta = "Richiesta reindirizzata temporaneamente.";
				break;
			case 308:
				$risposta = "Richiesta reindirizzata permanentemente.";
				break;
			case 400:
				$risposta = "Errori di sintassi nella richiesta.";
				break;
			case 401:
				$risposta = "Richiesta non autorizzata.";
				break;
			case 403:
				$risposta = "Richiesta respinta dal server.";
				break;
			case 404:
				$risposta = "La risorsa richiesta non è stata trovata.";
				break;
			case 405:
				$risposta = "Metodo GET/POST non consentito.";
				break;
			case 407:
				$risposta = "Richiesta autenticazione Proxy.";
				break;
			case 408:
				$risposta = "Timeout. Connessione terminata.";
				break;
			case 413:
				$risposta = "Dimensioni della richiesta eccedenti la massima lunghezza consentita.";
				break;
			case 414:
				$risposta = "URI della richiesta troppo lungo.";
				break;
			case 415:
				$risposta = "Media Type non consentito.";
				break;
			case 500:
				$risposta = "Errore interno del server.";
				break;
			case 501:
				$risposta = "Il server non è in grado di soddisfare la richiesta.";
				break;
			case 502:
				$risposta = "Bad Gateway.";
				break;
			case 503:
				$risposta = "Il server non è al momento disponibile.";
				break;
			case 504:
				$risposta = "Gateway Timeout.";
				break;
			case 505:
				$risposta = "Versione HTTP della richiesta non supportata.";
				break;
			default:
				$risposta = "";
		}
		
		return $risposta;
	}
	
	// Torna il Contenuto di un URL via Curl
	static function file_get_contents_curl($url) {
		$ch = curl_init();
	
		curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 300);		
		
		$data = curl_exec($ch);
		
		curl_close($ch);
	
		return $data;
	}
	
	// Torna l'Http responce di un URL via Curl
	static function file_get_headers_curl($url) {
		$ch = curl_init();
	
		curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 300);
		
		$data = curl_exec($ch);
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
	
		return $code;
	}
	
	static function n2w($num, $separatore = 0){
		if( !is_string($num) ){
			$num .= "";
		}
		//$num = ereg_replace('^0+', '', $num);
		
		$num = preg_replace('#^0+#', '', $num);
	
		if( strlen($num) > 15 ){
			return false;
		}
		$many = array('', 'mila', 'milioni', 'miliardi', 'mila');
		$pow_dieci = array('', 'mille', ' un milione ', ' un miliardo ', 'mille');
		$string = '';
	
		if( (strlen($num) % 3) != 0 ){
			if( strlen($num) > 3 ){
				$num_tmp = substr($num, strlen($num) % 3);
				$terzina = explode('|', wordwrap($num_tmp, 3, '|', 1));
					
				array_splice($terzina, 0, 0, substr($num, 0, strlen($num) % 3));
			}
			else{
				$terzina = array($num);
			}
		}
		else{
			$terzina = explode('|', wordwrap($num, 3, '|', 1));
		}
			
		for( $i = 0, $count = count($terzina); $i < $count; $i++ ){
			$terzina[$i] = intval($terzina[$i]);
			$index = $count - 1 - $i;
			if( $terzina[$i] AND ($terzina[$i] != 1 OR $index == 0) ){
				$string .= NsFn::centinaia($terzina[$i], $separatore);
				$string .= $many[$index];
				if( $index == 4 AND !$terzina[1] ){
					if( $separatore ){
						$string .= ' ';
					}
					$string .= $many[3];
				}
			}
			elseif($terzina[$i] == 1 AND $index != 0){
				$string .= $pow_dieci[$index];
				if( $index == 4 AND !intval($terzina[1]) ){
					if( $separatore ){
						$string .= ' ';
					}
					$string .= $many[3];
				}
	
				if( ($i != $count - 1) AND intval($terzina[$i + 1]) AND $separatore){
					$string = trim($string) . ' e';
				}
			}
	
			if( $separatore AND $terzina[$i]){
				$string .= ' ';
			}
		}
		return trim($string);
	}
	
	// Funzione Accessoria per n2w
	static function centinaia($num, $separa = 1){
		$num = (int) $num;
		$num_s = sprintf('%03d', $num);
		$string = '';
	
		$units = array('', 'uno', 'due', 'tre', 'quattro', 'cinque', 'sei', 'sette', 'otto', 'nove');
		$teens = array('dieci', 'undici', 'dodici', 'tredici', 'quattordici', 'quindici', 'sedici',
				'diciassette', 'diciotto', 'diciannove');
		$decine = array('', 'dieci', 'venti', 'trenta', 'quaranta', 'cinquanta', 'sessanta', 'settanta', 'ottanta', 'novanta');
		 
		if( strlen($num_s) > 3 OR $num_s == 0 ){
			return;
		}
		else{
			$cifre = array((int)$num_s[0], (int)$num_s[1], (int)$num_s[2]);
			 
			if( $cifre[0] ){
				if( $cifre[0] != 1){
					$string .= $units[$cifre[0]];
				}
				$string .= 'cento';
				 
				if( $separa ){
					$string .= ' ';
				}
			}
			 
			if( $cifre[1] ){
				if( $cifre[1] == 1 ){
					$string .= $teens[$cifre[2]];
				}
				else{
					if( $cifre[2] == 1 OR $cifre[2] == 8 ){
						$string .= substr($decine[$cifre[1]], 0, -1);
					}
					else{
						$string .= $decine[$cifre[1]];
					}
				}
			}
			 
			if( $cifre[2] AND $cifre[1] != 1 ){
				$string .= $units[$cifre[2]];
			}
			 
			return $string;
		}
	}
	
	static function nfn($numero) {
		return number_format($numero, 2, "," ,".");
	}
	
	// Funzione per l'invio messaggio SSE
	static function sendMessageSSE($messaggio) {
		if ($messaggio == "STOP") {
			$button = "<br><br><a href='".Yii::app()->getHomeUrl()."' role='button' class='btn btn-primary'><i class='fa fa-remove fa-fw'></i>Chiudi</a>";
			echo "event: stop\n";
			echo "data: $button\n\n";
		} else {
			echo "data: $messaggio\n\n";
		}
		ob_flush();
		flush();
	}

	static function sendMessaggioSSE($messaggio) {
		header('Content-Type: text/event-stream');
		header('Cache-Control: no-cache');
		
		if ($messaggio == "STOP") {
			echo "event: stop\n";
		}
		echo "data: $messaggio\n\n";
		ob_flush();
		flush();
	}
	
	public static function sendMessage($messaggio){
		if (ob_get_level () == 0)
			ob_start ();
			echo $messaggio;
			ob_flush ();
			flush ();
	}
	
	
	static function dirToArray($dir) {
		$contents = array();
		foreach (scandir($dir) as $node) {
			if ($node == '.' || $node == '..') continue;
			if (is_dir($dir . '/' . $node)) {
				$contents[$node] = self::dirToArray($dir . '/' . $node);
			} else {
				$contents[] = $node;
			}
		}
		return $contents;
	}	
	
	static function dirSize($dir) {
		$size = 0;
		foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS)) as $file){
			$size += $file->getSize();
		}
		return $size;
	}
	
	static function fileSize($file) {
		$size = 0;
		if (file_exists($file)) {
			$size = filesize($file);
		}
		return $size;
	}

	static function humanSize($size) {
		$hSize = "";
		if ($size > 1024*1024*1024*1024) {
			$hSize = number_format($size/(1024*1024*1024*1024), 2, ",", ".");
			$hSize.= ' Tb';
		} elseif ($size > 1024*1024*1024) {
			$hSize = number_format($size/(1024*1024*1024), 2, ",", ".");
			$hSize.= ' Gb';
		} elseif ($size > 1024*1024) {
			$hSize = number_format($size/(1024*1024), 2, ",", ".");
			$hSize.= ' Mb';
		} elseif ($size > 1024) {
			$hSize = number_format($size/1024, 2, ",", ".");
			$hSize.= ' Kb';
		} else {
			$hSize = number_format($size, 0, ",", ".");
			$hSize.= ' bytes';
		}
		return $hSize;
	}
	
	/*
	 * converte una cifra in lettere
	 */
	
	static function n2wd($importo){
		//$importo=round($importo,2);
		$int=intval($importo);
		$dec=round(($importo-$int)*100);
		
		return self::n2w($int)."/".$dec;
	}
	
	//
	// Conversione Data Inserita nei filtri grid
	//
	static function gridDataFilter($data) {
		$newData = "";
		
		if (strlen($data) == 10 ) {
			$newData = NsDate::dmy2ymd($data);
		} elseif (strlen($data) > 10) {
			$p1 = substr($data, 0, -10);
			$p2 = substr($data, -10);
			
			$newData = $p1.NsDate::dmy2ymd($p2);
		}
		
		return $newData;
	}
	
	//
	// Elimina il contenuto di una directory (Il secondo parametro TRUE elimina la directory stessa
	//
	static function eliminadir($path, $eliminaRadice=false) {
		// Controllo esistenza directory
		if (is_dir($path)) {
			$contenuto = scandir($path);
			foreach ($contenuto as $x) {
				if ($x != "." && $x != "..") {
					if (filetype($path."/".$x) == "dir") {
						NsFn::eliminadir($path."/".$x, true);
					} else {
						unlink ($path."/".$x);
					}
				}
			}
			reset($contenuto);
			if ($eliminaRadice) {
				rmdir($path);
			}
		}
	}
	
	//
	// Estrazione file .xml da contenitore .p7m
	//
	static function p7m2xml($pfile, $xfile, $sanitize=false) {
		// Contenuto file p7m
		$s = file_get_contents($pfile);
		
		// Eliminazione contenuto precedente dichiarazione xml
		$s = substr($s, strpos($s, '<?xml '));
		
		// Eliminazione contenuto successivo all'XML
		preg_match_all('/<\/.+?>/', $s, $matches, PREG_OFFSET_CAPTURE);
		$lastMatch = end($matches[0]);
		
		$s = substr($s, 0, $lastMatch[1]);

		// Eliminazione eventuali caratteri non UTF8
		if ($sanitize) {
			$regex = '/(
	            [\xC0-\xC1] 
	            | [\xF5-\xFF] 
	            | \xE0[\x80-\x9F] 
	            | \xF0[\x80-\x8F] 
	            | [\xC2-\xDF](?![\x80-\xBF]) 
	            | [\xE0-\xEF](?![\x80-\xBF]{2}) 
	            | [\xF0-\xF4](?![\x80-\xBF]{3}) 
	            | (?<=[\x0-\x7F\xF5-\xFF])[\x80-\xBF] 
	            | (?<![\xC2-\xDF]|[\xE0-\xEF]|[\xE0-\xEF][\x80-\xBF]|[\xF0-\xF4]|[\xF0-\xF4][\x80-\xBF]|[\xF0-\xF4][\x80-\xBF]{2})[\x80-\xBF] 
	            | (?<=[\xE0-\xEF])[\x80-\xBF](?![\x80-\xBF]) 
	            | (?<=[\xF0-\xF4])[\x80-\xBF](?![\x80-\xBF]{2}) 
	            | (?<=[\xF0-\xF4][\x80-\xBF])[\x80-\xBF](?![\x80-\xBF]) 
	        )/x';

			$s = preg_replace($regex, '', $s);
			
			$result = "";
			$current;
			$length = strlen($s);
			for ($i=0; $i < $length; $i++) {
				$current = ord($s{$i});
				if (($current == 0x9) || ($current == 0xA) || ($current == 0xD) || (($current >= 0x20) && ($current <= 0xD7FF)) || (($current >= 0xE000) && ($current <= 0xFFFD)) || (($current >= 0x10000) && ($current <= 0x10FFFF))) {
					$result .= chr($current);
				} else {
					$ret;
				}
			}
			$s = $result;
		}
		
		// Scrittura file
		file_put_contents($xfile, $s);
	}

	//
	// Estrazione file .pdf da contenitore .p7m
	//
	static function p7m2pdf($pfile, $xfile) {
		// Contenuto file p7m
		$s = file_get_contents($pfile);
	
		// Eliminazione contenuto precedente dichiarazione pdf
		$s = substr($s, strpos($s, '%PDF'));
		
		// Eliminazione contenuto successivo chiusura pdf
		$s = substr($s, 0, strpos($s, '%%EOF') + 5);
		
		// Scrittura file
		file_put_contents($xfile, $s);
	}
	
	
}

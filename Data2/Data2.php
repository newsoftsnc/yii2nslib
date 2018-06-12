<?php
class Data2{
	public static $app='';
	public static $xml='';

	static function getXml($app=''){
		if(!$app){
			$app=Yii::app()->params[appname];
		}
		
		if(!self::$app){
			self::$app=$app;
		}
		if(self::$app!==$app){
			self::$app=$app;
			self::$xml='';
		}
		if(!self::$xml){
			$fileApplic2 = $_SERVER['DOCUMENT_ROOT']."/applic2/".self::$app."/data2.xml";

			$dirYii = Yii::getPathOfAlias('media').'/data2/';
			$fileYii = Yii::getPathOfAlias('media').'/data2/data2.xml';
				
			// Aggiorna versione interna di data2.xml
			if (file_exists($fileApplic2)) {
				// Verifico necessità di aggiornare la versione locale
				if (!file_exists($fileYii) or filemtime($fileYii) < filemtime($fileApplic2)) {
					// Creazione Path Yii
					NsFn::creaPath($dirYii);
				
					// Aggiornamento File
					copy($fileApplic2, $fileYii);
				}
			}

			// Apertura copia interna di data2.xml
			$xml=simplexml_load_file($fileYii);
			self::$xml=$xml;
		}

		return self::$xml;
	}
	
	static function getAliasByName($nomealias){
		$xml=self::getXml();

		$alias=$xml->xpath("/root/DB/ENTITA[@NOME='$nomealias']")[0];
		
		if(!$alias) return false;
		return $alias;
	}
	
	static function getTableByName($nometable){
		/* @var $table SimpleXMLElement */
		
		$xml=self::getXml();
	
		$table=$xml->xpath("/root/DB/TABLE[@NOME='$nometable']")[0];
	
		if(!$table) return false;
		return $table;
	}
	
	/**
	 * RITORNA L'ARRAY DEGLI ALIAS PARENT IDENTIFICATIVI
	 * 
	 * @param string $nomeentita
	 * @param array $aparenti
	 * @return array $aparenti
	 * 
	 * 
	 */
	static function getParenti($nomeentita,$aparenti=[]){
		self::getXml();
		$query="/root/DB/ENTITA[@NOME='$nomeentita']/LINKP[@IDENTIFICATIVA='1']";

		foreach(self::$xml->xpath($query) as $parenti){
			$nomeparent=(string) $parenti[ALIAS]; 
			$aparenti[$nomeparent]=$nomeparent;
			foreach(self::getParenti($nomeparent,$aparenti) as $nome=>$value){
				$aparenti[$nome]=$value;
			}
		}
		return $aparenti;
	}

	/**
	 * RITORNA L'ARRAY DEGLI ALIAS PARENT
	 * @param string $nomeentita
	 * @param array $aparenti
	 * @return array $aparenti
	 *
	 * 
	 */
	static function getParent($nomeentita,$aparent=[]){
		self::getXml();
		$query="/root/DB/ENTITA[@NOME='$nomeentita']/LINKP";
	
		foreach(self::$xml->xpath($query) as $parenti){
			$nomeparent=(string) $parenti[ALIAS];
			$aparent[$nomeparent]=$nomeparent;
			foreach(self::getParent($nomeparent,$aparent) as $nome=>$value){
				$aparent[$nome]=$value;
			}
		}
		return $aparent;
	}
	
	/**
	 * RITORNA L'ARRAY DEGLI ALIAS PARENT COMUNI
	 * @param string $nomeentita
	 * @param array $aparenti
	 * @return array $aparenti
	 *
	 *
	 */
	
	static function getIntersect($nomeentitap,$nomeentitac){
		self::getXml();

		$query="/root/DB/ENTITA[@NOME='$nomeentitac']/LINKP";
	
		$aparentc=[];
		foreach(self::$xml->xpath($query) as $parenti){
			$nomeparent=(string) $parenti[ALIAS];
			//$aparentc[$nomeparent]=$nomeparent;
			if($nomeparent==$nomeentitap) continue;
			$aparentc[$nomeparent]=$nomeparent;
			foreach(self::getParent($nomeparent,$aparentc) as $nome=>$value){
				$aparentc[$nome]=$value;
			}
		}
		
		$aparentp=self::getParent($nomeentitap);
		$aparentintersect=array_intersect($aparentp, $aparentc);
		
		return $aparentintersect;
	}
	
	/**
	 * ritorna l'array lockParent
	 * nel formato [nomechild][aliasParent,...,....]
	 */
	static function getLockParents($nomeentita){
		self::getXml();
		$parenti=self::getParenti($nomeentita);
		$lockParents=[];
		foreach(self::$xml->xpath("//LINKP[@ALIAS='$nomeentita']") as $xlink){
			$xchild=$xlink->xpath("..")[0];
			$aliasc=(string) $xchild[NOME];
			
			$intersect=self::getIntersect($nomeentita, $aliasc);
			$intersec=[];
			foreach($intersect as $ele){
				if(!in_array($ele,$parenti)){
					$intersec[]=$ele;
				}
			}
			$lockParents[$aliasc]=$intersec;
		}
		
		return $lockParents;
		
		$return=[];
		foreach($lockParents as $aliasc=>$lockParent){
			$aliasc=ucfirst(strtolower($aliasc));
			$alock=[];
			foreach($lockParent as $string) $alock[]=ucfirst(strtolower($string)); 
			$return[$aliasc]=$alock;
		}
		
		return $return;
		
	}
	
	static function getChildd($nomeentita){
		self::getXml();
		$achild=[];
		foreach(self::$xml->xpath("//LINKP[@ALIAS='$nomeentita']") as $xlink){
			$xchild=$xlink->xpath("..")[0];
			$aliasc=(string) $xchild[NOME];
			$achild[]=$aliasc;
		}
		return $achild;
	}
	
	
	static function getTableBuild($nometable){
		$xtable=self::getTableByName($nometable);
		$build="$xtable[BUILD]";
		
		$afield=[];
		foreach($xtable->FIELD AS $xfield){
			$obj=new stdClass();
			$obj->nome="$xfield[NOME]";
			$obj->tipo="$xfield[TIPO]";
			$obj->lung="$xfield[LEN]";
			$obj->dec="$xfield[DEC]";
			$obj->pk="$xfield[PK]";
			$obj->ai="$xfield[AI]";
			$obj->pfk="$xfield[PFK]";
			$obj->fk="$xfield[FK]";
			if($obj->pfk or $obj->fk) $afield[]=$obj;
		}
		foreach($xtable->FIELD AS $xfield){
			$obj=new stdClass();
			$obj->nome="$xfield[NOME]";
			$obj->tipo="$xfield[TIPO]";
			$obj->lung="$xfield[LEN]";
			$obj->dec="$xfield[DEC]";
			$obj->pk="$xfield[PK]";
			$obj->ai="$xfield[AI]";
			$obj->pfk="$xfield[PFK]";
			$obj->fk="$xfield[FK]";
			if($obj->pfk or $obj->fk)continue;
			$afield[]=$obj;
		}
		
		
		$afieldText=[];
		foreach ($afield as $obj){
			$text=" $obj->nome ";
			switch ($obj->tipo){
				case 'C':
					$text.="CHAR($obj->lung) NOT NULL";
					break;
				case 'N':
					if($obj->ai){
						$text.="int(11) AUTO_INCREMENT NOT NULL";
					}else{
						$zerofill=$obj->pk?'ZEROFILL':'';
						$text.="NUMERIC($obj->lung,$obj->dec) $zerofill NOT NULL";
					}
					break;
				case 'D':
					$text.="DATE NOT NULL";
					break;
				case 'L':
					$text.="TINYINT(1) NOT NULL";
					break;
				case 'M':
					$text.="MEDIUMTEXT NOT NULL";
					break;
			}
			$afieldText[]=$text;
		}
		
		$afieldKey=[];
		foreach ($afield as $obj){
			if($obj->pfk or $obj->pk){
				$afieldKey[]=$obj->nome;
			}
		}
		
		$nomet="$xtable[NOME]";
		if($nomet=='cronom' or $nomet=='crono'){
			$engine='MYISAM';
		}else{
			$engine='INNODB';
		}
		
		$buildText="CREATE TABLE IF NOT EXISTS $xtable[NOME] ("
		.implode(',', $afieldText).", PRIMARY KEY(".implode(',', $afieldKey).")) ENGINE=$engine";
		
		return $buildText;
	
	}
	
	static function getAfieldKey($modelname){
		$modelname=strtoupper($modelname);
		$alias=self::getAliasByName($modelname);
		$nometable="$alias[TABLE]";
		$table=self::getTableByName($nometable);
		$path="./FIELD[@PK='1']";
		$axfieldkey=$table->xpath($path);
		
		$afield=[];
		foreach($axfieldkey as $fieldkey){
			$afield[]="$fieldkey[NOME]";
		}
		
		return $afield;
	}
	
	
}
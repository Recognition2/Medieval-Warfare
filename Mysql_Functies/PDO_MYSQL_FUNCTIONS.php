<?php

/*Connect to database*/
try{
$conn = new PDO('mysql:host='.$db['host'].';dbname='.$db['dbname'], $db['user'], $db['pass']);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES, false); /*Use of full prepare statement*/
$conn->query("SET SESSION sql_mode = 'ANSI,ONLY_FULL_GROUP_BY'");
}
/*Display possible error*/
catch(PDOException $e){
$sMsg = '<p>
    Regelnummer: '.$e->getLine().'<br />
    Bestand: '.$e->getFile().'<br />
    Foutmelding: '.$e->getMessage().'
    </p>';
trigger_error($sMsg);
}
    
/*Simpel:
* Ren, vlucht als het nog kan
* Of
* Vereiste de connectie,tablename,columname waar je iets naar wil schrijven, de te opslaan waarden*/
function write_row($conn,$tableName,$columnName,$value)
{
	try{
		$sql=$conn -> prepare("INSERT INTO $tableName ($columnName) VALUES ($value)");/*De mysql statement voorbereiden*/
		$sql->execute();/*Mysql statement uitvoeren*/
	}
	catch(PDOException $e) {
		$sMsg = '<p>
		Regelnummer: '.$e->getLine().'<br />
		Bestand: '.$e->getFile().'<br />
		Foutmelding: '.$e->getMessage().'
		</p>';
		trigger_error($sMsg);
	}
}

/*Simpel:
* Ren, vlucht als het nog kan
* Of
* Vereiste de connectie,tablename,columname waar je iets tegen wil testen, de te testen waarde, colum waar de data in staat*/
function getSingleValue($conn,$tableName, $prop, $value, $columnName)
{
	try{
		$sql=$conn -> prepare("SELECT $columnName
		FROM $tableName WHERE $prop='$value' LIMIT 1");/*De mysql statement voorbereiden*/
		$sql->execute();/*Mysql statement uitvoeren*/ 
		$result = $sql->fetch(PDO::FETCH_ASSOC);/*Resultaat in een array stoppen het is een enkele waarde dus geen for loop*/
		/*Print result*/
		/*print_r($result);*/
		return $result[$columnName] ;
	}
	catch(PDOException $e) {
		$sMsg = '<p>
		Regelnummer: '.$e->getLine().'<br />
		Bestand: '.$e->getFile().'<br />
		Foutmelding: '.$e->getMessage().'
		</p>';
		trigger_error($sMsg);
	}
}

/*Kijken of een waarde al bestaat in de database
 * connectie waarde nodig
 * Tablenaam
 * Waarde
 * In welke colum
 * 1 als de waarde bestaat 
 * 0 als deze nog niet bestaat*/
function testifvalueexist($conn,$tableName, $value, $columnName)
{
	try{
		$sql=$conn -> prepare("SELECT 1 $columnName
		FROM $tableName WHERE $columnName='$value' LIMIT 1"); /*Limit 1 voor snelheid*/
		$sql->execute();/*Mysql statement uitvoeren*/ 
		/*print_r($sql);*/
		$result = $sql->fetch(PDO::FETCH_ASSOC);/*Resultaat in een array stoppen het is een enkele waarde dus geen for loop*/
		/*print_r($result);*/
		if($result[$columnName]==1){
			return 1;
		}else{
			return 0;
		}
	}
	catch(PDOException $e) {
		$sMsg = '<p>
		Regelnummer: '.$e->getLine().'<br />
		Bestand: '.$e->getFile().'<br />
		Foutmelding: '.$e->getMessage().'
		</p>';
		trigger_error($sMsg);
	}
}
/*Bijwerken van een waarde in de database
 * table naame
 * Aantepassen colum
 * waarde
 * de eise en waar deze aan moet voldoen*/
function update_singel_value($conn,$tableName,$columnName,$value,$prop,$ID)
{
	try{
		$sql=$conn -> prepare("UPDATE $tableName set $columnName='$value' where $prop='$ID' limit 1");/*De mysql statement voorbereiden*/
		/*print_r($sql);*/		
		$sql->execute();/*Mysql statement uitvoeren*/
	}
	catch(PDOException $e) {
		$sMsg = '<p>
			Regelnummer: '.$e->getLine().'<br />
			Bestand: '.$e->getFile().'<br />
			Foutmelding: '.$e->getMessage().'
			</p>';
		trigger_error($sMsg);
	}
}

?>

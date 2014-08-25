<?php
session_start();
include("../../config/config.php");

$user_id = $_SESSION["av_iduser"];

//ACTIONLOG
	$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(THCAP) คำนวณยอดผ่อน',LOCALTIMESTAMP(0))");
//ACTIONLOG---

$genDate = $_POST["genDatedata"];
$genMinPay = $_POST["genMinPaydata"];
$interest = $_POST["interest"];
$investment = str_replace(",","",$_POST["investment"]);
$datestart = $_POST["datestart"];
$vat = $_POST["vat"];

if($vat == 'sumvat'){
	$qry_novat = pg_query("select \"cal_rate_or_money\"('VAT','now','$investment','2')");
	list($investment_ar) = pg_fetch_array($qry_novat);
	$text = "{0,".$investment_ar*(-1)."}";
	
	for($i=1;$i<sizeof($genDate);$i++){
		$qry_novat = pg_query("select \"cal_rate_or_money\"('VAT','now','$genMinPay[$i]','2')");
		list($genMinPaydata) = pg_fetch_array($qry_novat);

		if($i == 1){
			$day = floor((strtotime($genDate[$i]) - strtotime($datestart))/86400);
			$text = $text.",{".$day.",".$genMinPaydata."}";
			$dayold = $genDate[$i];
		}else{
			$day = floor((strtotime($genDate[$i]) - strtotime($dayold))/86400);
			$text = $text.",{".$day.",".$genMinPaydata."}";
			$dayold = $genDate[$i];
		}

	}
}else{
	$text = "{0,".$investment*(-1)."}";
	for($i=1;$i<sizeof($genDate);$i++){
		
		$qry_novat = pg_query("select \"cal_rate_or_money\"('VAT','now','$genMinPay[$i]','2')");
		list($genMinPaydata) = pg_fetch_array($qry_novat);
	
		if($i == 1){
			$day = floor((strtotime($genDate[$i]) - strtotime($datestart))/86400);
			$text = $text.",{".$day.",".$genMinPaydata."}";
			$dayold = $genDate[$i];
		}else{
			$day = floor((strtotime($genDate[$i]) - strtotime($dayold))/86400);
			$text = $text.",{".$day.",".$genMinPaydata."}";
			$dayold = $genDate[$i];
		}
	}
}	

$cashflow = "{".$text."}";
$qry_irr = pg_query("select \"cal_irr_rate_for_filease_acc\"('$cashflow','$interest','YEAR','0')");
list($irr_acc) = pg_fetch_array($qry_irr);
echo "	<fieldset style=\"background-color:#FFFFFF;text-align:center;\">";
echo "	<legend align=\"center\">อัตราดอกเบี้ย Effective rate</legend>";	
echo "	<span><p>$irr_acc<p></span>";
echo "	</fieldset>";

?>
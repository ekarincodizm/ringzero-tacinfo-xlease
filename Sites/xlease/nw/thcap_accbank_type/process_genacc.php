<?php include("../../config/config.php");
pg_query("BEGIN");
$status=0;
$datenow = nowDate();
$user_id = $_SESSION["av_iduser"];

$select_bankint=pg_escape_string($_POST["bankint"]);//เลือกบัญชี
$select_date=pg_escape_string($_POST["date1"]);//เลือกวัน 1 = ตามเดือน ปี ,2 =ตามปี
$monthMY=pg_escape_string($_POST["month1"]);//ตามเดือน ปี
$yearMY=pg_escape_string($_POST["year1"]);//ตามเดือน ปี
$yearY=pg_escape_string($_POST["year2"]);//ตามปี
//ตามเดือน ปี
if($select_date=='1'){
	if(($select_bankint !='') and($monthMY !='') and ($yearMY!='')){
		$sql=pg_query("select account.\"thcap_get_ledger_accBook\"('$monthMY', '$yearMY', '$user_id','$select_bankint',null)");
		$query =pg_fetch_array($sql); 
		list($result)=$query;
		if ($result){}
		else{$status++;}
		//echo $monthMY.'/'.$yearMY.'/'.$user_id.'/'.$select_bankint;
	}
	else{
	}
}
//ตามปี
else if($select_date=='2'){
	list($year,$month,$day)=explode("-",$datenow);
	$countm=0;
	if($yearY==$year){
		$month=$month;
	}
	else if($yearY<$year) { 
		$month=12;
	}
	else{
		$month=0;
	}
	while($countm < $month ){
		$countm++;
		$sql=pg_query("select account.\"thcap_get_ledger_accBook\"('$countm', '$yearY', '$user_id','$select_bankint',null)");
		$query =pg_fetch_array($sql); 
		list($result)=$query;
		if ($result){}
		else{$status++;exit;}
	}	
}
else{
	$status++;
}
if($status==0){
	pg_query("COMMIT");	
  	echo 1;
}
else{
	pg_query("ROLLBACK");
	echo 2;
}
?>
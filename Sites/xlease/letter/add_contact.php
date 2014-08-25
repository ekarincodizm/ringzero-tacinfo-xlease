<?php
session_start();
include("../config/config.php");

$userid = $_SESSION["av_iduser"];
$txt_ads = pg_escape_string($_REQUEST["txt_ads"]);
$idno = pg_escape_string($_REQUEST["idno"]);
$nowdate = Date('Y-m-d');


$query_user = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$userid'");
$result_user = pg_fetch_array($query_user);
$fullname = $result_user["fullname"];

$qury_cont = pg_query("select \"ContactNote\" from \"Fp_Note\" where \"IDNO\" = '$idno'");
$num_cont = pg_num_rows($qury_cont);
	
if($num_cont == 0){
	$contactnote="";
}else{
	$result_cont = pg_fetch_array($qury_cont);
	$contactnote = $result_cont["ContactNote"];
}
$contact = "******------------------ที่ส่งจดหมาย------------------******\r\n".$txt_ads."\r\n------------------โดย $fullname วันที่  $nowdate------------------\r\n\r\n".$contactnote;

pg_query("BEGIN WORK");
$status=0;
if($num_cont == 0){
	if($txt_ads != ""){
		$ins = "insert into \"Fp_Note\" (\"IDNO\",\"ContactNote\") values ('$idno','$contact')";
		if(!pg_query($ins) ){
			$status++;
		}
	}
}else{
	if($txt_ads != ""){
		$update = "update \"Fp_Note\" set \"ContactNote\" = '$contact' where \"IDNO\" = '$idno'";
		if(!pg_query($update) ){
			$status++;
		}
	}
}

if($status == 0){
    pg_query("COMMIT");
	
    $query_cont = pg_query("select \"ContactNote\" from \"Fp_Note\" where \"IDNO\" = '$idno'");
	$result_cont = pg_fetch_array($query_cont);
	$contactnote = $result_cont["ContactNote"];
	echo $contactnote;
	
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกได้ในขณะนี้";   
}



?>
<?php
include("../../../config/config.php");
include("../../function/checknull.php");
set_time_limit(0);
$show=$_GET["show"];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<title>Script Update ผู้กู้ร่วม</title>
<script language=javascript>
$(document).ready(function(){
	$('#btn1').click(function(){
		$("#btn1").attr('disabled',true);
		$("#panel").text('กำลังดำเนินการ...ระบบอาจจะใช้เวลาประมวลผลนาน 5-10 นาที');
		$("#panel").load("updateconame.php?show=1");
		$("#btn1").attr('disabled',false);
		
    });	
});
</script>
</head>
<body>
<?php
if($show!=1){
?>
<div align="center"><input type="button" id="btn1" value="เริ่ม UPDATE ชื่อผู้กู้ร่วมในตาราง thcap_temp_receipt_details และ thcap_temp_taxinvoice_details ให้ถูกต้อง" style="height:60px;"></div>
<?php } ?>
<div id="panel">
<?php
if($show==1){
pg_query("BEGIN WORK");
$status = 0;

// หาเลขที่ใบเสร็จทั้งหมด
if($qry_noSame = pg_query("SELECT \"receiptID\", \"contractID\" FROM thcap_v_receipt_details
union SELECT \"receiptID\", \"contractID\" FROM thcap_v_receipt_details_cancel group by \"receiptID\", \"contractID\""))
{
	while($res_noSame = pg_fetch_array($qry_noSame))
	{
		$receiptID = $res_noSame["receiptID"]; // เลขที่ใบเสร็จ
		$contractID = $res_noSame["contractID"]; // เลขที่สัญญา
		
		//หาผู้กู้ร่วมของเลขที่สัญญานั้นๆ
		if($qry_name=pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\" where \"contractID\" = '$contractID' and \"CusState\" ='1'")); else $status++;
		$numco=pg_num_rows($qry_name);
		$i=1;
		$nameco="";
		
		//นำชื่อผู้กู้ร่วมมาเชื่อมเพื่อเก็บในตัวแปรเดียว
		while($resco=pg_fetch_array($qry_name)){
			$name2=trim($resco["thcap_fullname"]);
			if($numco==1){ //กรณีมีชื่อเดียวไม่ต้องใส่ comma
				$nameco=$name2;
			}else{ 
				if($i==$numco){
					$nameco=$nameco.$name2;
				}else{
					$nameco=$nameco.$name2.", ";
				}
			}
			$i++;
		}

		//เช็คค่าว่างของตัวแปร เพื่อใช้ในการ insert ลงฐานข้อมูล
		$nameco = checknull($nameco); // ชื่อผู้กู้ร่วม
		
		//update ตาราง thcap_temp_receipt_details ให้ update ข้อมูลให้ถูกต้อง
		$update="UPDATE thcap_temp_receipt_details SET \"cusCoFullname\"=$nameco WHERE \"receiptID\"='$receiptID'";
		if($resup=pg_query($update)){
		}else{
			$status++;
		}
		
	} //end while
}
else
{ 
	$status++;
}


// แก้ไขข้อมูลในส่วนใบกำกับ โดยหาเลขที่ใบกำกับทั้งหมด
if($qry_tax = pg_query("SELECT \"taxinvoiceID\", \"contractID\" FROM thcap_v_taxinvoice_details
union SELECT \"taxinvoiceID\", \"contractID\" FROM thcap_v_taxinvoice_details_cancel group by \"taxinvoiceID\", \"contractID\""))
{
	while($res_tax = pg_fetch_array($qry_tax))
	{
		$taxinvoiceID = $res_tax["taxinvoiceID"]; // เลขที่ใบกำกับ
		$contractID = $res_tax["contractID"]; // เลขที่สัญญา
		
		//หาผู้กู้ร่วมของเลขที่สัญญานั้นๆ
		if($qry_name=pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\" where \"contractID\" = '$contractID' and \"CusState\" ='1'")); else $status++;
		$numco=pg_num_rows($qry_name);
		$i=1;
		$nameco="";
		
		//นำชื่อผู้กู้ร่วมมาเชื่อมเพื่อเก็บในตัวแปรเดียว
		while($resco=pg_fetch_array($qry_name)){
			$name2=trim($resco["thcap_fullname"]);
			if($numco==1){ //กรณีมีชื่อเดียวไม่ต้องใส่ comma
				$nameco=$name2;
			}else{ 
				if($i==$numco){
					$nameco=$nameco.$name2;
				}else{
					$nameco=$nameco.$name2.", ";
				}
			}
			$i++;
		}

		//เช็คค่าว่างของตัวแปร เพื่อใช้ในการ insert ลงฐานข้อมูล
		$nameco = checknull($nameco); // ชื่อผู้กู้ร่วม
		
		//update ตาราง thcap_temp_receipt_details ให้ update ข้อมูลให้ถูกต้อง
		$update="UPDATE thcap_temp_taxinvoice_details SET \"cusCoFullname\"=$nameco WHERE \"taxinvoiceID\"='$taxinvoiceID'";
		if($resup=pg_query($update)){
		}else{
			$status++;
		}
		
	} //end while
}
else
{ 
	$status++;
}

if($status == 0)
{
	//pg_query("ROLLBACK");
	pg_query("COMMIT");
	echo "<center><h2>SUCCESS</h2></center>";
}
else
{
	pg_query("ROLLBACK");
	echo "<center><h2>ERROR</h2></center>";
}
}
?>
</div>
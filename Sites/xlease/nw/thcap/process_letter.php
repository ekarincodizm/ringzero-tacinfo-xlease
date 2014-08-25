<?php 
session_start();
include("../../config/config.php");
include("../function/checknull.php");
?>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<?php
$cmd = $_REQUEST['cmd'];
$addUser=$_SESSION["av_iduser"];
$addStamp=nowDateTime();

pg_query("BEGIN WORK");
$status = 0;

if($cmd == "addhead"){  //บันทึกชื่อเรื่องที่จะส่งจดหมาย
	$headsend = $_POST["headsend"];   	
	//บันทึกข้อมูล
	$ins="INSERT INTO thcap_letter_head(\"sendName\",\"addUser\",\"addStamp\") VALUES ('$headsend','$addUser','$addStamp')";
	if($resin=pg_query($ins)){
	}else{
		$status++;
	}

	if($status == 0){
		pg_query("COMMIT");
		echo "1";
	}else{
		pg_query("ROLLBACK");
		echo "2";
	}	
}else if($cmd=="showspec"){ //แสดงชื่อเรื่องที่จะส่งจดหมาย
	echo "<table width=\"100%\" border=\"0\"  align=\"center\">";
	echo "<select name=\"typeletter[]\" id=\"typeletter\">";
	$qryspecial2=pg_query("SELECT auto_id, \"sendName\" FROM thcap_letter_head");
	$numspec=pg_num_rows($qryspecial2);
	while($resspec2=pg_fetch_array($qryspecial2)){
		list($sendId1,$sendName1)=$resspec2;
		echo "<option value=$sendId1>$sendName1</option>";					
	}
	echo "</select><button type=\"button\" onclick=\"addFile()\">เพิ่มรายการ</button>";
	echo "<div id=\"files-root\" style=\"margin:0\"></div>";
	echo "</table>";
}else if($cmd=="add"){
	$contractID = $_POST['contractID']; //เลขที่สัญญา
	$addrcon = $_POST['addrcon'];
	$type_send = $_POST['type_send']; //ประเภทการส่งจดหมาย
	$regis_back2 = $_POST['regis_back']; //เลขลงทะเบียนตอบรับ(ถ้ามี)
	
	$typea=0;
	if($addrcon=="1"){//เลือกว่าส่งไปที่อยู่เลขที่สัญญาด้วย
		$addrresscon = $_POST['addrresscon'];
		$addrresscon = "'".$addrresscon."'";
		
		//หาชื่อลูกค้า โดยจะระบุชื่อลูกค้าทุกคนในซองด้วย
		$qry_name=pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\" WHERE \"contractID\" ='$contractID' order by \"CusState\" ");
		$pp=0;
		$rownum_name = pg_num_rows($qry_name);
		while($resqname=pg_fetch_array($qry_name)){
			list($cusName2)=$resqname;
			$pp += 1;
			if($pp == 1){
				$cusName .= "$cusName2";
			}else{
				if($pp == $rownum_name){
					$cusName .= "และ$cusName2";
				}else{
					$cusName .= ", $cusName2";
				}	
			}
		}
		$cusName="'".$cusName."'";
		
		if($type_send=="A"){
			$type_send="'".$type_send."'";
			$regis_back = "'".$regis_back2."'";
			$typea++;
		}else{
			$type_send="'".$type_send."'";
			$regis_back="null";
		}
	}else{
		$addrresscon="null";
		$cusName="null";
		$type_send="null";
		$regis_back="null";
	}
	 
	$chk = $_POST['chk']; //รหัสลูกค้าที่ส่งจดหมายไปด้วย
	$addrcus = $_POST['addrcus']; //ที่อยู่ของลูกค้าที่ส่งจดหมาย
	$typeletter = $_POST['typeletter']; //รายการที่ส่ง
	$letterref = $_POST['detailref']; //เลขอ้างอิงรายการที่ส่ง
	$typesend = $_POST['typesend']; //ประเภทการส่งจดหมาย
	$regisback = $_POST['regisback']; //เลขที่ลงทะเบียน
	$note = $_POST['note']; //หมายเหตุ
	$note=checknull($note);
	
	
	//บันทึกข้อมูลหลัก
	$insmain="INSERT INTO thcap_letter_send(
            \"contractID\", \"addressCon\", \"cusName\", id_user, \"sendDate\", note,\"type_send\",\"regisnumber\")
    VALUES ('$contractID', $addrresscon, $cusName, '$addUser', '$addStamp', $note,$type_send,$regis_back) returning auto_id";
	
	if($resin=pg_query($insmain)){
		list($sendID)=pg_fetch_array($resin);
	}else{
		$status++;
	}
	
	//บันทึกเรื่องที่ส่งและเลขอ้างอิง
	for($i=0;$i<sizeof($typeletter);$i++){
		$detail=$typeletter[$i];
		$detailref=$letterref[$i];
		$detailref=checknull($detailref);
		
		if($letterref[$i]!=""){ //ถ้ามีการระบุเลขที่อ้างอิง
			//ตรวจสอบว่ามีเลขอ้างอิงที่เป็นใบแจ้งหนี้รอส่งหรือไม่ ถ้าใช่ให้ update เป็นส่งแล้ว
			$qrychkdebt=pg_query("SELECT auto_id FROM thcap_sendinvlist WHERE print_user IS NOT NULL AND print_date IS NOT NULL
			AND \"letterID\" is null AND \"invoiceID\"=$detailref");
			$numcheckdebt=pg_num_rows($qrychkdebt);
			if($numcheckdebt>0){ //แสดงว่ามีการส่งใบแจ้งหนี้ให้ update ว่าส่งใบแจ้งหนี้แล้ว
				list($pkinvoice)=pg_fetch_array($qrychkdebt);
				
				$updebt="UPDATE thcap_sendinvlist SET  send_user='$addUser', send_date='$addStamp', \"letterID\"='$sendID'
				WHERE auto_id='$pkinvoice'";
				if($resupdebt=pg_query($updebt)){
				}else{
					$status++;
				}
			}
		}
		//เก็บข้อมูลว่าส่งรายการอะไรบ้างในตาราง  thcap_letter_detailRef
		$insdetail="INSERT INTO \"thcap_letter_detailRef\"(\"sendID\", detail, \"detailRef\")
		VALUES ('$sendID', '$detail', $detailref)";
		if($resinsdetail=pg_query($insdetail)){
		}else{
			$status++;
		}
	}
		
	//หาประเภทที่เลือก
	for($pp=0;$pp<sizeof($typesend);$pp++){
		if($typesend[$pp]==""){
		}else{
			$type[]=$typesend[$pp];
			$regis[]=$regisback[$pp];
		}
	}
	
	//บันทึกข้อมูลในตารางรายละเอียด
	if(sizeof($chk)>0){
		for($i=0;$i<sizeof($chk);$i++){
			//echo "$typesend[$i],$regisback<br>";
			
			//ประเภทการส่งจดหมาย
			if($type[$i]!=""){
				if($type[$i]=="A"){
					$regisback="'".$regis[$i]."'";
					$typea++;
				}else{
					$regisback="null";
				}
			}
			//ค้นหาชื่อลูกค้าขณะนั้นเพื่อเก็บเป็นประวัติขณะนั้น
			$qry_name2=pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\" WHERE \"contractID\" ='$contractID' and \"CusID\"='$chk[$i]'");
			list($receiveName)=pg_fetch_array($qry_name2);
			
			$insdetail="INSERT INTO thcap_letter_detail(
					\"sendID\", \"CusID\", \"receiveName\", \"addrCus\",\"type_send\",\"regisnumber\")
			VALUES ('$sendID', '$chk[$i]','$receiveName', '$addrcus[$i]','$type[$i]',$regisback)";
			
			if($resindetail=pg_query($insdetail)){
			}else{
				$status++;
			}
		}
	}
	if($status == 0){
		pg_query("COMMIT");
		$post = "คลองจั่น";
		echo "<center>บันทึกข้อมูลเรียบร้อยแล้ว<br /><br />
		<input type=\"button\" value=\"พิมพ์จดหมาย\" onclick=\"window.open('print_letter.php?cus_lid=$sendID')\">";
		if($typea>0){
			echo "<input type=\"button\" value=\"พิมพ์ใบเหลือง\" onclick=\"window.open('print_yellow.php?cus_lid=$sendID')\">";
		}
		echo "<input type=\"button\" value=\"   กลับ   \" onclick=\"window.location='frm_lt.php'\"></center>";
	}else{
		pg_query("ROLLBACK");
		echo "ไม่สามารถบันทึกข้อมูลได้";
	}	
	
}
?>

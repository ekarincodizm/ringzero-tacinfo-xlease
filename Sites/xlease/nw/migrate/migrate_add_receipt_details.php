<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<center><a href="frm_migrate_receipt_detail.php">กลับ</a><br></center>
<?php
include("../../config/config.php");
include("../function/checknull.php"); // ไฟล์ function chacknull ใช้เพื่อตรวจสอบค่าว่างของตัวแปรนั้นๆ วิธีใช้คือ $A = checknull($A); หาก $A เป็นค่าว่างจะส่งค่า "null" กลับมา หากไม่ใช่จะส่งค่า '$A' กลับมา

set_time_limit(350);

pg_query("BEGIN WORK");
$status=0;

//หาชื่อชื่อของ user
$qry_userFullName = pg_query("select \"fullname\" from \"Vfuser\" where \"username\" = 'sureerat.kha' ");
$resFullname = pg_fetch_array($qry_userFullName);
list($userFullname) = $resFullname; // ชื่อเต็มของ user

$qry = pg_query("select a.* FROM \"thcap_temp_receipt_otherpay\" a  where a.\"receiptID\" not in((select b.\"receiptID\" from \"thcap_temp_receipt_details\" b)) ");
while($res = pg_fetch_array($qry))
{
	$receiptID = $res["receiptID"]; // เลขที่สัญญา
	
	$qry_sContract = pg_query("select * from \"thcap_temp_int_201201\" where \"receiptID\" = '$receiptID' "); // หาเลขที่สัญญา
	while($res_sContract = pg_fetch_array($qry_sContract))
	{
		$contractID = $res_sContract["contractID"]; // เลขที่สัญญา
	}
	
	// ค้นหาผู้กู้หลัก -- ปัจจุบันรองรับเพียงคนเดียว
	if($qry_namemain = pg_query("select \"thcap_fullname\", \"thcap_address\" from \"vthcap_ContactCus_detail\" where \"contractID\" = '$contractID' and  \"CusState\" = '0'")); else $status++;
	if($resnamemain = pg_fetch_array($qry_namemain)){
		$name3 = trim($resnamemain["thcap_fullname"]);
		$address = trim($resnamemain["thcap_address"]);
	}
	
	// ค้นหาผู้กู้ร่วม -- ปัจจุบันรองรับได้หลายคน
	if($qry_name = pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\" where \"contractID\" = '$contractID' and \"CusState\" > 0")); else $status++;
	$numco = pg_num_rows($qry_name);
	$i=1;
	$nameco = "";
	while($resco=pg_fetch_array($qry_name)){
		$name2 = trim($resco["thcap_fullname"]);
		if($numco == 1){ //กรณีมีชื่อเดียวไม่ต้องใส่ comma
			$nameco = $name2;
		}else{ 
			if($i == $numco){
				$nameco = $nameco.$name2;
			}else{
				$nameco = $nameco.$name2.",";
			}
		}
		$i++;
	}
	
	//หาที่อยู่
	$qry_addrFull = pg_query("select \"thcap_address\" from \"vthcap_ContactCus_detail\" where \"contractID\" = '$contractID' ");
	$res_addrFull = pg_fetch_array($qry_addrFull);
	list($addrFull) = $res_addrFull;
	
	//หาที่อยู่ส่งจดหมาย
	if($qry_addr = pg_query("SELECT concat(COALESCE(btrim(\"A_NO\"), ''), '', COALESCE(
	CASE
		WHEN \"A_SUBNO\" IS NULL OR \"A_SUBNO\" = '-' OR \"A_SUBNO\" = '--' THEN ''
		ELSE concat(' หมู่ ', btrim(\"A_SUBNO\"))
	END, ''), '', COALESCE(
	CASE
		WHEN \"A_SOI\" IS NULL OR \"A_SOI\" = '-' OR \"A_SOI\" = '--' THEN ''
		ELSE concat(' ซอย', btrim(\"A_SOI\"))
	END, ''), '', COALESCE(
	CASE
		WHEN \"A_RD\" IS NULL OR \"A_RD\" = '-' OR \"A_RD\" = '--' THEN ''
		ELSE concat(' ถนน', btrim(\"A_RD\"))
	END, ''), '', COALESCE(
	CASE
		WHEN \"A_TUM\" IS NULL OR \"A_TUM\" = '-' OR \"A_TUM\" = '--' THEN ''
		ELSE 
			CASE
				WHEN \"A_PRO\" = 'กรุงเทพ' OR \"A_PRO\" = 'กรุงเทพฯ' OR \"A_PRO\" = 'กรุงเทพมหานคร' OR \"A_PRO\" = 'กทม' OR \"A_PRO\" = 'กทม.' THEN concat(' แขวง', btrim(\"A_TUM\"))
				ELSE concat(' ตำบล', btrim(\"A_TUM\"))
			END
	END, ''), '', COALESCE(
	CASE
		WHEN \"A_AUM\" IS NULL OR \"A_AUM\" = '-' OR \"A_AUM\" = '--' THEN ''
		ELSE 
			CASE
				WHEN \"A_PRO\" = 'กรุงเทพ' OR \"A_PRO\" = 'กรุงเทพฯ' OR \"A_PRO\" = 'กรุงเทพมหานคร' OR \"A_PRO\" = 'กทม' OR \"A_PRO\" = 'กทม.' THEN concat(' เขต', btrim(\"A_AUM\"), ' ')
				ELSE concat(' อำเภอ', btrim(\"A_AUM\"), ' ')
			END
	END, ''), '', COALESCE(
	CASE
		WHEN \"A_PRO\" IS NULL THEN ''
		ELSE 
			CASE
				WHEN \"A_PRO\" = 'กรุงเทพ' OR \"A_PRO\" = 'กรุงเทพฯ' OR \"A_PRO\" = 'กรุงเทพมหานคร' OR \"A_PRO\" = 'กทม' OR \"A_PRO\" = 'กทม.' THEN btrim(\"A_PRO\")
				ELSE concat('จังหวัด', btrim(\"A_PRO\"))
			END
	END, ''), ' ', COALESCE(
	CASE
		WHEN \"A_POST\" IS NULL OR \"A_POST\" = '-' OR \"A_POST\" = '--' OR \"A_POST\" = '0' THEN ''
		ELSE btrim(\"A_POST\")
	END, ''), '', '') AS sentaddress
	FROM \"thcap_addrContractID\"
	where \"contractID\" = '$contractID' and \"addsType\" = '3'")); else $status++;
	list($sentaddress)=pg_fetch_array($qry_addr);
	
	//เช็คค่าว่างของตัวแปร เพื่อใช้ในการ insert ลงฐานข้อมูล
	$nameco = checknull($nameco); // ชื่อผู้กู้ร่วม
	$addrFull = checknull($addrFull); // ที่อยู่
	$sentaddress = checknull($sentaddress); // ที่อยู่ที่ส่งจดหมาย
	
	$qry_insertMain = "INSERT INTO \"thcap_temp_receipt_details\"(\"receiptID\", \"doerID\", \"doerStamp\", \"backAmt\", \"backDueDate\", \"nextDueAmt\", \"nextDueDate\", \"cusFullname\",
						\"cusCoFullname\", \"userFullname\", \"addrFull\", \"addrSend\", \"whtRef\", \"typeReceive\", \"typeDetail\", \"receiptRemark\")
						VALUES('$receiptID', 'sureerat.kha', '2012-04-12', null, null, null, null, '$name3', $nameco, '$userFullname', $addrFull, $sentaddress, null, null, null, null) ";
						
	if($runqry = pg_query($qry_insertMain)){}else{$status++; echo "<br>$qry_insertMain<br>";}
}

if($status == 0)
{
	pg_query("COMMIT");
	echo "<br><center>บันทึกสมบูรณ์</center>";
}
else
{
	pg_query("ROLLBACK");
	echo "<br><center>บันทึกผิดพลาด!!</center>";
}

?>
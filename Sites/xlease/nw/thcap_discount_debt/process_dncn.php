<?php
include("../../config/config.php");
include("../function/checknull.php");
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php 
$logs_any_time = nowDateTime();
$addUser = $_SESSION["av_iduser"];
$contractID = pg_escape_string($_POST["contractID"]);
$method = pg_escape_string($_POST["method"]);
$typeDiscount = pg_escape_string($_POST["typeDiscount"]); //ประเภทส่วนลด
$discountAmt = pg_escape_string($_POST["discountAmt"]); //จำนวนเงินส่วนลด
$dcNoteDate = pg_escape_string($_POST["dcNoteDate"]); //วันที่ออกรายการมีผล
$dcNoteDescription = pg_escape_string($_POST["dcNoteDescription"]); //เหตุผลในการคืน
$debtID = pg_escape_string($_POST["debtID"]); // รหัสหนี้
$myVat = pg_escape_string($_POST["myVat"]); // อัตราดอกเบี้ย
?>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<?php
pg_query("BEGIN WORK");
$status = 0;

// ตรวจสอบก่อนว่ามีการขอยกเว้นหนี้ไปก่อนหน้านี้แล้วหรือยัง
$qry_except_debt = pg_query("select \"debtID\" from \"thcap_temp_except_debt\" where \"debtID\" = '$debtID' and \"Approve\" is null ");
$row_except_debt = pg_num_rows($qry_except_debt);
if($row_except_debt > 0)
{ // มีการขออนุมัติอยู่แล้ว
	$status++;
	$error = "มีการขอยกเว้นหนี้ของรายการนี้อยู่แล้ว";
}

// ตรวจสอบก่อนว่ามีการขออนุมัติไปก่อนหน้านี้แล้วหรือยัง
$qry_chkHaveData = pg_query("select \"debtID\" from account.\"thcap_dncn\" where \"debtID\" = '$debtID' and \"dcNoteStatus\" = '8' and \"subjectStatus\" = '2' ");
$row_chkHaveData = pg_num_rows($qry_chkHaveData);
if($row_chkHaveData > 0)
{ // มีการขออนุมัติอยู่แล้ว
	$status++;
	$error = "มีการขอส่วนลดของรายการนี้อยู่แล้ว";
}

$qry_other = pg_query("select \"typePayID\", \"typePayRefValue\", \"typePayRefDate\", \"typePayAmt\", \"debtNet\", \"debtVat\", \"debtDueDate\"
						from public.\"vthcap_otherpay_debt_current\" where \"debtID\" = '$debtID' ");
while($res_name=pg_fetch_array($qry_other))
{
	$typePayID=trim($res_name["typePayID"]); // รหัสประเภทค่าใช้จ่าย
	$typePayRefValue=trim($res_name["typePayRefValue"]); // ค่าอ้างอิงของค่าใช้จ่าย
	$typePayRefDate=trim($res_name["typePayRefDate"]); // วันที่ตั้งหนี้
	$typePayAmt=trim($res_name["typePayAmt"]); // จำนวนหนี้
	$debtNet=trim($res_name["debtNet"]); // จำนวนหนี้ก่อน VAT
	$debtVat=trim($res_name["debtVat"]); // จำนวน VAT
	$debtDueDate=trim($res_name["debtDueDate"]); // วันครบกำหนดชำระ
}

// คำนวนจำนวนหนี้ใหม่
if($typeDiscount == "before")
{
	$discountDebtNet = round($discountAmt,2); // ส่วนลดก่อน vat
	$discountDebtVat = round(($discountDebtNet * $myVat / 100),2); // ส่วนลด vat
	$sumDiscount = round(($discountDebtNet + $discountDebtVat),2); // รวมส่วนลด
}
elseif($typeDiscount == "after")
{
	$sumDiscount = round($discountAmt,2); // รวมส่วนลด
	$discountDebtVat = round(($sumDiscount * $myVat / (100 + $myVat)),2); // ส่วนลด vat
	$discountDebtNet = round(($sumDiscount - $discountDebtVat),2); // ส่วนลดก่อน vat
}
else
{
	$status++;
}

if($method=="add"){ //กรณี (THCAP) ขอคืนเงินลูกค้า
	//gen เลข CN
	$qrycn=pg_query("SELECT \"thcap_gen_documentID\"('$contractID','$dcNoteDate','3')");//thcap_gen_documentID('เลขที่สัญญา','วันที่ออกรายการมีผล','3') 3=รหัส เลขที่ CN (รหัส CreditNote)
	list($dcNoteID)=pg_fetch_array($qrycn);

	//หาชื่อของพนักงาน
	$qryname=pg_query("select \"fullname\" from \"Vfuser\" where id_user='$addUser'");
	list($doerName)=pg_fetch_array($qryname);
	
	// หาชื่อผู้กู้หลักปัจจุบัน
	$qry_cusMain = pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\" where \"contractID\" = '$contractID' and \"CusState\" = '0' ");
	while($res_cusMain = pg_fetch_array($qry_cusMain))
	{
		if($cusMain == "")
		{
			$cusMain = $res_cusMain["thcap_fullname"];
		}
		else
		{
			$cusMain = $cusMain.", ".$res_cusMain["thcap_fullname"];
		}
	}
	$cusMain = checknull($cusMain);
	
	// หาชื่อผู้กู้ร่วม
	$qry_cusJoin = pg_query("select \"thcap_get_coborrower_details\"('$contractID')");
	list($cusJoin)=pg_fetch_array($qry_cusJoin);
	$cusJoin = checknull($cusJoin);
	
	// หาชื่อผู้ค้ำประกัน
	$qry_cusGua = pg_query("select \"thcap_get_guarantor_details\"('$contractID')");
	list($cusGua)=pg_fetch_array($qry_cusGua);
	$cusGua = checknull($cusGua);
	
	//insert ข้อมูลเพื่อเก็บว่าใครเป็นคนทำรายการ
	$ins_detail="INSERT INTO account.thcap_dncn_details(\"dcNoteID\", \"dcNoteRev\", \"doerID\", \"doerName\", \"doerStamp\", \"dcMainCusName\", \"dcCoCusName\", \"dcGuaCusName\", \"dcNoteUltimateDate\")
    VALUES ('$dcNoteID', 1, '$addUser','$doerName', '$logs_any_time', $cusMain, $cusJoin, $cusGua, '$dcNoteDate')";
	if($resins_detail=pg_query($ins_detail)){
	}else{
		$status++;
	}
	
	//insert ข้อมูลในตารางหลัก
	$ins="INSERT INTO account.thcap_dncn(
            \"dcNoteID\", \"dcType\", \"dcCompID\", \"dcNoteDescription\",
			\"dcNoteAmtNET\", \"dcNoteAmtVAT\", \"dcNoteAmtALL\",\"contractID\",\"dcNoteStatus\",\"debtID\",\"subjectStatus\", \"dcNoteDate\")
    VALUES ('$dcNoteID', 2, 'THCAP', '$dcNoteDescription',
            $discountDebtNet, $discountDebtVat, $sumDiscount, '$contractID', '8', '$debtID', '2', '$dcNoteDate')";
	if($resins=pg_query($ins)){
	}else{
		$status++;
	}
	
}
else if($method=="print"){
	$dcNoteID=$_GET["dcNoteID"];
	
	//หาว่าเป็นการ print ครั้งที่เท่าไหร่
	$qrymax=pg_query("select max(\"printTime\") from account.thcap_dncn_reprint where \"dcNoteID\"='$dcNoteID'");
	list($printTime)=pg_fetch_array($qrymax);
	
	if($printTime==""){ //กรณียังไม่มีการเพิ่มข้อมูล
		$printTime=0;
	}else{
		$printTime=$printTime+1;
	}
	
	//insert ข้อมูลว่ามีการ print แล้ว 1 ครั้ง
	$ins="INSERT INTO account.thcap_dncn_reprint(
          \"dcNoteID\", id_user, \"printStamp\", \"printTime\")
    VALUES ( '$dcNoteID', '$addUser', LOCALTIMESTAMP(0), '$printTime')";
	
	if($res=pg_query($ins)){
	}else{
		$status++;
	}
}

if($status == 0){
	pg_query("COMMIT");
	
	if($method=="print"){
		//สั่งให้ popup หน้า pdf 
		echo "<script type=\"text/javascript\">";
			echo "javascript:popU('pdf_reprint.php?dcNoteID=$dcNoteID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740');";
			echo "opener.location.reload(true);
				   self.close();";
		echo "</script>";
	}else{
		echo "<div style=\"text-align:center;padding-top:50px\"><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></div>";
		echo "<meta http-equiv='refresh' content='3; URL=frm_Index.php'>";
	}	
}else{
	pg_query("ROLLBACK");
	echo "<div style=\"text-align:center;padding-top:50px\"><b>ไม่สามารถบันทึกข้อมูลได้ $error กรุณาลองใหม่อีกครั้ง</b></div>";
	if($method!="print"){
	echo "<meta http-equiv='refresh' content='3; URL=frm_Index.php'>";
	}
}
?>

<?php
session_start();
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
</head>
<?php
$id_user=$_SESSION["av_iduser"];
	
$chqKeepID=$_POST["chqKeeperID"];
$revID=$_POST["revChqID"];
$conf=$_POST["conf"];

$current_date = nowDateTime();

pg_query("BEGIN WORK");
$status = 0;
$concurrent = 0;
$lostData = 0;
$invalid = 0;
$errorMessage = "";

for($i=0;$i<sizeof($chqKeepID);$i++){ //รายการที่ทำการ update
	$chqKeeperID = $chqKeepID[$i];
	$revChqID=$revID[$i];
	$confirm=$conf[$i];
	if($confirm==0){
		continue;
	}
	//หาเลขที่สัญญา
		$qry_conid=pg_query("select \"revChqToCCID\" from finance.\"thcap_receive_cheque\" a WHERE \"revChqID\" ='$revChqID' ");
		list($contractid) = pg_fetch_array($qry_conid);
		
	//เช็คว่ามีเลขที่สัญญาในระบบ?
		$qry_chk_con = pg_query("select \"contractID\" from thcap_contract where \"contractID\"='$contractid' ");
		$num_chk_con = pg_num_rows($qry_chk_con);
		if($num_chk_con==0){
			$invalid++; 
			$errorMessage = $errorMessage."ไม่พบเลขที่สัญญาในระบบ<br>";
		}
		
	// หาวันที่นำเช็คเข้าธนาคาร
	$qry_fr="select \"giveTakerDate\"::date from finance.\"V_thcap_receive_cheque_keeper_cheManage\"
					WHERE \"chqKeeperID\" = '$chqKeeperID' and \"revChqID\" = '$revChqID' and \"revChqStatus\" ='7'";
	if($res_fr=pg_query($qry_fr)){
		$num_fr = pg_num_rows($res_fr);
		if($num_fr>0){
			$bankRevDate = pg_fetch_result($res_fr,0);
		}else{
			$lostData++;
		}
		
	} else {
		$status++;
		$errorMessage = $errorMessage."error line : 58<br>";
	}
	
	
	//ตรวจสอบข้อมูลก่อนว่ามีการ update revChqStatus ในตาราง  thcap_receive_cheque เป็น 6 หรือยังถ้ามีแล้ว แสดงมีการทำรายการก่อนหน้านี้แล้ว
	$qrychk=" SELECT * FROM finance.\"thcap_receive_cheque\"  WHERE \"revChqID\"='$revChqID' and \"revChqStatus\"='6' ";
	if($res_chk=pg_query($qrychk)){
		
		$numchk = pg_num_rows($res_chk);
		
	} else {
		$status++;
		$errorMessage = $errorMessage."error line : 70<br>";
	}
	
	if($numchk>0){
		$concurrent++; //แสดงว่ามีการทำรายการก่อนหน้านี้แล้ว
		break;
	}
	
	$updatekeep="update finance.thcap_receive_cheque_keeper set \"replyByTakerID\"='$id_user',
		\"replyByTakerStamp\"='$current_date',
		\"bankRevDate\"='$bankRevDate',
		\"bankRevResult\"='$confirm'
		where \"chqKeeperID\"='$chqKeeperID'";
	if(pg_query($updatekeep)){
	}else{
		$status++;
			$errorMessage = $errorMessage."error line : 86<br>";
	}
	
	if($confirm=="3"){
		$revchqstatus="2"; //เดิมถ้าเป็นเช็คเด้ง จะบอกว่าเช็คเด้ง
		//$revchqstatus="8"; // เปลี่ยนใหม่เป็น ถ้าเช็คเด้ง จะบอกว่า เช็คที่รับถูกส่งให้ผู้เก็บเช็ค เพื่อจะได้นำไปทำรายการใหม่
	}else if($confirm=="4"){
		$beforChqToBank_qry="select \"beforChqtoBank\" from finance.thcap_receive_cheque_keeper where \"chqKeeperID\"='$chqKeeperID' ";
		if($res_beforChqToBank=pg_query($beforChqToBank_qry)){
			if($revchqstatus=pg_fetch_result($res_beforChqToBank,0)){
			} else {
				$status++;
				$errorMessage = $errorMessage."error line : 98<br>";
			}
		}else {
			$status++;
			$errorMessage = $errorMessage."error line : 102<br>";
		}
		
		if($revchqstatus==""){
			$revchqstatus="8";
		}
	}else {
		$revchqstatus="6";
	}

	$updaterev="update finance.thcap_receive_cheque set \"revChqStatus\"='$revchqstatus'
				where \"revChqID\"='$revChqID'";
	if(pg_query($updaterev)){
	}else{
		$status++;
		$errorMessage = $errorMessage."error line : 117<br>";
	}
	
	if($confirm=="3")
	{ //ถ้าเป็นเช็คเด้ง ให้เก็บ log ก่อนล้างรายการ
		// update keppFrom ให้เป็น สถานะ 2
		$update="update finance.thcap_receive_cheque_keeper set \"keepFrom\" = '2'
		where \"revChqID\"='$revChqID' and \"keepFrom\"<>'3' returning \"keepFrom\"";
		if($resupdate=pg_query($update)){
			if(list($keepFrom)=pg_fetch_array($resupdate)){
			}else{
				$status++;
				$errorMessage = $errorMessage."error line : 129<br>";
			}
		}else{
			$status++;
			$errorMessage = $errorMessage."error line : 133<br>";
		}		
		$inskeep="INSERT INTO finance.thcap_receive_cheque_keeper_log(
            \"revChqID\", \"keepFrom\", \"keeperID\", \"keeperStamp\", \"giveTakerID\", 
            \"giveTakerStamp\", \"giveTakerDate\", \"replyByTakerID\", 
            \"replyByTakerStamp\", \"bankRevDate\", \"bankRevResult\", \"getBankSlip\", 
            \"giveCusConID\", \"giveCusDate\", \"receiptIDForReturn\", \"keepChqDate\", 
            \"BID\",\"result\")
			SELECT \"revChqID\", \"keepFrom\", \"keeperID\", \"keeperStamp\", \"giveTakerID\", 
            \"giveTakerStamp\", \"giveTakerDate\", \"replyByTakerID\", 
            \"replyByTakerStamp\", \"bankRevDate\", \"bankRevResult\", \"getBankSlip\", 
            \"giveCusConID\", \"giveCusDate\", \"receiptIDForReturn\", \"keepChqDate\", 
            \"BID\",\"result\" FROM finance.thcap_receive_cheque_keeper WHERE \"revChqID\"='$revChqID'";
		if(pg_query($inskeep)){
		}else{
			$status++;
			$errorMessage = $errorMessage."error line : 150<br>";
		}
		
		$insNewKeep="INSERT INTO finance.thcap_receive_cheque_keeper(
					\"revChqID\", \"keepFrom\", \"keeperID\", \"keeperStamp\", \"getBankSlip\", 
					\"giveCusConID\", \"giveCusDate\", \"receiptIDForReturn\", \"keepChqDate\", \"result\")
					SELECT \"revChqID\", \"keepFrom\", \"keeperID\", \"keeperStamp\", \"getBankSlip\", 
					\"giveCusConID\", \"giveCusDate\", \"receiptIDForReturn\", \"keepChqDate\", \"result\"
					FROM finance.thcap_receive_cheque_keeper
					WHERE \"chqKeeperID\" = '$chqKeeperID' ";
		if(pg_query($insNewKeep)){
		}else{
			$status++;
			$errorMessage = $errorMessage."error line : 163<br>";
		}
		
		//ดึงข้อมูลขึ้นมาเพื่อนำไปตั้งหนี้
		$keepFromback=$keepFrom-1; //ถ้า keepFrom เป็น 2 ขึ้นไปบอกให้รู้ว่าเป็นการนำเข้าใหม่ครั้งที่เท่าไหร่ เช่นถ้าเป็น 2 แสดงว่าเป็นการนำเช็คเข้าครั้งที่ 2 แต่เป็นเช็คเด้งครั้งที่ 1 ดังนั้นจึงต้อง - 1 เพื่อให้ได้ครั้งที่เด้ง
		$qrychkreturn="select \"bankChqAmt\",\"revChqToCCID\",\"bankOutID\"||'-'||\"bankChqNo\"||'-'||\"chqSubmitTimes\" from finance.\"V_thcap_receive_cheque_keeper_cheManage\" where \"chqKeeperID\"='$chqKeeperID'";
		if($res_chkreturn=pg_query($qrychkreturn)){
			
			$num_chkreturn = pg_num_rows($res_chkreturn);
			
			if($num_chkreturn>0){
				if(list($bankChqAmt,$contractID,$bankref)=pg_fetch_array($res_chkreturn)){
				}else{
					$status++;
					$errorMessage = $errorMessage."error line : 177<br>";
				}
			} else {
				$lostData++;
			}
			
		} else {
			$status++;
			$errorMessage = $errorMessage."error line : 185<br>";
		}

		if($bankChqAmt!=""){
			//กรณีพบว่าเป็นเช็คเด้งหรือเช็คคืน ให้ตั้งหนี้ด้วย
			if($bankChqAmt<=300000){
				$bankChqAmt=1070;
			}else if($bankChqAmt>300000){
				$bankChqAmt=2140;
			}
		} else {
			$lostData++;
		}
		
		if($contractID!=""){
			//หา type ค่าปรับเช็ค จาก function account."thcap_getReturnChqFeeType"
			$qrytype="select account.\"thcap_getReturnChqFeeType\"('$contractID')";
		} else {
			$lostData++;
		}
		
		if($res_type=pg_query($qrytype)){
		
			$num_type=pg_num_rows($res_type);
			
			if($num_type>0){
				if(list($typechq)=pg_fetch_array($res_type)){
				}else{
					$status++;
					$errorMessage = $errorMessage."error line : 214<br>";
				}
			} else {
				$lostData++;
			}
		}else {
			$status++;
			$errorMessage = $errorMessage."error line : 221<br>";
		}
		
		
		// หาชื่อธนาคารและเลขที่เช็ค
		$qrychkbank = "select \"bankOutID\", \"bankChqNo\" from finance.\"V_thcap_receive_cheque_keeper_cheManage\" where \"revChqID\"='$revChqID'";
		if($res_chkbank=pg_query($qrychkbank)){
		
			$num_chkbank = pg_num_rows($res_chkbank);
			
			if($num_chkbank>0){
			
				if(list($bankOutID_chkdup, $bankChqNo_chkdup) = pg_fetch_array($res_chkbank)){
				}else{
					$status++;
					$errorMessage = $errorMessage."error line : 236<br>";
				}
				
			}else{
				$lostData++;
			}
			
		}else{
			$status++;
			$errorMessage = $errorMessage."error line : 245<br>";
		}
		
		
		// 1. ตรวจสอบว่า REF นั้นเป็น format ว่า LIKE * {ชื่อธนาคาร}-{เลขที่เช็ค} * ของใบที่กำลังทำรายการมีหรือไม่
		// 2. ตรวจสอบว่าวันที่ตั้งหนี้นั้นเป็นวันที่เดียวกันกับข้อแรกหรือไม่ 
		if($bankOutID_chkdup!="" and $bankChqNo_chkdup!=""){
			$qry_chkdup = "select * from \"thcap_temp_otherpay_debt\" where \"typePayRefValue\" like '%$bankOutID_chkdup-$bankChqNo_chkdup%' and \"typePayRefDate\" = '$bankRevDate' and \"debtStatus\" in('1','9') ";
		} else {
			$lostData++;
		}
		if($res_chkdup=pg_query($qry_chkdup)){
		
			$row_chkdup = pg_num_rows($res_chkdup);
		
		} else {
			$status++;
		
		}
		//ตรวจสอบว่ามีการตั้งหนี้หรือยัง
		$qry_status_setdebt = pg_query("select \"status_setdebt\" from finance.thcap_receive_cheque_keeper where \"chqKeeperID\"='$chqKeeperID' ");
		$status_setdebt = pg_fetch_result($qry_status_setdebt,0);
		
		if($row_chkdup > 0)
		{				
			if($status_setdebt=='1'){				
				echo "<div style=\"padding: 50px 0px;text-align:center;\">มีการตั้งหนี้ ของธนาคาร $bankOutID_chkdup เลขที่เช็ค $bankChqNo_chkdup ในนี้ที่ $bankRevDate ไปก่อนหน้านี้แล้ว จึงไม่ทำการเพิ่มอีก</div>";
			}
			else{
				$concurrent++;
				echo "<div style=\"padding: 50px 0px;text-align:center;\">มีการตั้งหนี้ ของธนาคาร $bankOutID_chkdup เลขที่เช็ค $bankChqNo_chkdup ในนี้ที่ $bankRevDate ไปก่อนหน้านี้แล้ว</div>";
			}
		}
		
		//บันทึกข้อมูล
		if($contractID!="" and $typechq!="" and $bankref!="" and $bankRevDate!="" and $bankChqAmt!="" and $id_user!=""){
			$ins="SELECT thcap_process_setdebtloan('$contractID','$typechq','$bankref','$bankRevDate','$bankChqAmt',null,'$id_user')";
		}else {
			$lostData++;
		}
		if($ins!=""){
			if($res_ins=pg_query($ins)){
		
				$num_ins = pg_num_rows($res_ins);
			
				if($num_ins>0){
					if(list($return) = pg_fetch_array($res_ins)){
					}else{
						$status++;
						$errorMessage = $errorMessage."error line : 286<br>";
					}
				} else {
					$status++;
					$errorMessage = $errorMessage."error line : 290<br>";
				}
			}else{
				$status++;
				$errorMessage = $errorMessage."error line : 294<br>";
			}
		}
		
		if($return == 't'){	
			$update="update finance.thcap_receive_cheque_keeper set \"status_setdebt\" = '1'
			where \"chqKeeperID\"='$chqKeeperID' and \"status_setdebt\" is null";
			if($resupdate=pg_query($update)){				
			}else{
				$status++;				
			}
			
		}else{ 
			if($status_setdebt=='1'){}
			else {
				$status++; 
				$errorMessage = $errorMessage."error line : 301<br>";
			}
		}
	}
	if($confirm=="4"){
		//Insert ข้อมูลเก็บรักษาเช็ค
		$insNewKeep="INSERT INTO finance.thcap_receive_cheque_keeper(
					\"revChqID\", \"keepFrom\", \"keeperID\", \"keeperStamp\", \"getBankSlip\", 
					\"giveCusConID\", \"giveCusDate\", \"receiptIDForReturn\", \"keepChqDate\", \"result\")
					SELECT \"revChqID\", \"keepFrom\", \"keeperID\", \"keeperStamp\", \"getBankSlip\", 
					\"giveCusConID\", \"giveCusDate\", \"receiptIDForReturn\", \"keepChqDate\", \"result\"
					FROM finance.thcap_receive_cheque_keeper
					WHERE \"chqKeeperID\" = '$chqKeeperID' ";
		if(pg_query($insNewKeep)){
		}else{
			$status++;
		}
		
		// update keppFrom ให้เป็น สถานะ 3
		$update="update finance.thcap_receive_cheque_keeper set \"keepFrom\" = '3'
		where \"chqKeeperID\"='$chqKeeperID' returning \"keepFrom\"";
		if($resupdate=pg_query($update)){
			if(list($keepFrom)=pg_fetch_array($resupdate)){
			}else {
				$status++;
				$errorMessage = $errorMessage."error line : 325<br>";
			}
		}else{
			$status++;
			$errorMessage = $errorMessage."error line : 329<br>";
		}
		
		$inskeep="INSERT INTO finance.thcap_receive_cheque_keeper_log(
            \"revChqID\", \"keepFrom\", \"keeperID\", \"keeperStamp\", \"giveTakerID\", 
            \"giveTakerStamp\", \"giveTakerDate\", \"replyByTakerID\", 
            \"replyByTakerStamp\", \"bankRevDate\", \"bankRevResult\", \"getBankSlip\", 
            \"giveCusConID\", \"giveCusDate\", \"receiptIDForReturn\", \"keepChqDate\", 
            \"BID\",\"result\")
			SELECT \"revChqID\", \"keepFrom\", \"keeperID\", \"keeperStamp\", \"giveTakerID\", 
            \"giveTakerStamp\", \"giveTakerDate\", \"replyByTakerID\", 
            \"replyByTakerStamp\", \"bankRevDate\", \"bankRevResult\", \"getBankSlip\", 
            \"giveCusConID\", \"giveCusDate\", \"receiptIDForReturn\", \"keepChqDate\", 
            \"BID\",\"result\" FROM finance.thcap_receive_cheque_keeper WHERE \"revChqID\"='$revChqID'";
		if(pg_query($inskeep)){
		}else{
			$status++;
			$errorMessage = $errorMessage."error line : 346<br>";
		}
	}
}
if($concurrent>0){
	pg_query("ROLLBACK");
	echo "<div align=\"center\"><font size=4><b>มีบางรายการได้รับไปก่อนหน้านี้แล้ว กรุณาทำรายการใหม่อีกครั้ง</b></font></div>";
	echo "<div style=\"padding: 20px;text-align:center;\"><input type=button value=\"  กลับ  \" onclick=\"window.location='frm_confirmchqtobank.php'\"></div>";
}else if($invalid>0){
	pg_query("ROLLBACK");
	echo "<div align=\"center\"><font size=4><b>มีข้อมูลไม่ถูกต้องระหว่างทำรายการ</b></font></div>";
	echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>$errorMessage</b></font></div>"; 
	echo "<div style=\"padding: 20px;text-align:center;\"><input type=button value=\"  กลับ  \" onclick=\"window.location='frm_confirmchqtobank.php'\"></div>";
} else if($lostData>0){
	pg_query("ROLLBACK");
	echo "<div align=\"center\"><font size=4><b>มีข้อมูลสูญหายระหว่างทำรายการ กรุณาทำรายการใหม่อีกครั้ง</b></font></div>";
	echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>$errorMessage</b></font></div>"; 
	echo "<div style=\"padding: 20px;text-align:center;\"><input type=button value=\"  กลับ  \" onclick=\"window.location='frm_confirmchqtobank.php'\"></div>";
} else{
	if($status == 0){
		pg_query("COMMIT");
		echo "<div style=\"padding-top: 50px;text-align:center;\"><font size=4><b>บันทึกข้อมูลเรียบร้อยแล้ว โดยมีเช็คที่ถูกรับดังนี้</b></font></div>";
		echo "<div style=\"padding-top: 10px;margin-left:330px;\">";
	
		for($i=0;$i<sizeof($chqKeepID);$i++){ //รายการที่ทำการ update
			$chqKeeperID2 = $chqKeepID[$i];
			$revChqID2=$revID[$i];
			$confirm2=$conf[$i];
			
			if($confirm2==0){
				continue;
			}
		
			//ค้นหาเลขที่เช็ค
			$qurycheck=pg_query("select \"bankChqNo\" from finance.thcap_receive_cheque where \"revChqID\"='$revChqID2'");
			$res_check=pg_fetch_array($qurycheck);
			$bankChqNo=$res_check["bankChqNo"];
		
			if($confirm2==1){
				$txtcon="เข้าปกติ";
			}else if($confirm2==2){
				$txtcon="Too Late";
			}else if($confirm2==3){
				$txtcon="เช็คคืนรอจัดการ";
				$color="style=\"background-color:#F0FFF0\"";
			} else if($confirm2==4){
				$txtcon="ยกเลิกนำเช็คเข้าธนาคาร";
			}
		
			echo "<div $color>- เลขที่เช็ค  <b>$bankChqNo</b>  ผลการนำเข้า  <b>$txtcon</b></div>";
			unset($color);
		}
		echo "</div>";
		echo "<div style=\"padding: 20px;text-align:center;\"><input type=button value=\"  กลับ  \" onclick=\"window.location='frm_confirmchqtobank.php'\"></div>";
	}else{
		pg_query("ROLLBACK");
		echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ทำรายการไม่สมบูรณ์ กรุณาลองใหม่อีกครั้ง</b></font></div>";
		echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>$errorMessage</b></font></div>"; 
		echo "<div style=\"padding: 50px;text-align:center;\"><input type=button value=\"  กลับ  \" onclick=\"window.location='frm_confirmchqtobank.php'\"></div>";
	}
}
?>
</html>
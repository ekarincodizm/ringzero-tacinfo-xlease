<?php
session_start();
include("../../../config/config.php");
include("../../function/checknull.php");
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
$iduser = $_SESSION["av_iduser"];//รหัสของผู้ใช้
$tm_pk = pg_escape_string($_GET["tm_pk"]);
$appv = pg_escape_string($_GET["appv"]);
$moveID = pg_escape_string($_GET["moveID"]);
if($moveID==""){
	$tm_pk = pg_escape_string($_POST["tm_pk"]);
	$moveID = pg_escape_string($_POST["moveID"]);
	if(isset($_POST["appv"])){
		$appv="1";//อนุมัติ
	}else{
		$appv="0";//ไม่อนุมัติ
	}
}
?>

<script type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}

function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
function popWindow(wName){
	features = 'toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740';
	pop = window.open('',wName,features);
	if(pop.focus){ pop.focus(); }
	return true;
}
</script>

<?php
$id_user=$_SESSION["av_iduser"];
$logs_any_time = nowDateTime();
$resultappv="yes";
//--ตรวจสอบผู้ทำรายการย้าย อนุมัติ
if($moveID==$iduser){
	$qry_emplevel = pg_query("select \"emplevel\" from public.\"Vfuser\" where \"id_user\" = '$iduser'");
	$rs_emplevel = pg_fetch_array($qry_emplevel);
	$emplevel=$rs_emplevel["emplevel"];
    if($emplevel>1){  
		$resultappv="no";} //ไม่ให้ทำการอนุมัติ หรือ ไม่อนุมัติ
}
if($resultappv=="yes")
{
	//--------------- เริ่มบันทึกข้อมูล
	pg_query("BEGIN");
	$status = 0;

	$qry_check = pg_query("select \"appstatus\" from public.\"thcap_transfermoney_c2c_temp\" where \"tm_pk\" = '$tm_pk' ");
	$statusCHK = pg_fetch_result($qry_check,0);
	if($statusCHK == "0")
	{
		$error = "รายการนี้ถูกปฏิเสธไปก่อนหน้านี้แล้ว";
		$status++;
	}
	elseif($statusCHK == "1")
	{
		$error = "รายการนี้ถูกอนุมัติไปก่อนหน้านี้แล้ว";
		$status++;
	}
	elseif($statusCHK == "")
	{
		$error = "ไม่พบข้อมูล กรุณาแจ้งฝ่าย IT เพื่อทำการตรวจสอบ";
		$status++;
	}
	elseif($statusCHK == "2") // ถ้ารอการอนุมัติอยู่
	{
		if($appv == "0") // ถ้าไม่อนุมัติ
		{
			$sql_no_appv = "update public.\"thcap_transfermoney_c2c_temp\" set \"appstatus\" = '0' , \"appvID\" = '$id_user' , \"appvStamp\" = '$logs_any_time' 
						where \"tm_pk\" = '$tm_pk' and \"appstatus\" = '2' ";
			if($resultNO = pg_query($sql_no_appv))
			{}
			else
			{
				$status++;
			}
		}
		elseif($appv == "1") // ถ้าอนุมัติ
		{
			$query = pg_query("select * from public.\"thcap_transfermoney_c2c_temp\" where \"tm_pk\" = '$tm_pk' ");
			$numrows = pg_num_rows($query);
			while($result = pg_fetch_array($query))
			{
				$begin_conid = $result["begin_conid"]; // เลขที่สัญญาต้นทาง
				$begin_trans_type = $result["begin_trans_type"]; // ประเภทเงินต้นทาง
				$end_conid = $result["end_conid"]; // รหัสสัญญาปลายทาง
				$end_trans_type = $result["end_trans_type"]; // รหัสประเภทการโอนปลายทาง
				$end_trans_money = $result["end_trans_money"]; // จำนวนเงินที่รับของสัญญาปลายทาง
				$all_trans_money = $result["all_trans_money"]; // จำนวนเงินทั้งหมดที่โอนจากต้นทาง
				$masterID = $result["masterID"]; // รายการที่ทำพร้อมกัน
				$doerID = $result["doerID"]; // รหัสผู้ขอย้ายเงิน
				$doerStamp = $result["doerStamp"]; // วันเวลาที่ทำรายการ
				$changeMoneyDate = $result["changeMoneyDate"]; // วันที่ย้ายเงิน
				$reason = $result["reason"]; // เหตุผลในการย้ายเงิน
			}
			
			// จำนวนเงินคงเหลือของต้นทาง
			$qry_money_balance = pg_query("select \"contractBalance\" from \"thcap_contract_money\" where \"contractID\" = '$begin_conid' and \"moneyType\" = '$begin_trans_type' ");
			$contractBalance = pg_fetch_result($qry_money_balance,0);
			
			// ตรวจสอบว่าเมื่ออนุมัติจะทำให้จำนวนเงินของสัญญาที่โอนมาให้ติดลบหรือไม่
			if(($contractBalance - $end_trans_money) < 0)
			{
				$status++;
				
				if($begin_trans_type == "997")
				{
					$begin_trans_type_text = "ค้ำ";
				}
				elseif($begin_trans_type == "998")
				{
					$begin_trans_type_text = "พัก";
				}
				
				$error = "การกระทำดังกล่าว จะทำให้จำนวนเงิน$begin_trans_type_text ของเลขที่สัญญา $begin_conid ติดลบ กรุณาตรวจสอบ";
			}
		
			// หาประเภทการจ่าย
			IF($end_trans_type == "997")
			{
				$guarantee_trans_money = $end_trans_money; // เงินค้ำ
				$deposit_trans_money = 0; // เงินพัก
			}
			ELSEIF($end_trans_type == "998")
			{
				$guarantee_trans_money = 0; // เงินค้ำ
				$deposit_trans_money = $end_trans_money; // เงินพัก
			}
			ELSE
			{
				$status++;
				echo "เกิดข้อผิดพลาด ไม่พบประเภทการจ่าย";
			}
		
			if($status == 0) // ถ้ายังไม่พบ error อะไร ค่อยทำงานต่อ
			{
				/*SELECT "thcap_process_receiveLease"(เลขที่สัญญา, วันที่จ่าย แบบ วัน และ เวลา, ช่องทางการจ่าย(เฉพาะค่าอื่นๆ ไม่รวมเงินพักกับเงินค้ำ), จำนวนเงินที่จ่าย, ภาษีหัก ณ ที่จ่าย, บอกว่ามีภาษีหัก ณ ที่จ่ายหรือไม่ ถ้ามี เป็น 1 ไม่มีเป็น 0,
				รหัสพนักงาน ของคนที่ทำรายการ, เลขที่อ้างอิงภาษีหัก ณ ที่จ่าย, หมายเหตุ, บอกว่ามีใบเสร็จออกแทนหรือไม่ ถ้าใช่เป็น 1 ไม่ใช่เป็น 0,
				ชื่อประเภทของใบเสร็จออกแทน, เลขที่ใบเสร็จออกแทน, อัตราดอกเบี้ย(ปัจจุบันไม่ได้ใช้ จึงใส่ 0.00 ไปก่อน), arrayรหัสหนี้และwht , เงินพัก , เงินค้ำ, รหัส temp การย้ายเงินข้ามสัญญา, ChannelRef)*/
			
				$qry_newReceiptID = pg_query("SELECT \"thcap_process_receiveOther\"('$end_conid','$changeMoneyDate', '$begin_trans_type', '0.00', '0', '0', '$iduser', null, null, '0', null, null, '0.00', '{}', '$deposit_trans_money', '$guarantee_trans_money', '$tm_pk', '$begin_conid')");
				$newReceiptID = pg_fetch_result($qry_newReceiptID,0);
			
				if($newReceiptID == "" || $newReceiptID == "null" || $newReceiptID == null)
				{
					$status++;
				}
				else
				{
					// ตรวจสอบจำนวนเงินคงเหลือของต้นทางอีกครั้ง หลังทำการย้ายเงิน
					$qry_money_balance2 = pg_query("select \"contractBalance\" from \"thcap_contract_money\" where \"contractID\" = '$begin_conid' and \"moneyType\" = '$begin_trans_type' ");
					$contractBalance2 = pg_fetch_result($qry_money_balance2,0);
					
					if($contractBalance2 < 0)
					{
						$status++;
						
						if($begin_trans_type == "997")
						{
							$begin_trans_type_text = "ค้ำ";
						}
						elseif($begin_trans_type == "998")
						{
							$begin_trans_type_text = "พัก";
						}
				
						$error = "การกระทำดังกล่าว จะทำให้จำนวนเงิน$begin_trans_type_text ของเลขที่สัญญา $begin_conid ติดลบ กรุณาตรวจสอบ";
					}
				}
			}
		}
	}
}
if($resultappv=="no"){
	echo "<center><h2><font color=\"#0000FF\">ผิดพลาด เนื่องจากกำหนดให้ ผู้ทำรายการ ต้องไม่เป็น คนเดียวกับผู้ทำการอนุมัติ/ไม่อนุมัติ </font></h2></center>";
}
else
{
	if($status == 0)
	{
		//ACTIONLOG
			$sqlaction = "INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(THCAP) อนุมัติย้ายเงินข้ามสัญญา', '$logs_any_time')";
			if($result = pg_query($sqlaction)){}else{$status++;}
		//ACTIONLOG---
		pg_query("COMMIT");
		echo "<center><h2><font color=\"#0000FF\">บันทึกสำเร็จ</font></h2></center>";
		//echo "<center><input type=\"button\" value=\"ตกลง\" onclick=\"javascript:RefreshMe();\"></center>";
		if($appv == "1")
		{
			/*echo "<script type=\"text/javascript\">";
			echo "javascript:popU('../../Payments_Other/print_receipt_pdf.php?receiptID=$newReceiptID&typepdf=2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740');";
			echo "</script>";*/
			?>
			<!--ส่งค่าข้อมูลแบบ POST-->
			<form name ="my" action="../../Payments_Other/print_receipt_pdf.php" method="post" target="Details" onSubmit="return 		popWindow(this.target)">
				<input type="hidden" name="receiptID" id="receiptID" value="<?php echo $newReceiptID; ?>">
				<input type="hidden" name="typepdf" id="typepdf" value="2">
				<input name="print" type="submit" value="พิมพ์" hidden />
				<?php echo "<script type=\"text/javascript\">";
					  echo "document.forms['my'].print.click();";				 
		              echo "</script>";?>
			</form >
	<?php	}
		echo "<script type=\"text/javascript\">";
		echo "RefreshMe();";
		echo "</script>";
	}
	else
	{
		pg_query("ROLLBACK");
		echo "<center><h2><font color=\"#FF0000\">บันทึกข้อมูลผิดพลาด!!</font></h2></center>";
		if($error != ""){echo "<br><center><h2><font color=\"#FF0000\">$error</font></h2></center>";}
		echo "<center><input type=\"button\" value=\"ปิด\" onclick=\"javascript:RefreshMe();\"></center>";
	}
}
?>
<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");

$error_message = '';

pg_query("BEGIN WORK");
$status=0;
$user_id = $_SESSION["av_iduser"];

$app_datetime = nowDateTime();//วันเวลาปัจจุบันจาก server
$id = pg_escape_string($_POST["id"]);
$str_note = pg_escape_string($_POST["str_note"]);
$str_note = checknull($str_note);

$bywhat = pg_escape_string($_POST["bywhat"]);
$payerchqno_or_payeebankno = pg_escape_string($_POST["payerchqno_or_payeebankno"]);
$payeebankname = pg_escape_string($_POST["payeebankname"]);

if(isset($_POST["btn_app"])){
	$statusapp="1";//อนุมัติ
}else{
	$statusapp="0";//ไม่อนุมัติ
}

?>
<script type="text/javascript">
window.onbeforeunload = WindowCloseHanlder;
function WindowCloseHanlder()
{    
     opener.location.reload(true);
}
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
function RefreshMe(){	
    opener.location.reload(true);
    self.close();
}
</script>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">    
    <link type="text/css" rel="stylesheet" href="act.css"></link>    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>
<body>

<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
        

<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();" class="ui-button"></div>
<div style="clear:both;"></div>



<div align="left">
<?php
//ตรวจสอบว่ารายการนั้นรอการอนุมัติอยู่
$sql_appvStatus = pg_query("SELECT
								\"voucherDate\",
								\"ChannelAmt\",
								\"contractID\",
								\"cusid\",
								\"fullname\",
								\"byChannel\",
								\"doerID\",
								\"doerStamp\"
							FROM
								\"thcap_temp_payment_debtor_creditors_cost\"
							WHERE
								\"auto_id\"='$id' AND
								\"appvStatus\"='9'"
);
$numrows = pg_num_rows($sql_appvStatus);
if($numrows>0){	
	// =======================================================================================
	// กรณีอนุมัติ
	// =======================================================================================
	if($statusapp=='1'){

		while($res_appvStatus=pg_fetch_array($sql_appvStatus))
		{
			$voucherDate = $res_appvStatus["voucherDate"];
			$ChannelAmt = $res_appvStatus["ChannelAmt"];
			$contractID = $res_appvStatus["contractID"];
			$cusid = $res_appvStatus["cusid"];
			$fullname = $res_appvStatus["fullname"];
			$byChannel = $res_appvStatus["byChannel"];
			$doerID = $res_appvStatus["doerID"];
			$doerStamp = $res_appvStatus["doerStamp"];
		}
		
		// Checknull ในข้อมูลที่ได้รับ
		$fullname = checknull($fullname);

		//ตรวจสอบว่า สัญญานั้นมีอยู่ในระบบหรือไม่
		$sql = pg_query("select \"contractID\" from \"thcap_contract\" where \"contractID\"='$contractID'");
		$numrows_conid = pg_num_rows($sql);
		
		// ตรวจสอบประเภทสัญญา ของสัญญานั้นๆ
		$vcontype=pg_query("SELECT \"thcap_get_contractType\"('$contractID')");
		$vcontype=pg_fetch_array($vcontype);
		list($vcontype)=$vcontype;
		
		// *todo ในส่วนนี้ยังไม่รองรับการทำงานแบบ CONCURRENCY แบบ 100% เนื่องจากในขณะที่ทำ voucher ยังมีโอกาสที่จะมีผู้ที่ทำรายการพร้อมๆ กัน ที่ถูกต้อง Query ในการตรวจสอบเพื่อทำรายการ ควรอยู่ใน Single Query
		if(($numrows_conid==0) and ($cusid =='')){}
		else if($numrows_conid>0){
			// หาผลรวมที่ได้ทำการ อนุมัติ ทั้งหมด
			$res_ChannelAmt=0;		
			$qry_sum = pg_query("SELECT \"thcap_get_all_payment_paid_for_contract\"('$contractID')");
			list($res_ChannelAmt) = pg_fetch_array($qry_sum);
			// หาจำนวนเงินลงทุน โดยใส่ parameter type = 3 (เงินลงทุนรวมภาษีมูลค่าเพิ่ม (ถ้ามี) ก่อนหักเงินดาวน์ (ถ้ามี))
			$qry = pg_query("SELECT \"thcap_get_iniinvestmentamt\"('$contractID','3')");
			list($res_amount) = pg_fetch_array($qry);			
			// ที่จ่ายแล้ว+รอการอนุมัติ
			$sum_amt=$res_ChannelAmt+$ChannelAmt;
		}
		
		// ตัดช่องว่างออก เพื่อป้องกันข้อผิดพลาดจากการเปรียบเทียบค่า
		$sum_amt = trim($sum_amt);
		$res_amount = trim($res_amount);
		
		// ตรวจสอบว่าผมรวมที่ได้ เมื่อเทียบกับยอดคงเหลือที่จะต้อง ออก payment voucher ยอดที่จะออกเกินยอดคงเหลือหรือไม่
		//*todo ในส่วนนี้ (($numrows_conid==0) and ($cusid =='')) แม้ว่าจะไม่เคยมีการบันทึก voucher มาก่อน ก็ต้องเช็คว่าจำนวนเกิน หรือไม่
		if(($sum_amt <=$res_amount) or (($numrows_conid==0) and ($cusid ==''))){
		
			// ตรวจสอบว่า CusID ดังกล่าวเป็น ประเภทไหน 0-ไม่จำกัดเขต, 1-ลูกค้าบุคคล, 2-ลูกค้านิติบุคคล, 3- พนักงานบริษัท (อย่างไรก็ดีใน กรณีนี้ จะไม่มี 0, 3 เนื่องจากเป็นการจ่ายเงินให้ลูกค้าที่เป็น 1 หรือ 2 เสมอ)
			$chk_to = pg_query("SELECT 
									\"type\"
								FROM
									\"public\".\"vthcap_ContactCus_detail\"
								WHERE
									\"CusID\" = '$cusid'
			");
			list($chk_to) = pg_fetch_array($chk_to);
			
			// ในกรณีที่หาข้อมูล CusID ไม่พบหรือหาไม่เจอ หรือไม่ได้กรอกมาแต่แรก จะต้องกำนหใด้เป็น 0 คือไม่จำกัดเขตข้อมูล หรือเป็นข้อมูลที่ไม่มีในระบบ
			if($chk_to == '') {
				$chk_to = 0;
			}
		
			// ----------------------------------------------------------------------------------------
			// เตรียมข้อมูลตัวแปรของค่าต่างๆที่จะนำไปบันทึกสร้างใบสำคัญ ตามประเภทสัญญา
			// ----------------------------------------------------------------------------------------
			// *todo เพิ่มประเภทให้รองรับสัญญาประเภทอื่นๆเพิ่มเติม
			// LOAN
			if ($vcontype == 'MG' OR $vcontype == 'LI' OR $vcontype == 'CG' OR $vcontype == 'SM' OR $vcontype == 'PL' OR 
				$vcontype == 'JV' OR $vcontype == 'PN' OR $vcontype == 'FA' OR $vcontype == 'FI' OR $vcontype == 'FL' OR
				$vcontype == 'HP' OR $vcontype == 'BH' OR $vcontype == 'SB') {
				
				// การปรับแต่งค่าในกรณีที่เป็นสัญญา LOAN
				if ($vcontype == 'MG' OR $vcontype == 'LI' OR $vcontype == 'CG' OR $vcontype == 'SM' OR $vcontype == 'PL' OR 
					$vcontype == 'JV' OR $vcontype == 'PN'){
					
					$voucherPurpose = 201; // ตั้งลูกหนี้ประเภทเงินกู้
					$voucher_note = "ให้เงินกู้ลูกค้าประเภทสัญญา $vcontype เลขที่สัญญา $contractID";
				}
				if ($vcontype == 'FA' OR $vcontype == 'FI'){
					$voucherPurpose = 205; // ตั้งลูกหนี้ประเภทแฟคตอริ่ง
					$voucher_note = "ชำระค่าซื้อสิทธิเรียกร้องให้กับประเภทสัญญา $vcontype เลขที่สัญญา $contractID";
				}
				if ($vcontype == 'FL' OR $vcontype == 'HP' OR $vcontype == 'BH' OR $vcontype == 'SB'){
					$voucherPurpose = 1; // จ่ายค่าใช้จ่ายทั่วไป บุคคลภายนอก ตามใบแจ้งหนี้ หรือใบสั่งซื้อ (ชำระทันที)
					$voucher_note = "ชำระค่าสินค้าหรือสิทธิให้กับประเภทสัญญา $vcontype เลขที่สัญญา $contractID";
				}
				
				$precord_details = "{ $voucher_note}"; // รายละเอียดรายการใบใบสำคัญที่เกิดขึ้น
				$precord_netamt = "{ $ChannelAmt}"; // จำนวนเงินส่วนที่ให้เงินกู้เฉพาะรายการนี้
				$precord_vatamt = '{0.00}'; // ให้เงินกู้ ไม่มี VAT
				$precord_whtamt = '{0.00}'; // ให้เงินกู้ ไม่มีภาษีหัก ณ ที่จ่าย
				$precord_whtref = '{}'; // ให้เงินกู้ ไม่มีภาษีหัก ณ ที่จ่าย
				$precord_acc_type = '{1, 2}'; // รายการแรก เดบิต - รายการสอง เครดิต
				$precord_acc_amt = "{ $ChannelAmt, $ChannelAmt}"; // จำนวนเงินของรายการที่เกิดขึ้น
				$precord_channel_bid = "{ $byChannel}"; // แหล่งช่องทางเงินออก ให้เป็นไปตามที่ผู้ใช้งานเลือก
				$precord_channel_bywhat = "{ $bywhat}"; // ประเภทช่องทางที่จ่ายเงินออก
				$precord_channel_payamt = "{ $ChannelAmt}"; // จำนวนเงินของรายการที่เกิดขึ้น
				
				// ----------------------------------------------------------------------------------------
				// $precord_acc_book - สมุดบัญชีที่ต้องการบันทึกแต่ละด้าน Debit (เพิ่มลูกหนี้) / Credit (ลดจำนวนเงินจากช่องทางที่จ่ายออก)
				// ----------------------------------------------------------------------------------------
				// หาข้อมูลว่าลูกหนี้ประเภทนั้นมีรหัสบัญชีที่จะบันทึกในด้าน Debit อย่างไร
				$precord_acc_book_debit = pg_query("SELECT \"thcap_get_accid_from_channel\"(-1, '$vcontype')");
				list($precord_acc_book_debit) = pg_fetch_array($precord_acc_book_debit);
				// หาข้อมูลว่าที่มาของเงินที่จ่ายออกมาจากช่องทางไหน
				$precord_acc_book_credit = pg_query("SELECT \"thcap_get_accid_from_channel\"($byChannel)");
				list($precord_acc_book_credit) = pg_fetch_array($precord_acc_book_credit);
				// สร้าง array $precord_acc_book
				$precord_acc_book = "{ $precord_acc_book_debit, $precord_acc_book_credit}";
				// ----------------------------------------------------------------------------------------
				
				// ----------------------------------------------------------------------------------------
				// $precord_channel_payerchqno_or_payeebankno - เลขที่เช็คจ่าย หรือ เลขที่บัญชีธนาคารผู้รับโอนปลายทาง
				// $precord_channel_payeebankname - ธนาคารผู้รับโอนปลายทาง หรือ ผู้ที่เช็คสั่งจ่าย
				// ----------------------------------------------------------------------------------------
				if ($bywhat == 0) {
					$precord_channel_payerchqno_or_payeebankno = '{}';
					$precord_channel_payeebankname = '{}';
				}
				else if ($bywhat == 1 || $bywhat == 2) {
					$precord_channel_payerchqno_or_payeebankno = "{ $payerchqno_or_payeebankno}";
					$precord_channel_payeebankname = "{ $payeebankname}";
				}
				else {
					$status++;
					$error_message .= 'ข้อมูลประเภทช่องทางการจ่าย (bywhat) ไม่รู้จักประเภทดังกล่าว |';
				}
				// ----------------------------------------------------------------------------------------
			}else{
				$status++;
			}
			
			// ----------------------------------------------------------------------------------------
			// gen Payment voucher
			// ----------------------------------------------------------------------------------------
			// ยังไม่เรียบร้อย โดย function นี้จะสร้าง voucher ขึ้นมาเพื่อรออนุมัติอีกครั้ง โดยสามารถดูตัวอย่างการทำงานจากไฟล์นี้ได้
			$qry = "
				SELECT \"thcap_process_voucherCreate_payment\"(
					'$voucherDate'::date,--pvoucherdate (date) : วันทีทำรายการ
					NULL,--pvatdate (date) : วันที่รายการใบกำกับภาษี (ถ้าไม่มี VAT จะเป็นค่าว่า / ถ้ามี VAT ชำระวันนี้วันที่จะตรงกันกับ voucherDate / ถ้ามี VAT แต่ชำระไปแล้ววันที่จะก่อนหน้า voucherDate)
					'$doerID'::varchar,--puserid (varchar) : รหัสผู้ทำรายการ
					'$cusid'::varchar,--ppayid (varchar) : รหัาลูกค้าในระบบ
					'$chk_to'::smallint,--ppayidwhat (smallint) : ประเภทของข้อมูลลูกค้าที่ส่งมาจาก GUI
					$fullname::varchar,--ppayfull (varchar) : ชื่อลูกค้าเต็ม
					$voucherPurpose::integer,--pvoucherpurpose (integer) : วัตถุประสงค์ในการทำรายการ voucher
					'$voucher_note'::varchar,--premark (varchar) : หมายเหตุรายการอนุมัติ
					'$precord_details'::varchar[],--precord_details (character varying[]) : ชื่อรายการสินค้าหรือบริการ
					'$precord_netamt'::varchar[],--precord_netamt (character varying[]) : จำนวนเงินค่าสินค้าหรือบริการ
					'$precord_vatamt'::varchar[],--precord_vatamt (character varying[]) : จำนวนภาษีมูลค่าเพิ่ม
					'$precord_whtamt'::varchar[],--precord_whtamt (character varying[]) : จำนวนภาษีหัก ณ ที่จ่าย
					'$precord_whtref'::varchar[],--precord_whtref (character varying[]) : เลขที่อ้างอิงใบหัก ณ ที่จ่าย
					'$precord_acc_type'::varchar[],--precord_acc_type (character varying[]) : ประเภทของรายการที่จะบันทึกบัญชี 1-Debit 2-Credit
					'$precord_acc_book'::varchar[],--precord_acc_book (character varying[]) : accBookSerial รายการที่จะบันทึกบัญชี
					'$precord_acc_amt'::varchar[],--precord_acc_amt (character varying[]) : จำนวนเงินของรายการที่จะบันทุกบัญชี
					'$precord_channel_bid'::varchar[],--precord_channel_bid (character varying[]) : แหล่งที่คืนเงินออก เช่น จากเงินสดช่องทางไหน หรือบัญชีธนาคารใดๆ (BankInt.BID)
					'$precord_channel_bywhat'::varchar[],--precord_channel_bywhat (character varying[]) : ช่องทางการจ่ายออกตามที่ผู้ใช้เลือก 0-ไม่ใช่เงินโอนหรือเช็ค 1-เงินโอน 2-เช็ค
					'$precord_channel_payerchqno_or_payeebankno'::varchar[],--precord_channel_payerchqno_or_payeebankno (character varying[]) : เลขที่เช็คจ่าย หรือ เลขที่บัญชีธนาคารผู้รับโอนปลายทาง
					'$precord_channel_payeebankname'::varchar[],--precord_channel_payeebankname (character varying[]) : ธนาคารผู้รับโอนปลายทาง หรือ ผู้ที่เช็คสั่งจ่าย
					'$precord_channel_payamt'::varchar[],--precord_channel_payamt (character varying[]) : จำนวนเงินที่ได้สั่งจ่ายผ่านช่องทางนั้นๆ
					'NODATA',--pdocumentref เลขอ้างอิงเอกสารที่ประกอบใบสำคัญ
					'$contractID'::varchar --pcontractid (varchar) [DEFAULT NULL] : เลขที่สัญญา หาก AP นี้เป็นการทำเพื่อตั้งลูกหนี้
				)
			";
			if($res_qry=pg_query($qry)){ 
				$prevoucherdetailsid = pg_fetch_result($res_qry,0);				
			}else{
				$status++;
			}
			
			// ----------------------------------------------------------------------------------------
			// Approve voucher ที่สร้างขึ้นมา
			// ----------------------------------------------------------------------------------------
			$qry = "select \"thcap_process_voucherApprove\"($prevoucherdetailsid,'$user_id',2,'ทำรายการผ่านเมนู (THCAP) อนุมัติชำระเงินให้ลูกหนี้-เจ้าหนี้ต้นทุนสินค้า')";
			if($res_qry=pg_query($qry)){
				$voucherid = pg_fetch_result($res_qry,0);
			}else{
				$status++;
			}
			
			// ----------------------------------------------------------------------------------------
			// ทำการ Tag voucher ที่ Approved แล้วกับ สัญญา
			// ----------------------------------------------------------------------------------------
			$qry = "INSERT INTO thcap_temp_voucher_tag(\"voucherID\", \"contractID\") VALUES ( '$voucherid', '$contractID')";
			if($res_qry=pg_query($qry)){
			}else{
				$status++;
			}
		}else{
				$ins_error = "จำนวนเงินที่จ่ายแล้ว+จำนวนเงินที่รอการอนุมัติ  มากกว่า จำนวนเงินลงทุน<br>จำนวนเงินที่จ่ายแล้ว+จำนวนเงินที่รอการอนุมัติ = $sum_amt และจำนวนเงินลงทุน = $res_amount";
				$status++;
		}
	}
	// =======================================================================================
	// กรณีไม่อนุมัติ
	// =======================================================================================
	else{
		$voucherid = null;
	}
	
	// =======================================================================================
	// อัพเดทข้อมูลผลการอนุมัติและรหัสรายการ voucherID ที่ได้
	// =======================================================================================
	$voucherid = checknull($voucherid);	
	$update_detail="UPDATE \"thcap_temp_payment_debtor_creditors_cost\" SET 
				\"appvID\"='$user_id',
				\"appvStamp\"='$app_datetime',
				\"appvStatus\"='$statusapp',
				\"voucherID\"=$voucherid,
				\"appvRemark\"=$str_note  where \"auto_id\"='$id' and \"appvStatus\"='9'
				RETURNING  \"auto_id\"";	
	$result_temp = pg_query($update_detail);
	if($result_temp){
		$abh_autoid_temp = pg_fetch_result($result_temp,0);
	}else{
		$status++;
	}
	if($abh_autoid_temp ==''){$status++;}
}

if($numrows>0){
	if($status == 0){
		pg_query("COMMIT");
		echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></font></div>";		
	}else{
		pg_query("ROLLBACK");		
		echo "<center>".$ins_error."<br>";
		echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง หรือติดต่อฝ่ายเทคโนโลยีและสารสนเทศ ต่อ 5400</b></font></div>";		
	}
}
else{
	pg_query("ROLLBACK");
	echo "<center>".$ins_error."<br>";
	echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>รายการนี้อาจถูกทำการอนุมัติ/ไม่อนุมัติแล้ว กรุณาตรวจสอบอีกครั้ง</b></font></div>";	
}
?>
 </td>
 </tr>
</table>
</body>
</html>


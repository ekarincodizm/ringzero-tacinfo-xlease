<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");
include('class.upload.php');
$id_user = $_SESSION["av_iduser"];
$revTranID=pg_escape_string($_POST["revTranID"]); 
$tranActionID=pg_escape_string($_POST["tranActionID"]);
$result=pg_escape_string($_POST["result"]);
$remark=pg_escape_string($_POST["remark"]);
$app=pg_escape_string($_POST["app"]);
$curdate = nowDateTime();
$datelog=$curdate;
$BID=pg_escape_string($_POST["BID"]);
$dateRevStamp=pg_escape_string($_POST["dateRevStamp"]);
$nowdateTime = date("YmdHis");

$pcashdate=pg_escape_string($_POST["pcashdate"]);
$pcashamt=pg_escape_string($_POST["pcashamt"]);

$revChqID=pg_escape_string($_POST["revChqID"]); //รหัสเช็ค
$remark_not_chk_null = $remark; // remark ที่ไม่ checknull เพื่อป้องการการเพิ่ม  double quote ลงไป ""

// ===================================================================================================================================
// ตัวแปรสำหรับ "ทำรายการรับเงินที่ไม่ใช่สินค้า หรือบริการหลัก"
// ===================================================================================================================================
$revtranstatussubtype_id_for_otherreceive = pg_escape_string($_POST["revtranstatus_ref"]); // ประเภทรายการ
$revtranstatussubtype_ref1 = pg_escape_string($_POST["revtranstatussubtype_ref1"]); // ข้อมูลอ้างอิงสำหรับทำรายการรับเงินอื่นๆที่ไม่ใช่สินค้าหรือบริการหลัก 1 (เลขที่รายการ / เลขที่สัญญา)
$revtranstatussubtype_ref2 = pg_escape_string($_POST["revtranstatussubtype_ref2"]); // ข้อมูลอ้างอิงสำหรับทำรายการรับเงินอื่นๆที่ไม่ใช่สินค้าหรือบริการหลัก 2 (จะเกี่ยวกับชื่อผู้จ่าย)
$revtranstatussubtype_ref3 = pg_escape_string($_POST["revtranstatussubtype_ref3"]); // ข้อมูลอ้างอิงสำหรับทำรายการรับเงินอื่นๆที่ไม่ใช่สินค้าหรือบริการหลัก 3  
$revtranstatussubtype_ref4 = pg_escape_string($_POST["revtranstatussubtype_ref4"]); // ข้อมูลอ้างอิงสำหรับทำรายการรับเงินอื่นๆที่ไม่ใช่สินค้าหรือบริการหลัก 4 
$revtranstatussubtype_ref5 = pg_escape_string($_POST["revtranstatussubtype_ref5"]); // ข้อมูลอ้างอิงสำหรับทำรายการรับเงินอื่นๆที่ไม่ใช่สินค้าหรือบริการหลัก 5 (ถ้ามีการกำหนดให้ใช้เลขที่สมุดบัญชีสำหรับบันทึกบัญชีในขาเครดิต)
$revtranstatussubtype_ref6 = pg_escape_string($_POST["revtranstatussubtype_ref6"]); // ข้อมูลอ้างอิงสำหรับทำรายการรับเงินอื่นๆที่ไม่ใช่สินค้าหรือบริการหลัก 6
$revtranstatussubtype_ref7 = pg_escape_string($_POST["revtranstatussubtype_ref7"]); // ข้อมูลอ้างอิงสำหรับทำรายการรับเงินอื่นๆที่ไม่ใช่สินค้าหรือบริการหลัก 7 (จำนวนเงินภาษีมูลค่าเพิ่มของรายการนี้)
$revtranstatussubtype_ref8 = pg_escape_string($_POST["revtranstatussubtype_ref8"]); // ข้อมูลอ้างอิงสำหรับทำรายการรับเงินอื่นๆที่ไม่ใช่สินค้าหรือบริการหลัก 8 (เลขที่อ้างอิงใบภาษีหัก ณ ที่จ่าย)
$revtranstatussubtype_ref9 = pg_escape_string($_POST["revtranstatussubtype_ref9"]); // ข้อมูลอ้างอิงสำหรับทำรายการรับเงินอื่นๆที่ไม่ใช่สินค้าหรือบริการหลัก 9 (จำนวนเงินภาษีหัก ณ ที่จ่ายของรายการนี้)

if($revtranstatussubtype_ref1 == '' || $revtranstatussubtype_ref1 ==  NULL) $revtranstatussubtype_ref1 = 'NULL';
if($revtranstatussubtype_ref2 == '' || $revtranstatussubtype_ref2 ==  NULL) $revtranstatussubtype_ref2 = 'NULL';
if($revtranstatussubtype_ref3 == '' || $revtranstatussubtype_ref3 ==  NULL) $revtranstatussubtype_ref3 = 'NULL';
if($revtranstatussubtype_ref4 == '' || $revtranstatussubtype_ref4 ==  NULL) $revtranstatussubtype_ref4 = 'NULL';
if($revtranstatussubtype_ref5 == '' || $revtranstatussubtype_ref5 ==  NULL) $revtranstatussubtype_ref5 = 'NULL';
if($revtranstatussubtype_ref6 == '' || $revtranstatussubtype_ref6 ==  NULL) $revtranstatussubtype_ref6 = 'NULL';
if($revtranstatussubtype_ref7 == '' || $revtranstatussubtype_ref7 ==  NULL) $revtranstatussubtype_ref7 = 'NULL';
if($revtranstatussubtype_ref8 == '' || $revtranstatussubtype_ref8 ==  NULL) $revtranstatussubtype_ref8 = 'NULL';
if($revtranstatussubtype_ref9 == '' || $revtranstatussubtype_ref9 ==  NULL) $revtranstatussubtype_ref9 = 'NULL';

// หาเลขที่ chq มา
$prevtranstatussubtype_paid_chq = $revChqID;
$prevtranstatussubtype_paid_chq = checknull($prevtranstatussubtype_paid_chq);

// ===================================================================================================================================
// ตัวแปรสำหรับ "ทำรายการชำระคืนให้กับบุคคลภายนอก"
// ===================================================================================================================================
$pvoucherdate_refund_anonymous = pg_escape_string($_POST["dcNoteDate"]); // วันที่รายการมีผล
$preturnby_what_refund_anonymous = pg_escape_string($_POST["proviso_return"]); // ประเภทการคืน  (0-ไม่ใช่เงินโอนหรือเช็ค 1-เงินโอน 2-เช็ค)
$returnTranToAccNo = pg_escape_string($_POST["returnTranToAccNo"]); // เลขที่บัญชีปลายทาง
$returnChqNo = pg_escape_string($_POST["returnChqNo"]); // เลขที่เช็ค
$returnChqDate = pg_escape_string($_POST["returnChqDate"]); // วันที่บนเช็ค
$preturnby_payerchqno_or_payeebankno_refund_anonymous = "$returnTranToAccNo"."$returnChqNo"; // เลขที่เช็คจ่าย หรือ เลขที่บัญชีธนาคารผู้รับโอนปลายทาง (เนื่องจากมีได้อย่างใดอย่างหนึ่งเท่านั้น จึงใช้วิธีการต่อ string เพื่อให้ได้อย่างที่เหลือ)
$preturnby_payeebankname_refund_anonymous = pg_escape_string($_POST["returnTranToBank"]); // ธนาคารผู้รับโอนปลายทาง หรือ ผู้ที่เช็คสั่งจ่าย
$preturnby_byChannel = pg_escape_string($_POST["byChannel"]); // ช่องทางการจ่ายเงินคืน
$premark_refund_anonymous = pg_escape_string($_POST["remark"]); // หมายเหตุรายการ

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<script language="JavaScript" type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script> 
</head>

<body style="background-color:#ffffff; margin-top:0px;">

<table width="100%" border="0" align="center">
<tr >
<td align="center" valign="middle" height="200">
<?php
pg_query("BEGIN WORK");
$status = 0;

//ตรวจสอบว่าได้อนุมัติไปก่อนหน้านี้หรือไม่
if($app==1){ //กรณีบัญชีอนุมัติ
	//ตรวจสอบว่ายังไม่มีการอนุมัติของฝ่ายบัญชี 
	$qrycheck=pg_query("select * from finance.\"V_thcap_receive_transfer_tsfAppv\" where \"appvXID\" is null and \"bankRevAccID\"='$BID' and date(\"bankRevStamp\")='$dateRevStamp'");
	$num_chk=pg_num_rows($qrycheck);
}else{ //กรณีการเงินอนุมัติ
	//ตรวจสอบว่ายังไม่มีการอนุมัติของฝ่ายการเงิน และสถานะัยังเป็น 9 อยู่(คืนรออนุมัิติ)
	$qrycheck=pg_query("select * from finance.\"V_thcap_receive_transfer_tsfAppv\" where \"appvYID\" is null and \"revTranStatus\"='9'
	and \"revTranID\"='$revTranID'");
	$num_chk=pg_num_rows($qrycheck);
}

if($num_chk == 0 AND $revTranID != 'bid_1'){ //แสดงว่าอนุมัติไปก่อนหน้านี้แล้ว
	echo "<font size=4><b>รายการนี้ได้รับอนุมัติไปก่อนหน้านี้แล้วกรุณาตรวจสอบ</b></font><br><br>";
	echo "<input type=\"submit\" value=\"  ปิด  \" onclick=\"javascript:RefreshMe();\" />";

}else{ //กรณียังไม่ได้อนุมัติก่อนหน้านี้ให้สามารถอนุมัติได้
	if($app==1){
		$chk=$_POST["chk"]; //รายการที่ยกเลิก
		$noresult=$_POST["noresult"]; //เหตุผลที่ยกเลิก
		
		$app_user="appvXID";
		$app_time="appvXStamp";
		$app_remark="appvXRemask";
		$app_status="appvXStatus";

		while($resup=pg_fetch_array($qrycheck)){
			//ตรวจสอบข้อมูลว่าค่าที่ไม่อนุมัติคือค่าไหนแล้วนำเหตุผลมา update
			for($pp=0;$pp<sizeof($chk);$pp++){
				if($chk[$pp]==$resup[revTranID]){
					$remark2=$noresult[$pp];
					$statusup=4;
					break;
				}
			}
			if($statusup!=4){
				$remark2=$remark;
				$statusup=0;
			}
			
			$remark2=checknull($remark2);
			
			//ตรวจสอบว่ารหัสเงินโอนนั้นเป็น billpayment หรือไม่ ถ้าใช่ให้ update ตรวจสอบการเงินเลย
			$insacc="";
			$qrychkbill=pg_query("select * from \"finance\".\"thcap_receive_transfer\" where \"revTranID\"='$resup[revTranID]' and \"cnID\"='BILL'");
			$numbill=pg_num_rows($qrychkbill);
			if($numbill>0){ //แสดงว่าเป็น billpayment
				//ตรวจสอบว่ามีเลขที่สัญญาหรือยัง ถ้ายังไม่ต้อง update ส่วนนี้ ให้การเงินตรวจสอบก่อน
				$resbill = pg_fetch_array($qrychkbill);
				$contractID = $resbill["contractID"]; // เลขที่สัญญาที่ผูกไว้
				$amt=$resbill["bankRevAmt"];
				$bankRevRef1 = $resbill["bankRevRef1"];
				
				if($contractID == "") // ถ้ายังไม่ได้ผูกเลขที่สัญญาไว้
				{
					$REF1 = $bankRevRef1;
					$REF1_checknull = checknull($REF1);
					
					$qryinv=pg_query("SELECT ta_array1d_get(thcap_decode_invoice_ref($REF1_checknull, null),0) as \"contractID\"");
					list($REF1_decode) = pg_fetch_array($qryinv);
					
					if($REF1_decode != "")
					{
						// ตรวจสอบว่ามีเลขที่สัญญาในระบบหรือไม่
						$qry_checkContract = pg_query("select \"contractID\" from \"thcap_contract\" where \"contractID\" = '$REF1_decode'");
						$row_checkContract = pg_num_rows($qry_checkContract);
						
						if($row_checkContract > 0) // ถ้ามีสัญญาอยู่จริง
						{
							$contractID = $REF1_decode;
						}
						else // ถ้าไม่มีเลขที่สัญญาดังกล่าวในระบบ
						{
							$contractID = "";
						}
					}
				}
				
				if($contractID!="")
				{
					$contractID_checknull = checknull($contractID);
					
					$insacc=",\"appvYID\"='000',\"appvYStamp\"='$curdate',\"appvYStatus\"='1'";
					
					$upd = "
							UPDATE
								\"finance\".\"thcap_receive_transfer\"
							SET
								\"revTranStatus\" = '1',
								\"ststariff\" = '0.00',
								\"balanceAmt\" = '$amt',
								\"contractID\" = $contractID_checknull
							WHERE
								\"revTranID\" = '$resup[revTranID]'
							";
					if($resins=pg_query($upd)){
					}else{
						$status++;
					}	
				}
			}
			
			//update ข้อมูลเบื้่องต้นว่าใครเป็นผู้อนุมัติ(บัญชี)		
			$upd="update \"finance\".\"thcap_receive_transfer_action\" set \"$app_user\"='$id_user',
						\"$app_time\"='$curdate',
						\"$app_remark\"=$remark2,
						\"$app_status\"='$result' $insacc where \"revTranID\"='$resup[revTranID]'";
												
			if($resins=pg_query($upd)){
			}else{
				$status++;
			}
			
			//หาข้อมูลเพื่อนำมาเก็บใน log
			$qrydata=pg_query("select * from finance.\"V_thcap_receive_transfer_tsfAppv\" where \"revTranID\"='$resup[revTranID]'");
			if($resdata=pg_fetch_array($qrydata)){
				$BAccount=$resdata["BAccount"];
				$bankRevBranch=$resdata["bankRevBranch"];
				$bankRevAmt=$resdata["bankRevAmt"];
				$bankRevStamp=$resdata["bankRevStamp"];
			}
			
			//กรณีไม่อนุมัติ
			if($result==0){
				//update status ที่ไม่อนุมัติและต้องแก้ไขเป็น 4 ส่วนรายการอื่นๆ ให้เป็น 0
				$upd2="update \"finance\".\"thcap_receive_transfer\" set \"revTranStatus\"='$statusup' where \"revTranID\"='$resup[revTranID]'";
				if($res_upd2=pg_query($upd2)){
				}else{
					$status++;
				}	

				//LOG
				if($statusup==4){ //เก็บเฉพาะรายการที่ต้องแก้ไขเท่านั้น เพราะถือว่าไม่อนุมัติ ที่เหลืออนุมัติ
					if($sqlaction = pg_query("INSERT INTO finance.thcap_receive_transfer_log(detail,\"revTranID\", id_user, \"dateStamp\",\"BAccount\", 
					   \"bankRevBranch\", \"bankRevAmt\", \"bankRevStamp\", remark) 
					VALUES ('ไม่อนุมัติรายการ (บัญชี)','$resup[revTranID]','$id_user', '$datelog','$BAccount',
					'$bankRevBranch','$bankRevAmt','$bankRevStamp',$remark2)")); else $status++;
				}
				//LOG---
			}
			
			if($result==1){ //กรณีอนุมัติ
				//LOG
				if($sqlaction = pg_query("INSERT INTO finance.thcap_receive_transfer_log(detail,\"revTranID\", id_user, \"dateStamp\",\"BAccount\", 
				   \"bankRevBranch\", \"bankRevAmt\", \"bankRevStamp\", remark) 
				VALUES ('อนุมัติรายการ (บัญชี)','$resup[revTranID]','$id_user', '$datelog','$BAccount',
				'$bankRevBranch','$bankRevAmt','$bankRevStamp',$remark2)")); else $status++;
				
				//เก็บ Log ว่าอนุมัติการเงินแล้ว
				if($numbill>0){ //กรณีเป็น billpayment
					if($contractID!=""){
						if($sqlaction = pg_query("INSERT INTO finance.thcap_receive_transfer_log(detail,\"revTranID\", id_user, \"dateStamp\",\"BAccount\", 
						  \"bankRevBranch\", \"bankRevAmt\", \"bankRevStamp\") 
						VALUES ('@$contractID','$resup[revTranID]','000', '$datelog','$BAccount',
						'$bankRevBranch','$bankRevAmt','$bankRevStamp')")); else $status++;
					}
				}
				//LOG---
			}
			
			//ถ้ามีรายการที่เคยไม่อนุมัติมาแล้วให้ไป update ข้อมูลว่าสิ้นสุดการอนุมัติครั้งนี้แล้ว
			$updatenoapp="update finance.thcap_receive_transfer_noapptemp set \"statusEnd\"='0' where \"revTranID\"='$resup[revTranID]' and \"statusEnd\"='1'";
			if($resupdatenoapp=pg_query($updatenoapp)){
			}else{
				$status++;
			}	
			$remark2="";
			$statusup="";
		}
	}else{ 
		// ========================================================================================================
		//	กรณีการเงินอนุมัติ (อนุมัติยืนยันรายการเงินโอน ทางการเงิน)
		// ========================================================================================================
		//ข้อมูลเพิ่มเติมกรณีการเงินกรอกเพิ่มเติม
		$errorstr="";
		if($result==3){
			$conID=pg_escape_string($_POST["contractID2"]);
			
		}else{
			$conID=pg_escape_string($_POST["contractID"]);
		}
		
		$contractID=checknull($conID); //เลขที่สัญญา
		$name=pg_escape_string($_POST["name"]); //รหัสของลูกค้า
		$revChqID2=checknull($revChqID);
		if($result==3){
			//ตรวจสอบเป็นค่าว่างหรือไม่
			if($revChqID2!="")
			{	//ตรวจสอบว่ามีรหัสเช็คในระบบจริง 
				$sqlrevChqID = pg_query("SELECT \"revChqID\" FROM finance.\"V_thcap_receive_cheque_chqManage\"
						where \"revChqID\"=$revChqID2");
				$rowrevChqID = pg_num_rows($sqlrevChqID);
				if($rowrevChqID>0){}
				else{
					//ไม่มีข้อมูลในระบบจริง 
					$errorstr="noactivesystem";
				}
			}
		}	
		$tariff=pg_escape_string($_POST["tariff"]); //ค่าธรรมเนียมเป็นจำนวนเงิน
			
		if($tariff==""){
			$tariff=0;
			$billfile="null";
		}else{ //กรณีเลือกมีค่าธรรมเนียม
			if($result==3){ //กรณีเป็นรายการเช็ค
				$dir_dest = (isset($_GET['dir']) ? $_GET['dir'] : './upload/addbillchq');
				$dir_pics = (isset($_GET['pics']) ? $_GET['pics'] : $dir_dest);
											
				$files = array();
				foreach ($_FILES["my_field"] as $k => $l) {
					foreach ($l as $i => $v) {
						if (!array_key_exists($i, $files))
							$files[$i] = array();
							$files[$i][$k] = $v;
					}
				}
				$i=1;								
				foreach ($files as $file) {
					$handle = new Upload($file);
											   
					if($handle->uploaded) {
						$handle->Process($dir_dest);    							 
													
						if ($handle->processed) {
							$pathfile=$handle->file_dst_name;
						
							$Board_oldfile = $pathfile;			
							$Board_newfile = md5_file("./upload/addbillchq/$pathfile", FALSE);		
															
							$Board_cuttext = split("\.",$pathfile);
							$Board_nubtext = count($Board_cuttext);
							$Board_newfile = "$Board_newfile.".$Board_cuttext[$Board_nubtext-1];
															
							$Board_newfile = $nowdateTime."_".$Board_newfile; // ใส่วันเวลาไว้หน้าไฟล์
															
							$Boardfile = "'$Board_newfile'"; // ชื่อไฟล์ที่จะเอาไปเก็บใน database
							$billfile=$Boardfile;							
							$flgRename = rename("./upload/addbillchq/$Board_oldfile", "./upload/addbillchq/$Board_newfile");
							if($flgRename)
							{
								//echo "บันทึกสำเร็จ";
							}
							else
							{
								echo "ไม่สามารถเปลี่ยนชื่อบางไฟล์ได้";
								$status++;
							}				
						}else{
							echo '<fieldset>';
							echo '  <legend>file not uploaded to the wanted location</legend>';
							echo '  Error: ' . $handle->error . '';
							echo '</fieldset>';
							$status++;
						}
					}
					$i++;
				}
			}
		}
		
		$result3=$result; //กรณีเป็นเช็ค
		
		if($result==3){
			$result=1;
		}
				
		if($name=="999"){
			$name=$_POST["cuseach"];
		}
		$dateContact=$_POST["dateContact"]; //วันที่ติดต่อ
		$hh=$_POST["hh"];
		$mm=$_POST["mm"];
		
		if($result==1 and $result3!=3){
			//เชื่อมข้อความในส่วนของ remark
			$remark="ติดตามการโอน
			------------------
			ผู้ติดต่ีอ : $name
			รายละเอียด : $remark";
				
			$dateContact="'".$dateContact." ".$hh.":".$mm."'";
		}else{
			$dateContact="null";
		}
		
		$app_user="appvYID";
		$app_time="appvYStamp";
		$app_remark="appvYRemask";
		$app_status="appvYStatus";
		$remark=checknull($remark);
		
		if($billfile==""){
			$billfile="null";
		}
		
		// Process Query ในส่วนนี้จะทำเฉพาะที่เป็นเงินโอนจริงๆเท่านั้น
		if($revTranID != 'bid_1'){
		
			//update ข้อมูลเบื้่องต้นว่าใครเป็นผู้อนุมัติ(การเงิน)
			$upd="update \"finance\".\"thcap_receive_transfer_action\" set \"$app_user\"='$id_user',
						\"$app_time\"='$curdate',
						\"$app_remark\"=$remark,
						\"$app_status\"='$result' where \"revTranID\"='$revTranID' and \"tranActionType\"='I' and \"tranActionID\"='$tranActionID'";
			if($resins=pg_query($upd)){
			}else{
				$status++;
			}
			
			//นำจำนวนเงินที่สามารถใช้ได้มา update ด้วย เืพื่อเก็บว่าเงินคงเหลือที่ใช้ได้เท่าไหร่
			$qrybalance=pg_query("select \"bankRevAmt\" from finance.thcap_receive_transfer where \"revTranID\"='$revTranID'");
			list($amt)=pg_fetch_array($qrybalance);
			
			//หา chqKeeperID
			$qry_chqKeeperID = pg_query("select \"chqKeeperID\" from finance.thcap_receive_cheque_keeper where \"revChqID\"=$revChqID2 and \"bankRevResult\" in ('1','2') ");
			$chqKeeperID = pg_fetch_result($qry_chqKeeperID,0);
			$chqKeeperID2 = checknull($chqKeeperID); //ใส่ฟังก์ชัน check null ในกรณีเลือก ผลการตรวจเป็นรายการที่เป็นเงินโอน
		
			$upd="update \"finance\".\"thcap_receive_transfer\" set \"revTranStatus\"='$result',\"contractID\"=$contractID,\"dateContact\"=$dateContact,
			\"revChqID\"=$revChqID2,\"ststariff\"='$tariff',\"balanceAmt\"='$amt',picslip=$billfile,\"chqKeeperID\"=$chqKeeperID2 where \"revTranID\"='$revTranID'";
			if($res_upd=pg_query($upd)){
			}else{
				$status++;
			}
			
			//หาข้อมูลเพื่อนำมาเก็บใน log
			$qrydata=pg_query("select * from finance.\"V_thcap_receive_transfer_tsfAppv\" where \"revTranID\"='$revTranID'");
			if($resdata=pg_fetch_array($qrydata)){
				$BAccount=$resdata["BAccount"];
				$bankRevBranch=$resdata["bankRevBranch"];
				$bankRevAmt=$resdata["bankRevAmt"];
				$bankRevStamp=$resdata["bankRevStamp"];
			}		
		}


		
		if($result==2){ 
			// ========================================================================================================
			//กรณีเงินที่ไม่ใช่ชำระค่าสินค้าหรือบริการ
			// ========================================================================================================
			
			// สร้างตัวแปรสำหรับส่งค่า ref เป็นแบบ array fix ที่
			$prevtranstatussubtype_ref_array = "
				{	
					{1,$revtranstatussubtype_ref1},
					{2,$revtranstatussubtype_ref2},
					{3,$revtranstatussubtype_ref3},
					{4,$revtranstatussubtype_ref4},
					{5,$revtranstatussubtype_ref5},
					{6,$revtranstatussubtype_ref6},
					{7,$revtranstatussubtype_ref7},
					{8,$revtranstatussubtype_ref8},
					{9,$revtranstatussubtype_ref9}
				}
			";
			
			// ตรวจสอบว่าเป็นการรับเงินสด หรือไม่ ถ้าไม่ก็ทำตาม process เดิมปกติ
			if($revTranID != 'bid_1'){
				// บันทึก LOG
				if($sqlaction = pg_query("INSERT INTO finance.thcap_receive_transfer_log(detail,\"revTranID\", id_user, \"dateStamp\",\"BAccount\", 
					  \"bankRevBranch\", \"bankRevAmt\", \"bankRevStamp\", remark) 
				VALUES ('เงินที่ไม่ใช่ชำระค่าสินค้าหรือบริการ','$revTranID','$id_user', '$datelog','$BAccount',
				'$bankRevBranch','$bankRevAmt','$bankRevStamp',$remark)")); else $status++;
				
				// เตรียมทำรายการขอออก Receive Voucher สำหรับการรับเงินค่าอื่นๆ
				$qry_vprevoucherdetailsid_rec_other = pg_query("
					SELECT \"thcap_process_voucherCreate_revtran_other\"(
							'$revTranID', -- prevtranid (varchar) : รหัส finance.thcap_receive_transfer.\"revTranID\" ที่ต้องการนำมาใช้
							'$id_user', -- puserid (varchar) : รหัสผู้ทำรายการ - อนุมัติรายการ
							$revtranstatussubtype_id_for_otherreceive, -- prevtranstatussubtype_id (integer) : รหัสประเภทรายการที่รับค่าอื่นๆ
							'$prevtranstatussubtype_ref_array'::varchar[], -- prevtranstatussubtype_ref (varchar[]) : ข้อมูล ref สำหรับใช้ในการบันทึกรายการ
							$prevtranstatussubtype_paid_chq -- prevtranstatussubtype_paid_chq : เลขที่ chq หากมีการจ่าย
					)
				");
				if($qry_vprevoucherdetailsid_rec_other){
					list($vprevoucherdetailsid_rec_other) = pg_fetch_array($qry_vprevoucherdetailsid_rec_other);
				}else{
					$status++;
				}
			
				// อนุมัติรายการ Receive voucher อัตโนมัติ
				// todo ยกเลิกในส่วนนี้ออกไป เนื่องจากต้องการให้ไปอนุมัติเอง :: ถ้าต้องการใช้งานเหมือนเดิม สามารถเปิดออกได้ :: อ้างอิงการปิดความสามารถด้วยเลขงาน #7420
				/*$qry_vvoucherid_rec_other = pg_query("
					SELECT \"thcap_process_voucherApprove\"(
							'$vprevoucherdetailsid_rec_other'::bigint, -- bigint : pprevoucherdetailsid : รหัสรายการที่จะอนุมัติ หรือไม่อนุมัติ
							'$id_user', -- varchar : pappvid : รหัสผู้อนุมัติรายการ
							1, -- integer : pstatus : สถานะการอนุมัติ (0-ไม่อนุมัติ, 1-อนุมัติ Auto, 2-อนุมัติ manual)
							'$remark_not_chk_null' -- varchar : pappvremark : ความเห็นผู้อนัมุติ
					)
				");
				if($qry_vvoucherid_rec_other){
					list($vprevoucherid_rec_other) = pg_fetch_array($qry_vvoucherid_rec_other);
					echo "ทำรายการอนุมัติใบสำคัญจ่ายเลขที่ $vprevoucherid_rec_other แล้ว";
				}else{
					$status++;
				}*/
				
				//update สถานะ chq ด้วยว่ามีการใช้เช็ค
				$upchq="UPDATE finance.thcap_receive_cheque SET \"revChqStatus\"=1 WHERE \"revChqID\"='$prevtranstatussubtype_paid_chq'";
				if($resupchq=pg_query($upchq)){
				}else{
					$status++;
				}
				
			}else{ // กรณีที่รับเป็นเงินสด
			
				// เตรียมทำรายการขอออก Receive Voucher สำหรับการรับเงินค่าอื่นๆ
				$qry_vprevoucherdetailsid_rec_other = pg_query("
					SELECT \"thcap_process_voucherCreate_revcash_other\"(
							'$pcashdate'::date, -- pcashdate (date) : วันที่รับเงินสด
							$pcashamt::numeric(15,2), -- pcashamt (numeric(15,2)) : จำนวนเงินที่รับเงินสด
							'$id_user', -- puserid (varchar) : รหัสผู้ทำรายการ - อนุมัติรายการ
							$revtranstatussubtype_id_for_otherreceive, -- prevtranstatussubtype_id (integer) : รหัสประเภทรายการที่รับค่าอื่นๆ
							'$prevtranstatussubtype_ref_array'::varchar[], -- prevtranstatussubtype_ref (varchar[]) : ข้อมูล ref สำหรับใช้ในการบันทึกรายการ
							$prevtranstatussubtype_paid_chq -- prevtranstatussubtype_paid_chq : เลขที่ chq หากมีการจ่าย
					)
				");
				if($qry_vprevoucherdetailsid_rec_other){
					list($vprevoucherdetailsid_rec_other) = pg_fetch_array($qry_vprevoucherdetailsid_rec_other);
				}else{
					$status++;
				}
			}

			
		}else if($result==8){
			// ========================================================================================================
			// ส่วนงานคืนเงินให้บุคคลภายนอก
			// ========================================================================================================
			// บันทึก log ในระบบเงินโอนรับ -------------------------------------------------------------------------------------------------------------------------------------------------
			if($sqlaction = pg_query("INSERT INTO finance.thcap_receive_transfer_log(detail,\"revTranID\", id_user, \"dateStamp\",\"BAccount\", 
				  \"bankRevBranch\", \"bankRevAmt\", \"bankRevStamp\", remark) 
			VALUES ('เงินที่เป็นของบุคคลภายนอก และทำการคืนให้เรียบร้อยแล้ว','$revTranID','$id_user', '$datelog','$BAccount',
			'$bankRevBranch','$bankRevAmt','$bankRevStamp',$remark)")); else $status++;
			
			// เตรียม payment voucher เพื่อทำการจ่ายออกให้บุคคลภายนอก ---------------------------------------------------------------------------------------------------------------
			// ตรวจสอบข้อมูลที่ใช้บันทึก
			if($preturnby_what_refund_anonymous==NULL OR $preturnby_what_refund_anonymous==''){
				$preturnby_what_refund_anonymous=0;
			}
			
			// แก้ไขปรับปรุงในเรื่องของ PV กับ วันที่บนเช็ค คนละวันกัน *todo ตรวจสอบอีกครั้งว่าจะมีปัญหาอะไรหรือไม่หากทำแบบนี้
			$effectivedate = '';
			if($returnChqDate >= $pvoucherdate_refund_anonymous){
				$effectivedate = $returnChqDate;
			}else{
				$effectivedate = $pvoucherdate_refund_anonymous;
			}
			
			// หาข้อมูลช่องทาง BID ของเงินดังกล่าว และจำนวนเงินที่จะคืน *todo ปรับให้สามารถคืนเงินได้ไม่เท่ากับที่โอนมาได้ เผื่อ
			$qry_revtranid_data = "
				SELECT \"bankRevAmt\"
				FROM finance.thcap_receive_transfer
				WHERE \"revTranID\" = '$revTranID'
			";
			if($res_revtranid_data=pg_query($qry_revtranid_data)){
				list($prevoucherdetailsid_bankRevAmt) = pg_fetch_array($res_revtranid_data);
			}else{
				$status++;
			}

			// เตรียมทำรายการขอออก Payment Voucher สำหรับการคืนเงิน (refund)
			$qry_vprevoucherdetailsid = pg_query("
				SELECT \"thcap_process_voucherCreate_refund\"(
						2::integer, -- เป็นการคืนเงินจากเงินโอน ของเงินที่ไม่ทราบผู้ชำระ (รายละเอียดเพิ่มเติมอ่านได้จาก function ที่เรียก)
						'$revTranID'::varchar, -- prefid : เลขที่รหัสเงินโอน
						'$effectivedate'::date, -- pvoucherdate : วันที่รายการ voucher มีผล (ถ้าเป็นการคืนเงินโดยการช่ายเช็คลงวันที่ล่วงหน้าจากที่ทำใบลดหนี้ วันที่ในรายการนี้จะเป็นวันที่ล่วงหน้า)
						'$preturnby_byChannel'::integer, -- pfromchannel : แหล่งที่คืนเงินออก เช่น จากเงินสดช่องทางไหน หรือบัญชีธนาคารใดๆ (BankInt.BID)
						'$revTranID'::varchar, -- pvoucherthisdetailsref : เลขที่สัญญาเจ้าของเงิน หรือ เลขที่รหัสรายการเงินโอน RT PT *todo ให้สามารถออกจากที่ไม่ใช่เลขที่สัญญาได้
						-996::integer, -- ppayfrom : ที่มาของเงินที่ออก -996 ออกจาเงินที่ไม่ทราบผู้ชำระเข้ามา
						'$prevoucherdetailsid_bankRevAmt'::numeric, -- ppayamt : จำนวนเงินคืนลูกค้า
						'$preturnby_payeebankname_refund_anonymous'::varchar, -- ppayid : รหัสผู้รับเงิน (รหัสลูกค้า / รหัสนิติบุคคล) payee
						'$id_user'::varchar, -- puserid : รหัสผู้อนุมัติรายการ
						'$premark_refund_anonymous'::varchar, -- premark : หมายเหตุรายการ
						'$preturnby_what_refund_anonymous'::smallint, -- preturnby_what (DEFAULT 0) : ช่องทางการจ่ายออกตามที่ผู้ใช้เลือก 0-ไม่ใช่เงินโอนหรือเช็ค เช่นเงินสด 1-เงินโอน 2-เช็ค
						'$preturnby_payerchqno_or_payeebankno_refund_anonymous'::varchar, -- preturnby_payerchqno_or_payeebankno : เลขที่เช็คจ่าย หรือ เลขที่บัญชีธนาคารผู้รับโอนปลายทาง
						'$preturnby_payeebankname_refund_anonymous'::varchar -- preturnby_payeebankname : ธนาคารผู้รับโอนปลายทาง
				)
			");
			if($qry_vprevoucherdetailsid){
				list($vprevoucherdetailsid) = pg_fetch_array($qry_vprevoucherdetailsid);
			}else{
				$status++;
			}
			
			// อนุมัติรายการ payment voucher อัตโนมัติ
			$qry_vvoucherid = pg_query("
				SELECT \"thcap_process_voucherApprove\"(
						'$vprevoucherdetailsid'::bigint, -- bigint : pprevoucherdetailsid : รหัสรายการที่จะอนุมัติ หรือไม่อนุมัติ
						'$id_user', -- varchar : pappvid : รหัสผู้อนุมัติรายการ
						1, -- integer : pstatus : สถานะการอนุมัติ (0-ไม่อนุมัติ, 1-อนุมัติ Auto, 2-อนุมัติ manual)
						'$premark_refund_anonymous' -- varchar : pappvremark : ความเห็นผู้อนัมุติ
				)
			");
			if($qry_vvoucherid){
				list($vprevoucherid) = pg_fetch_array($qry_vvoucherid);
				echo "ทำรายการอนุมัติใบสำคัญจ่ายเลขที่ $vprevoucherid สำหรับคืนเงินบุคคลภายนอกแล้ว";
			}else{
				$status++;
			}

		}else if($result==1 and $result3!=3){ //กรณีอนุมัติ
			//LOG
			if($sqlaction = pg_query("INSERT INTO finance.thcap_receive_transfer_log(detail,\"revTranID\", id_user, \"dateStamp\",\"BAccount\", 
				  \"bankRevBranch\", \"bankRevAmt\", \"bankRevStamp\", remark) 
			VALUES ('@$conID','$revTranID','$id_user', '$datelog','$BAccount',
			'$bankRevBranch','$bankRevAmt','$bankRevStamp',$remark)")); else $status++;
	
		}else if($result==1 and $result3==3){ //กรณีเช็ค	
			//update สถานะ chq ด้วยว่ามีการใช้เช็ค
			$upchq="UPDATE finance.thcap_receive_cheque SET \"revChqStatus\"=1 WHERE \"revChqID\"='$revChqID'";
			if($resupchq=pg_query($upchq)){
			}else{
				$status++;
			}
	
			//LOG
			if($sqlaction = pg_query("INSERT INTO finance.thcap_receive_transfer_log(detail,\"revTranID\", id_user, \"dateStamp\",\"BAccount\", 
				  \"bankRevBranch\", \"bankRevAmt\", \"bankRevStamp\", remark) 
			VALUES ('รายการที่เป็นเช็ครหัส $revChqID','$revTranID','$id_user', '$datelog','$BAccount',
			'$bankRevBranch','$bankRevAmt','$bankRevStamp',$remark)")); else $status++;
		}
	}
	if($errorstr!=""){
		pg_query("ROLLBACK");
		//ไม่มีรหัสเช็คนี้อยู่ในระบบ
		echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ไม่สามารถบันทึกข้อมูลได้ เนื่องจากไม่มีรหัสเช็คนี้อยู่ในระบบ</b></font></div>";
		echo "<input type=\"submit\" value=\"  ปิด  \" onclick=\"javascript:RefreshMe();\" />";	
	}
	else{
		if($status == 0){
			pg_query("COMMIT");
			echo "<font size=4><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></font><br><br>";
			echo "<input type=\"submit\" value=\"  ปิด  \" onclick=\"javascript:RefreshMe();\" />";
			//พิมพ์ pdf
			if(($result==2 || $result==8) && $revTranID != 'bid_1' && $vprevoucherid_rec_other != ""){
				$chk_voucherType = " 
					SELECT \"voucherType\"
					FROM \"thcap_temp_voucher_details\"
					WHERE \"voucherID\" = '$vprevoucherid_rec_other'";					
					if($res_chk_voucherType=pg_query($chk_voucherType)){
						list($v_voucherType) = pg_fetch_array($res_chk_voucherType);
					
					
					if($v_voucherType=='1'){
						$page_frm="../thcap_payment_voucher/pdf_payment_voucher.php";
					}
					else if($v_voucherType=='2'){
						$page_frm="../thcap_receive_voucher/pdf_receive_voucher.php";
					}
					else if($v_voucherType=='3'){
						$page_frm="../thcap_journal_voucher/pdf_journal_voucher.php";
					}
					
					?>
					<form name ="frm" action="<?php echo $page_frm; ?>"  method="post"  target="_blank">
						<input name="reprint"  value="reprint" hidden />
						<input name="print" type="submit" value="พิมพ์" hidden />						
						<?php	echo "<input name=\"select_print[]\" id=\"select_print1\" value=\"$vprevoucherid_rec_other\" hidden></td>"; ?>						
					</form>
					<?php 	echo "<script type=\"text/javascript\">";	
							echo "document.forms['frm'].print.click();";
							echo "</script>";
				}
			}
		}else{
			pg_query("ROLLBACK");
			echo $ins_error."<br>";
			echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b></font></div>";
			echo "<input type=\"submit\" value=\"  ปิด  \" onclick=\"javascript:RefreshMe();\" />";
		}
	}
}
?>
</td>
</tr>
</table>
</body>
</html>
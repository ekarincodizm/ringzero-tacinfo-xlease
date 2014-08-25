<?php
session_start();
include('../../config/config.php');
include('../function/checknull.php');

$datenow = nowDateTime();
$user_id = $_SESSION["av_iduser"];
$status=0;
$chkCus=0;
$institution = pg_escape_string($_POST['institution']);
$subject = $_POST['subject'];

if($subject==""){
	$allSubject = "4";
} else {
	for($i=0;$i<sizeof($subject);$i++){
		
		if($i==0){
			$allSubject = $subject[$i];
		}else{
			$allSubject = $allSubject.",".$subject[$i];
		}
	}
}

$setSubject = "{".$allSubject."}";

$customer = pg_escape_string($_POST['customer']);
//ตรวจสอบว่าเลือกลูกค้ามาจาก auto complete หรือไม่
if(preg_match("/#/i",$customer)){
$CusID = explode("#",$customer);
} else {
$chkCus++;
}
$debtor = pg_escape_string($_POST['debtor']);
//ตรวจสอบว่าลูกหนี้เพิ่มโดยระบบ หรือคีย์เข้าเอง
	if(preg_match("/#/i",$debtor)){
		$debtorID = explode("#",$debtor);
	}else {
		$debtorID[0] = "";
		$debtorID[1] = $debtor;
	}

$place = pg_escape_string($_POST['place']);
$phoneNumber = pg_escape_string($_POST['phoneNumber']);
$deadline = pg_escape_string($_POST['deadline']);
$assignName = pg_escape_string($_POST['assignName']);
$note = pg_escape_string($_POST['note']);
$row = pg_escape_string($_POST['row']);
$contractID = pg_escape_string($_POST['contractID']);
$payment = $_POST['payment'];
$paymentAmt = str_replace(",","",pg_escape_string($_POST['paymentAmt']));
$refvalue = pg_escape_string($_POST['refvalue']);

		
	pg_query("Begin");
		
		//ตรวนสอบว่ามีลูกค้าอยู่ในระบบจริงไหม
		$chkCus_qry = pg_query("select \"CusID\" from \"VSearchCus\" where \"CusID\" = '$CusID[0]'");
		$num_chkCus = pg_num_rows($chkCus_qry);
		if($num_chkCus==0){
			$chkCus++;
		}
		
		//insert รายละเอียด
		$ins_detail = "insert into assign_work_detail (\"AssignDate\",\"Institution\",\"Subject\",\"Place\",\"CusID\",\"DebtorID\",\"DebtorName\",
		\"PhoneNo\",\"DeadlineDate\",\"AssignName\",\"Note\",\"DoerID\",\"DoerStamp\",\"AppvID\",\"AppvStamp\",\"AuditID\",\"AuditStamp\",\"WorkStatus\",\"contractID\",\"deptStatus\")
						values ('$datenow','$institution','$setSubject','$place','$CusID[0]','$debtorID[0]','$debtorID[1]','$phoneNumber','$deadline','$assignName',
						'$note','$user_id','$datenow','000','$datenow','000','$datenow','1','$contractID','$payment') returning \"AssignNo\" ";
		
		if($qry=pg_query($ins_detail)){
			$AssignNo = pg_fetch_result($qry,0);
		} else {
			$status++;
		}
	
		for($j=1;$j<=$row;$j++){
			
			$select[$j]=$_POST["recChq$j"];
			if($select[$j]=="on"){
				
				$chequAmt[$j]=checknull(pg_escape_string($_POST["chequAmt$j"]));
				$date[$j]=checknull(pg_escape_string($_POST["date$j"]));
				$chqNo[$j]=checknull(pg_escape_string($_POST["chqNo$j"]));
				$chqBank[$j]=checknull(pg_escape_string($_POST["chqBank$j"]));
				$cashAmt[$j]=checknull(str_replace(",","",pg_escape_string($_POST["cashAmt$j"])));
				$docreturn[$j]=checknull(pg_escape_string($_POST["docreturn$j"]));
				
				//insert ข้อมูลเช็ค
				$ins_chq = "insert into assign_work_owner (\"AssignNo\",\"ChequeAmt\",\"Date\",\"Number\",\"ChqBank\",\"CashAmt\",\"DocReturn\")
						values ('$AssignNo',$chequAmt[$j],$date[$j],$chqNo[$j],$chqBank[$j],$cashAmt[$j],$docreturn[$j])";
				
				if(pg_query($ins_chq )){
				} else {
					$status++;
				}
			}
		}
		
		if($payment=="Y"){
			$qryContype = pg_query("select \"thcap_get_contractType\" ('$contractID') ");
			$contype = pg_fetch_result($qryContype,0);
			$remark = "ใบสั่งงาน Checker";
			if($subject!=""){
			for($i=0;$i<count($subject);$i++){
				if($subject[$i]==1 ||$subject[$i]==2){
					if($contype=="FA"){
						$typepay = "3189";
					}else if($contype=="FI"){
						$typepay = "C189";
					}
				} else {
					if($contype=="FA"){
						$typepay = "3102";
					}else if($contype=="FI"){
						$typepay = "C102";
					}
				}
				
				//ตั้งหนี้ตามจำนวนเรื่อง
				$setDept = "select thcap_process_setdebtloan('$contractID','$typepay','$refvalue','$datenow','$paymentAmt','$remark','000','0')";
				if(pg_query($setDept)){
				} else {
					$status++;
				}
			}
			}else {
				//ตั้งหนี้ครั้งเดียว (ไม่ระบุเรื่อง)
				$setDept = "select thcap_process_setdebtloan('$contractID','$typepay','$refvalue','$datenow','$paymentAmt','$remark','000','0')";
				if(pg_query($setDept)){
				} else {
					$status++;
				}
			}
		}
	
if($chkCus>0){
	pg_query("ROLLBACK");
	$alert="บันทึกข้อมูลล้มเหลว ลูกค้าไม่มีอยู่ในระบบ";
}else {	
	if($status == 0){
		pg_query("COMMIT");
		$alert="บันทึกข้อมูลสำเร็จแล้ว";
	} else{
		pg_query("ROLLBACK");
		$alert="บันทึกข้อมูลล้มเหลว";
	}
}
?>
<script>
function refres(){
	window.opener.location.reload();
	window.close();
}
function popU(U,N,T){
	newWindow = window.open(U, N, T);
}
</script>
<html>
		<center>
			<H1><?php echo $alert ?></H1><br>
<?php if($chkCus==0 and $status==0) { ?>
			<input type="button" name="print" id="print" value="พิมพ์" onclick="javascript:popU('pdf_print.php?AssignNo=<?php echo $AssignNo; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=700')"/>
<?php } ?>
			<input type="button" name="close" value="กลับ" onclick="location.href='frm_Index.php';"> 
		</center>
</html>
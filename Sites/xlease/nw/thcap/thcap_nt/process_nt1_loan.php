<?php
session_start();
include("../../../config/config.php");
include("../../function/checknull.php");
$id_user = $_SESSION["av_iduser"];
$nowdate = nowDateTime();
$date=nowDate();

$method=$_POST["method"]; //ประเภทการจัดการ
$contractID=$_POST["contractID"]; //เลขที่สัญญา

pg_query("BEGIN WORK");
$status=0;	

if($method=='add'){ //กรณี Create NT
	$guaranID=$_POST["guaranID"]; //ประเภทสินทรัพย์ที่จำนอง
	$startdate=$_POST["startdate"]; //วันที่ทำสัญญาจำนอง 
	$proctor=$_POST["proctor"];  //ทนายความผู้รับมอบอำนวจ
	list($proc_id,$proc_name)=explode('-',$proctor);
	$startnum=$_POST["startnum"]; //งวดที่เริ่มค้าง    ที่ต้องเก็บเนื่องจากใน pdf nt มีแสดงงวดที่เริ่มค้างและสิ้นสุด จึงเก็บประวัติว่า ณ ขณะนั้นๆ ค้างงวดที่เท่าไหร่ ถึงเท่าไหร่
	$endnum=$_POST["endnum"]; //งวดที่ค้างล่าสุด  
	$payleft=$_POST["payleft"]; //ค่างวดที่ต้องเรียกเก็บ 
	$paytag=$_POST["paytag"]; //ค่าติดตามทวงถาม 
	$proctor_nt=$_POST["proctor_nt"];  //ค่าหนังสือเตือนโดยทนาย
	$paytagnext=$_POST["paytagnext"]; //ค่าติดตามทวงถามในอนาคต 
	$duenext=$_POST["duenext"]; //งวดถัดไป 
	$paynext=$_POST["paynext"]; //ค่างวดถัดไป 
	$detailcontact=$_POST["detailcontact"];  //รายละัเอียดการติดต่อ
	$bankname=$_POST["bankname"];  //บัญชีธนาคาร
	$result=checknull($_POST["result"]);   //หมายเหตุ
	
	// อันดับแรกต้องตรวจสอบข้อมูลก่อนว่าข้อมูลนี้ได้ถูกอนุมัติไปก่อนหน้านี้หรือยัง(กรณีมีผู้ใช้งานพร้อมกัน)
	$qry_check=pg_query("select * from \"thcap_NT1_temp\" WHERE \"contractID\"='$contractID' and \"NT_1_Status\" = '2'");
	$num_check=pg_num_rows($qry_check);
	if($num_check > 0){
			echo "รายการนี้กำลังรอนุมัติอยู่ กรุณาทำรายการหลังจากอนุมัติแล้ว";
			exit();
	}else{ //กรณียังไม่ได้รับการอนุมัติก่อนหน้านี้
		//update รายการเก่าให้ active='false'
		$upactive="UPDATE \"thcap_NT1_temp\" SET \"active\"='FALSE' WHERE \"contractID\"='$contractID'";
		if($resupac=pg_query($upactive)){
		}else{
			$status++;
		}
		
		//หารหัสประเภทค่าติดตามของสัญญานี้
		$qrytypepay=pg_query("SELECT substring(account.\"thcap_mg_getMinPayType\"('$contractID'),1,1)");
		list($typePayID)=pg_fetch_array($qrytypepay);
		
		//รหัสค่างวด
		$typeID=$typePayID.'000';
		//รหัสค่าติดตามทวงถาม
		$typetag=$typePayID.'003';
		//รหัสค่าหนังสือเตือนโดยทนาย 
		$typent=$typePayID.'004';
		
		$debtmore="{{ $typeID,$payleft },{ $typetag,$paytag },{ $typent,$proctor_nt }}";
		
		//หาชื่อลูกค้า
		$qryname=pg_query("select \"CusID\",\"thcap_fullname\",\"CusState\" from \"vthcap_ContactCus_detail\" where \"contractID\"='$contractID' order by \"CusState\"");
		while($resname=pg_fetch_array($qryname)){
			list($CusID,$cusname,$CusState)=$resname;
			
			$ins="INSERT INTO \"thcap_NT1_temp\"(
					\"contractID\",\"CusID\",\"CusState\",\"NT_1_cusname\",\"NT_1_guaranID\", \"NT_1_Date\", 
					\"NT_1_Lawyer_Name\",\"NT_1_startDue\",\"NT_1_endDue\",\"NT_1_Debtmore\",
					\"NT_1_Duenext\",\"NT_1_Paynext\",\"NT_1_Paytagnext\", \"NT_1_contact\",
					\"NT_1_bank\",\"NT_1_Result\", \"NT_1_AddUser\", \"NT_1_AddStamp\", \"NT_1_Status\")
			VALUES ('$contractID','$CusID','$CusState','$cusname', '$guaranID', '$startdate', 
					'$proc_name', '$startnum','$endnum' ,'$debtmore', 
					'$duenext','$paynext','$paytagnext','$detailcontact', 
					'$bankname',$result, '$id_user', '$nowdate', '2')";

			if($resin=pg_query($ins)){
			}else{
				$status++;
			}	
		}
	}	
}else if($method=='approve'){ //กรณีอนุมัติ NT
	$stsapp=$_POST['stsapp'];//สถานะการอนุมัติ
	if($stsapp==""){ //ถ้าเป็น ค่าว่าง จริง แสดงว่ามาจาก show_ApproveNT1.php
		$sendfrom="showapprovent1";
		if(isset($_POST["btn1"])){
			$stsapp='yes'; //อนุมัติ
		}else{
			$stsapp='no';  //ไม่อนุมัติ
		}
	}
	//ตรวจสอบว่ารายการนี้รออนุมัติหรือไม่
	$qrychkapp=pg_query("select * from \"thcap_NT1_temp\" where \"contractID\"='$contractID' and \"NT_1_Status\"='2'");
	if(pg_num_rows($qrychkapp)>0){ //แสดงว่ายังมีรายการรออนุมัติอยู่
		if($stsapp=='yes'){ //แสดงว่าอนุมัติ
			$NT_1_Status=1; //สถานะอนุมัติแล้วรอส่งจดหมาย
			
			//insert ข้อมูลที่อนุมัติในตารางหลัก
			$ins="INSERT INTO \"thcap_NT1\"(
				\"NTID1\", \"contractID\", \"CusID\", \"CusState\", \"NT_1_cusname\", \"NT_1_guaranID\", 
				\"NT_1_Date\", \"NT_1_Lawyer_Name\", \"NT_1_startDue\", \"NT_1_endDue\", 
				\"NT_1_Debtmore\", \"NT_1_Duenext\", \"NT_1_Paynext\", \"NT_1_Paytagnext\", 
				\"NT_1_contact\", \"NT_1_bank\", \"NT_1_Result\", \"NT_tempID\")
				SELECT \"thcap_gen_documentID\"('$contractID','$date','6'),\"contractID\", \"CusID\", \"CusState\", \"NT_1_cusname\", \"NT_1_guaranID\", 
				\"NT_1_Date\", \"NT_1_Lawyer_Name\", \"NT_1_startDue\", \"NT_1_endDue\", 
				\"NT_1_Debtmore\", \"NT_1_Duenext\", \"NT_1_Paynext\", \"NT_1_Paytagnext\", 
				\"NT_1_contact\", \"NT_1_bank\", \"NT_1_Result\", \"NT_tempID\" FROM \"thcap_NT1_temp\"
				WHERE \"contractID\"='$contractID' and \"NT_1_Status\"='2' returning \"NTID1\" ";
			if($resin=pg_query($ins)){
				$ntid = pg_fetch_result($resin,0); // NT
			}else{
				$status++;
			}

			/***************ตั้งหนี้ค่าทนายอัตโนมัติ เมื่อกดอนุมัติ***********************/
			
			//หา typePayID ของค่าทนาย
			$qrytype=pg_query("select \"tpID\" from account.\"thcap_typePay\"
			where \"tpID\"=substring(account.\"thcap_mg_getMinPayType\"('$contractID'),1,1)||'004'");
			list($tpID)=pg_fetch_array($qrytype);
			
			if($tpID == "")
			{ // ถ้าไม่พบ typePayID ของค่าทนาย ของประเภทสัญญาดังกล่าว
				$status++;
				$error = "ไม่พบรหัสค่าใช้จ่ายของค่าทนาย";
			}
			
			//หาจำนวนเงิน ค่าหนังสือเตือน
			$qrydebt=pg_query("SELECT thcap_get_config('nt1_rate',\"thcap_get_contractType\"('$contractID'))");
			list($debtmoney)=pg_fetch_array($qrydebt);
			
			//ตั้งหนี้หนังสือเตือนอัตโนมัติ
			$qrysetdebt=pg_query("SELECT thcap_process_setdebtloan('$contractID','$tpID','$ntid','$nowdate','$debtmoney',null,'000','0')");
			list($setdebt)=pg_fetch_array($qrysetdebt);
			if($setdebt!='t'){
				$status++;
			}
			
			/***************จบตั้งหนี้ค่าทนายอัตโนมัติ เมื่อกดอนุมัติ***********************/
			
		}else{ //ไม่อนุมัติ
			$NT_1_Status=0; //สถานะไม่อนุมัติ
		}
			
		//update ตาราง temp ว่าอนุมัติแล้ว
		$uptemp="UPDATE \"thcap_NT1_temp\" SET \"NT_1_Status\"='$NT_1_Status', \"NT_1_AppUser\"='$id_user', \"NT_1_AppStamp\"='$nowdate'
		WHERE \"contractID\"='$contractID' and \"NT_1_Status\"='2'";
		if($resup=pg_query($uptemp)){
		}else{
			$status++;
		}
	}else{
		$status=-1;
	}
	
}
if($status==-1){
	pg_query("ROLLBACK");
	if($sendfrom=="showapprovent1"){		
		$script= '<script language=javascript>';
		$script.= " alert('ไม่พบรายการอนุมัติ อาจได้รับอนุมัติก่อนหน้านี้ กรุณาตรวจสอบ');
					opener.location.reload(true);
					self.close();";
		$script.= '</script>';
		echo $script;
	}
	else{
		echo 1;
	}
}else if($status == 0){
	pg_query("COMMIT");
	
	if($method=='add'){
		echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></font><br>";
		echo "<input type=button value=\"ปิด \" onclick=\"RefreshMe()\"></div>";
	}else{
		if($sendfrom=="showapprovent1"){		
			$script= '<script language=javascript>';
			if($stsapp=='yes'){
				$script.= " alert('อนุมัติเรียบร้อยแล้ว');";
			}
			else{
				$script.= " alert('ไม่อนุมัติเรียบร้อยแล้ว');";
			}			
			$script.= "opener.location.reload(true);";
			$script.= "self.close();";
			$script.= '</script>';
			echo $script;
		}
		else{
			echo 2;
		}
	}
}else{
	pg_query("ROLLBACK");
	
	if($method=='add'){
		echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b></font><br>";
		echo "<input type=button value=\"ปิด \" onclick=\"RefreshMe()\"></div>";
	}else{
		if($error == "ไม่พบรหัสค่าใช้จ่ายของค่าทนาย")
		{  
			if($sendfrom=="showapprovent1"){
				if($stsapp=='yes'){
					$script= '<script language=javascript>';
					$script.= " alert('ผิดพลาดไม่สามารถอนุมัติได้ เนื่องจากไม่พบรหัสค่าใช้จ่าย ของค่าทนาย ในระบบ กรุณาแจ้งผู้ดูแล');
								opener.location.reload(true);
								self.close();";
					$script.= '</script>';
					echo $script;					
				}
				else{
					$script= '<script language=javascript>';
					$script.= " alert('ผิดพลาดไม่สามารถไม่อนุมัติได้');
								opener.location.reload(true);
								self.close();";
					$script.= '</script>';
					echo $script;
				}
			}
			else{		
				echo 4;
			}
		}
		else
		{
			if($sendfrom=="showapprovent1"){
				$script= '<script language=javascript>';
					$script.= " alert('ผิดพลาดไม่สามารถไม่อนุมัติได้');
								opener.location.reload(true);
								self.close();";
					$script.= '</script>';
					echo $script;
			}
			else{
				echo 3;
			}
		}
	}
}
if($method=='add'){
?>
<script language="JavaScript" type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script> 
<?php
}
?>


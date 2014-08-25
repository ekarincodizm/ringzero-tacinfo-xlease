<?php
session_start();
include("../config/config.php");
include("../nw/function/checknull.php");
$datastring = pg_escape_string($_POST['datastring']);
$cid=pg_escape_string($_POST['cid']);
$datelog=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$id_user=$_SESSION["av_iduser"];


$perfs = explode("&", $datastring);
foreach($perfs as $perf){
    $perf_key_values = explode("=", $perf);
    $key = urldecode($perf_key_values[0]);
    $values = urldecode($perf_key_values[1]);
    $$key = $values;
}


pg_query("BEGIN WORK");
$status = 0;
$alert_text = "";
$status_insert = 0; // สำหรับตรวจความครบถ้วนของข้อมูลที่กรอก
$status_chk = 0; //สำหรับตรวจข้อมูลซ้ำ

// insert จาก กรอบที่แสดงให้กรอกข้อมูลอัตโนมัติ 
if( !empty($txt_money_tax) && !empty($txt_bill_tax) ){
    $status_insert++;
	$status_chk++;
    $qry="insert into carregis.\"DetailCarTax\" (\"IDCarTax\",\"CoPayDate\",\"TaxValue\",\"BillNumber\",\"TypePay\") values  ('$cid','$datetax','$txt_money_tax','$txt_bill_tax','101')";
    if(!$res=pg_query($qry)){
        $alert_text .= "- ไม่สามารถบันทึก ค่าภาษีรถยนต์ ได้\n";
        $status++;
    }
}

if( !empty($txt_money_tax_k) ){
    $status_insert++;
	$status_chk++;
    $qry="insert into carregis.\"DetailCarTax\" (\"IDCarTax\",\"CoPayDate\",\"TaxValue\",\"BillNumber\",\"TypePay\") values  ('$cid','$datetax_k','$txt_money_tax_k','$txt_bill_tax_k','-1')";
    if(!$res=pg_query($qry)){
        $alert_text .= "- ไม่สามารถบันทึก ลงขัน ค่าภาษีรถยนต์ ได้\n";
        $status++;
    }
}

if( !empty($txt_money_meter) && !empty($txt_bill_meter) ){
    $status_insert++;
	$status_chk++;
    $qry="insert into carregis.\"DetailCarTax\" (\"IDCarTax\",\"CoPayDate\",\"TaxValue\",\"BillNumber\",\"TypePay\") values  ('$cid','$datemeter','$txt_money_meter','$txt_bill_meter','105')";
    if(!$res=pg_query($qry)){
        $alert_text .= "- ไม่สามารถบันทึก ตรวจมิเตอร์ ได้\n";
        $status++;
    }
}

if( !empty($txt_money_meter_k) ){
    $status_insert++;
	$status_chk++;
    $qry="insert into carregis.\"DetailCarTax\" (\"IDCarTax\",\"CoPayDate\",\"TaxValue\",\"BillNumber\",\"TypePay\") values  ('$cid','$datemeter_k','$txt_money_meter_k','$txt_bill_meter_k','-1')";
    if(!$res=pg_query($qry)){
        $alert_text .= "- ไม่สามารถบันทึก ลงขัน ตรวจมิเตอร์ ได้\n";
        $status++;
    }
}

for($i=1; $i<=$txtcounter; $i++){
    $txt_money_other = 'txt_money_other'.$i;
    $txt_money_other = $$txt_money_other;
    if( !empty($txt_money_other) ){
        $status_insert++;
		$status_chk++;
        $dateother = 'dateother'.$i;
        $dateother = $$dateother;
        
        $txt_bill_other = 'txt_bill_other'.$i;
        $txt_bill_other = $$txt_bill_other;
        
        $selecttype = 'selecttype'.$i;
        $selecttype = $$selecttype;
		
		//ตรวจสอบว่าเป็นการจ่ายในส่วนภาษีรถยนต์หรือตรวจมิเตอร์ มีการบันทึกค่าใช้จ่ายซ้ำหรือไม่
		$qrytypechk=pg_query("select \"TypeDep\" from carregis.\"CarTaxDue\" where \"IDCarTax\"='$cid'");
		list($TypeDep)=pg_fetch_array($qrytypechk);
		
		//ค้นหาค่าลงขันที่ตั้งค่าใช้จ่ายไปแล้ว
		$qrychk1=pg_query("select \"IDDetail\" from carregis.\"DetailCarTax\" where \"TypePay\"='-1' and \"IDCarTax\"='$cid'");
		
		if($TypeDep=='101'){
			$numchk=2;
		}else if($TypeDep=='105'){
			$numchk=1;
			
			//ถ้าเป็นตรวจมิเตอร์จะไม่สามารถตั้งค่าใช้จ่ายเป็นค่าภาษีรถยนต์ได้
			if($selecttype=='101'){
				$status++;
				$alert_text='เนื่องจากค่าภาษีรถยนต์ไม่ได้อยู่ในรายการนี้';
				break;
			}
		}else if($TypeDep=='-1'){
			$numchk=2;
		}
		
		
		if($selecttype=='-1'){ //กรณีเป็นการตั้งค่าใช้จ่ายเป็นค่าลงขัีน
			//จะมีค่าลงขันได้แค่ $numchk รายการ
			if(pg_num_rows($qrychk1)==$numchk){ //ถ้ามีครบ $numchk รายการแล้ว จะไม่สามารถเพิ่มได้อีก
				$status_chk=0;
				break;
			}
		}else{
			//ตรวจสอบว่ามีการจ่ายค่านี้หรือยัง ถ้าจ่ายแล้วจะไม่สามารถบันทึกข้อมูลได้
			$qrychk=pg_query("select \"IDDetail\" from carregis.\"DetailCarTax\" where \"TypePay\"='$selecttype' and \"IDCarTax\"='$cid'");
			if(pg_num_rows($qrychk)>0){
				$status_chk=0;
				break;
			}
		}
		
		
        $BillNumber=checknull($txt_bill_other);
        $qry="insert into carregis.\"DetailCarTax\" (\"IDCarTax\",\"CoPayDate\",\"TaxValue\",\"BillNumber\",\"TypePay\") values  ('$cid','$dateother','$txt_money_other',$BillNumber,'$selecttype')";
        if(!$res=pg_query($qry)){
            $qry_typepay=pg_query("select \"TName\" from \"TypePay\" WHERE \"TypeID\"='$selecttype' ");
            if($res_typepay=pg_fetch_array($qry_typepay)){
                $TDName = $res_typepay["TName"];
            }
            $alert_text .= "- ไม่สามารถบันทึก $TDName ได้\n";
            $status++;
        }
    }
}

if($status_insert==0){
    pg_query("ROLLBACK");
    $data['success'] = false;
    $data['message'] = "กรุณากรอกข้อมูลที่ต้องการบันทึก!";
}elseif($status_chk ==0){
	pg_query("ROLLBACK");
    $data['success'] = false;
    $data['message'] = "ไม่สามารถบันทึกรายการซ้ำได้ กรุณาตรวจสอบ!";
}elseif($status == 0){	
	//pg_query("ROLLBACK");
	pg_query("COMMIT");
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(TAL) ทำรายการระบบทะเบียนรถ - ใส่ข้อมูลการชำระเงิน', '$datelog')");
	//ACTIONLOG---
    $data['success'] = true;
}else{
    pg_query("ROLLBACK");
    $data['success'] = false;
    $data['message'] = "ไม่สามารถบันทึกได้!\n$alert_text";
}

echo json_encode($data);
?>
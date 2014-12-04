<?php
session_start();
include("../config/config.php");

$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];

$idno=pg_escape_string($_POST['idno']);
$datepicker=pg_escape_string($_POST['datepicker']);
$countpay=pg_escape_string($_POST['countpay']);
$divmoney=pg_escape_string($_POST['divmoney']);
$discount=pg_escape_string($_POST['discount']);
$old_cusid=pg_escape_string($_POST['old_cusid']);
$old_asid=pg_escape_string($_POST['old_asid']);
$money=pg_escape_string($_POST['money']);
$counter=pg_escape_string($_POST['counter']);

pg_query("BEGIN WORK"); //220-01004
$status = 0;

// สร้าง Log file
$strFileNameLog = "myLog.txt"; // ชื่อไฟล์
$objFopenLog = fopen($strFileNameLog, 'a'); // เริ่มเปิดใช้งานไฟล์

$num_qry = 0; // ตัวนับ query

fwrite($objFopenLog, "\r\nเลือกใช้เงินรับฝาก\r\n");
fwrite($objFopenLog, "ผู้ทำรายการ : $user_id\r\n");
fwrite($objFopenLog, "วันเวลาที่ทำรายการ (postgres time) : $add_date\r\n");

$arr_datepicker = explode("#",$datepicker);

if($money == 0){
    $data['success'] = false;
    $data['message'] = "จำนวนเงินไม่ถูกต้อง";
	fwrite($objFopenLog, "จำนวนเงินไม่ถูกต้อง\r\n");
	fclose($objFopenLog); // ปิดการใช้งานไฟล์ Log
	pg_query("ROLLBACK");
    echo json_encode($data);
    exit;
}

if($money > $arr_datepicker[1])
{
    $data['success'] = false;
    $data['message'] = "จำนวนเงินไม่ถูกต้อง";
	fwrite($objFopenLog, "จำนวนเงินไม่ถูกต้อง\r\n");
}
else
{
    $data_arr = "";
    $alert_text = "";
	
	// หายอดเงินคงเหลือ ที่สามารถใช้ได้
	$str_remain = "select \"O_MONEY\", \"remain\" FROM \"VDepositRemain\" WHERE \"IDNO\" = '$idno' AND \"O_DATE\" <= '$arr_datepicker[0]' ORDER BY \"O_DATE\" ASC";
	$num_qry++;
	fwrite($objFopenLog, "user:$user_id php time start ".date('Y-m-d H:i:s')."-SQL$num_qry-");
	fwrite($objFopenLog, "$str_remain");
	$qry_remain = pg_query($str_remain);
	while($res_remain=pg_fetch_array($qry_remain))
	{
		$O_MONEY = $res_remain["O_MONEY"];
		$remain = $res_remain["remain"];
		
		if($remain == "" || empty($remain)){
			$balance = $O_MONEY;
		}else{
			$balance = $remain;
		}
		
		$sum_balance += $balance;
	}
	fwrite($objFopenLog, "-time end ".date('Y-m-d H:i:s')."\r\n");
	
	if($money > $sum_balance) // ถ้ายอดเงินที่ใช้ได้ ไม่เพียงพอให้ใช้
	{
		$data['success'] = false;
		$data['message'] = "จำนวนเงินคงเหลือไม่เพียงพอ อาจมีการทำรายการก่อนหน้านี้แล้ว กรุณาตรวจสอบ";
		fwrite($objFopenLog, "จำนวนเงินคงเหลือไม่เพียงพอ อาจมีการทำรายการก่อนหน้านี้แล้ว กรุณาตรวจสอบ\r\n");
		fclose($objFopenLog); // ปิดการใช้งานไฟล์ Log
		pg_query("ROLLBACK");
		echo json_encode($data);
		exit;
	}

    if($divmoney > 0){ //ตรวจสอบหากมีการจ่ายค่่างวด ให้ทำ
		$num_qry++;
		fwrite($objFopenLog, "user:$user_id php time start ".date('Y-m-d H:i:s')."-SQL$num_qry-");
		fwrite($objFopenLog, "select \"check_vat_use_recdate\"('$idno','$countpay','$arr_datepicker[0]')");
        $crs=@pg_query("select \"check_vat_use_recdate\"('$idno','$countpay','$arr_datepicker[0]')");
        $crt=@pg_fetch_result($crs,0);
		fwrite($objFopenLog, "-time end ".date('Y-m-d H:i:s')."\r\n");
        if(!empty($crt)){
            if($crt == "t" OR $crt == TRUE){
                $result = pg_query("select \"select_deposit_remain\"('$idno','$divmoney','$arr_datepicker[0]',1,'','$discount','$user_id')");
                $return1 = pg_fetch_result($result,0);
                if(empty($return1)){ $status++; }else{ $data_arr .= "$return1,"; }
            }else{
                $status++;
                $alert_text = "วันที่ที่เลือกมีการออก VAT ไม่สามารถใช้งานวันที่ได้ ให้ติดต่อผู้ดูแลระบบ";
				fwrite($objFopenLog, "$alert_text\r\n");
            }
        }else{
            $status++;
            $alert_text = "ไม่สามารถตรวจสอบ VAT ได้";
			fwrite($objFopenLog, "$alert_text\r\n");
        }
    }


    if($status == 0){
    
    for($i=1; $i<=$counter; $i++){ //จ่ายค่าอื่นๆ
        $typepayment = pg_escape_string($_POST['typepayment'.$i]);
        $amt = pg_escape_string($_POST['amt'.$i]);
        $newidno = pg_escape_string($_POST['newidno'.$i]);
        $submitchkconfirm = pg_escape_string($_POST['submitchkconfirm'.$i]);
            
        if($typepayment == 133){
            if($submitchkconfirm == 1){
                $str_result = "select \"select_deposit_remain\"('$idno','$amt','$arr_datepicker[0]','$typepayment','$newidno','0','$user_id')";
				$num_qry++;
				fwrite($objFopenLog, "user:$user_id php time start ".date('Y-m-d H:i:s')."-SQL$num_qry-");
				fwrite($objFopenLog, "$str_result");
				$result = pg_query($str_result);
                $return4 = pg_fetch_result($result,0);
				fwrite($objFopenLog, "-time end ".date('Y-m-d H:i:s')."\r\n");
                if(empty($return4)){ $status++; break; }else{ $data_arr .= "$return4,"; }
            }else{
				$str_chk = "select \"CusID\",\"asset_id\" from \"VContact\" WHERE \"IDNO\"='$newidno'";
				$num_qry++;
				fwrite($objFopenLog, "user:$user_id php time start ".date('Y-m-d H:i:s')."-SQL$num_qry-");
				fwrite($objFopenLog, "$str_chk");
				$qry_chk = pg_query($str_chk);
                if($res_chk=pg_fetch_array($qry_chk)){
                    $CusID=trim($res_chk["CusID"]);
                    $asset_id=trim($res_chk["asset_id"]);
                }
				fwrite($objFopenLog, "-time end ".date('Y-m-d H:i:s')."\r\n");
				
                if(($old_cusid != $CusID) && ($old_asid != $asset_id)){
                    $status++;
                    $alert_text = "ID ลูกค้า หรือ ID รถยนต์ ไม่ตรง [$old_cusid/$CusID] [$old_asid/$asset_id]";
					fwrite($objFopenLog, "$alert_text\r\n");
                    break;
                }else{
					$str_result = "select \"select_deposit_remain\"('$idno','$amt','$arr_datepicker[0]','$typepayment','$newidno','0','$user_id')";
					$num_qry++;
					fwrite($objFopenLog, "user:$user_id php time start ".date('Y-m-d H:i:s')."-SQL$num_qry-");
					fwrite($objFopenLog, "$str_result");
                    $result = pg_query($str_result);
                    $return2 = pg_fetch_result($result,0);
					fwrite($objFopenLog, "-time end ".date('Y-m-d H:i:s')."\r\n");
                    if(empty($return2)){ $status++; break; }else{ $data_arr .= "$return2,"; }
                }
            }
        }else{
			$str_result = "select \"select_deposit_remain\"('$idno','$amt','$arr_datepicker[0]','$typepayment','','0','$user_id')";
			$num_qry++;
			fwrite($objFopenLog, "user:$user_id php time start ".date('Y-m-d H:i:s')."-SQL$num_qry-");
			fwrite($objFopenLog, "$str_result");
            $result = pg_query($str_result);
            $return3 = pg_fetch_result($result,0);
			fwrite($objFopenLog, "-time end ".date('Y-m-d H:i:s')."\r\n");
            if(empty($return3)){ $status++; break; }else{ $data_arr .= "$return3,"; }
        }
    }

    }

    if($status == 0){
        $data_arr = substr($data_arr,0,strlen($data_arr)-1);
		
			//ACTIONLOG
			$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) ใช้เงินรับฝาก', '$add_date')");
			//ACTIONLOG---
		
		fwrite($objFopenLog, "บันทึกสำเร็จ\r\n");
		
        pg_query("COMMIT");
        $data['success'] = true;
        $data['message'] = $data_arr;
    }else{
        pg_query("ROLLBACK");
        $data['success'] = false;
        $data['message'] = "ไม่สามารถบันทึกได้! $alert_text";
		
		fwrite($objFopenLog, "ไม่สามารถบันทึกได้! $alert_text\r\n");
    }
    
}

fclose($objFopenLog); // ปิดการใช้งานไฟล์ Log

echo json_encode($data);
?>
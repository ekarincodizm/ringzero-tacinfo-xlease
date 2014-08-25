<?php
session_start();
include("../config/config.php");

$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];

$type = pg_escape_string($_POST['type']);

pg_query("BEGIN WORK");
$status = 0;

if($type == 1){

$perfs = explode("&", pg_escape_string($_POST['dt']));
foreach($perfs as $perf){
    $perf_key_values = explode("=", $perf);
    $key = urldecode($perf_key_values[0]);
    $values = urldecode($perf_key_values[1]);
    ${$key} = $values;
}

for($i = 1; $i<=$ct; $i++){
    if(${"dr".$i} == 0){
        $sum_cr+=round(${"cr".$i},2);
    }else{
        $sum_dr+=round(${"dr".$i},2);
    }
}

if(round($sum_cr,2) != round($sum_dr,2)){
    $status++;
    $err = "ยอดเงิน Dr Cr ไม่เท่ากัน $sum_cr / $sum_dr";
}else{

    $nub_4700 = 0;
    $nubcheck_1999 = 0;
    for($i = 1; $i<=$ct; $i++){
        $aid = ${"aid".$i};
        $acid = ${"acid".$i};
        $dr = ${"dr".$i};
        $cr = ${"cr".$i};
        
        if($acid == 4700 AND $dr != 0 AND $subacb != "AJ"){
            $nub_4700++;
        }
        
        if($acid == 1999 AND $dr != 0){
            $nubcheck_1999++;
        }
        
        $sql_update="UPDATE account.\"AccountBookDetail\" SET \"AcID\"='$acid',\"AmtDr\"='$dr',\"AmtCr\"='$cr' WHERE \"auto_id\"='$aid'";
        $res_update=@pg_query($sql_update);
        if(!$res_update){
            $status++;
            $err = "ไม่สามารถ Update AccountBookDetail !";
        }
    }
    
    if($nub1999 > 0){//เดิมมี VATB อยู่ก่อนแล้ว
        if($nubcheck_1999 == 0){//ตรวจสอบแล้ว ไม่พบ VATB
            //ทำการแก้ไข เอา VATB ออก
        }
    }else{//เดิม ไม่มี VATB
        if($nubcheck_1999 > 0){
            //ทำการแก้ไข ใส่ VATB ลงไป
        }
    }
    
    if($chkbuy == 1){
        $txtstr = "เงินสด";
    }else{
        $txtstr = "เช็ค เลขที่ $paybuy";
    }
    
    if($nub_4700 != 0){
         $text_add = "$buyreceiptno\n$buyfrom\n$tohpid\n$txtstr";
    }else{
         $text_add = "$detail";
    }
    
    $sql_update="UPDATE account.\"AccountBookHead\" SET \"acb_detail\"='$text_add' WHERE \"auto_id\"='$hid'";
    $res_update=@pg_query($sql_update);
    if(!$res_update){
        $status++;
        $err = "ไม่สามารถ Update AccountBookHead !";
    }


    if($nub1999 > 0){//เดิมมี VATB อยู่ก่อนแล้ว
        if($nubcheck_1999 == 0){//ตรวจสอบแล้ว ไม่พบ VATB
            //ทำการแก้ไข เอา VATB ออก
            $sql_update="UPDATE account.\"AccountBookHead\" SET \"ref_id\"=DEFAULT WHERE \"auto_id\"='$hid'";
            $res_update=@pg_query($sql_update);
            if(!$res_update){
                $status++;
                $err = "ไม่สามารถ Update AccountBookHead update VATB !";
            }
        }
    }else{//เดิม ไม่มี VATB
        if($nubcheck_1999 > 0){
            //ทำการแก้ไข ใส่ VATB ลงไป
            $sql_update="UPDATE account.\"AccountBookHead\" SET \"ref_id\"='VATB' WHERE \"auto_id\"='$hid'";
            $res_update=@pg_query($sql_update);
            if(!$res_update){
                $status++;
                $err = "ไม่สามารถ Update AccountBookHead update VATB !";
            }
        }
    }
    
    
    if($nub_4700 != 0){
    
        $qry_3=pg_query("select \"bh_id\" from account.\"BookBuy\" WHERE bh_id='$hid'");
        if($res_3=pg_fetch_array($qry_3)){
            $sql_update="UPDATE account.\"BookBuy\" SET \"buy_from\"='$buyfrom',\"buy_receiptno\"='$buyreceiptno',\"pay_buy\"='$txtstr',\"to_hp_id\"='$tohpid' WHERE \"bh_id\"='$hid'";
            $res_update=@pg_query($sql_update);
            if(!$res_update){
                $status++;
            }
        }else{
            $in_sql="insert into account.\"BookBuy\" (\"bh_id\",\"buy_from\",\"buy_receiptno\",\"pay_buy\",\"to_hp_id\") values ('$hid','$buyfrom','$buyreceiptno','$txtstr','$tohpid');";
            if(!$result=pg_query($in_sql)){
                $status++;
            }
        }
    
    }

}

}elseif($type == 2){
    $hid = pg_escape_string($_POST['id']);
	//ดังค่า acb_id ที่ต้องการ run ออกมา
	$qryacb=pg_query("select \"acb_id\",\"acb_date\",\"type_acb\" from account.\"AccountBookHead\" where \"auto_id\"='$hid'");
	list($acb_id,$acb_date,$type_acb)=pg_fetch_array($qryacb);
	
    $sql_update="UPDATE account.\"AccountBookHead\" SET \"cancel\"='TRUE' WHERE \"auto_id\"='$hid'";
    $res_update=@pg_query($sql_update);
    if(!$res_update){
        $status++;
    }
	$typeacb2=substr($acb_id,0,2); //ดึงประเภท/ปี/เดือน/วัน
	$typeacb='"'.$typeacb2.'"';
	$subcondition=substr($acb_id,0,8); //ดึงประเภท/ปี/เดือน/วัน
	$runnumnow=substr($acb_id,8,3); //ดึงเลขรันว่าเลขที่ยกเลิกเป็นเลขอะไร
	
	//ดึงข้อมูลหลังจากเลขรันปัจจุบันออกมา
	$runnew2=1;
	$qryall=pg_query("select \"auto_id\",\"acb_id\" from account.\"AccountBookHead\" where \"acb_date\" = '$acb_date' and \"cancel\"='FALSE' and \"type_acb\"='$type_acb' order by \"auto_id\"");
	$numall=pg_num_rows($qryall);
	while($resall=pg_fetch_array($qryall)){
		list($auto_idnext,$acb_idnext)=$resall;
		
		//จัดเรียงใหม่
		$runnew=sprintf("%03d", $runnew2);
		$acbnew=$subcondition.$runnew;
		$sql_update2="UPDATE account.\"AccountBookHead\" SET \"acb_id\"='$acbnew' WHERE \"auto_id\"='$auto_idnext'";
		$res_update2=@pg_query($sql_update2);
		if(!$res_update2){
			$status++;
		}
		$runnew2++;
	}
	
	//กรณีที่มีเลขต่อท้าย
	if($numall>0){
		$runnum=$runnew2-1;
	}else{
		$runnum=$runnumnow-1;
	}
	$sql_update3="UPDATE account.\"RunningNo\" SET $typeacb='$runnum' WHERE \"RunningDate\"='$acb_date'";
	$res_update3=@pg_query($sql_update3);
	if(!$res_update3){
		$status++;
		//$err = "ไม่สามารถ Update AccountBookHead !";
		$err="UPDATE account.\"RunningNo\" SET $typeacb='$runnum' WHERE \"RunningDate\"='$acb_date'";
	}
	//
}

if($status == 0){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) แก้ไขรายการบันทึกบัญชี', '$add_date')");
	//ACTIONLOG---
    pg_query("COMMIT");
    //pg_query("ROLLBACK");
    $data['success'] = true;
    $data['message'] = "บันทึกข้อมูลเรียบร้อยแล้ว";
}else{
    pg_query("ROLLBACK");
    $data['success'] = false;
    $data['message'] = "ไม่สามารถบันทึกข้อมูลได้\n$err";
}
    
echo json_encode($data);
?>
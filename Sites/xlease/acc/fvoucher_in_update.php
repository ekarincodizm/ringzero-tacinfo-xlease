<?php
session_start();
include("../config/config.php");
$now_date = nowDate();//ดึง วันที่จาก server
$user_id = $_SESSION["av_iduser"];
$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$cid = pg_escape_string($_POST['cid']);
$jobid = pg_escape_string($_POST['jobid']);
$moneyold = pg_escape_string($_POST['moneyold']);
$moneypay = pg_escape_string($_POST['moneypay']);
$detail = pg_escape_string($_POST['detail']);

$qry_old=pg_query("select * from account.tal_voucher WHERE \"vc_id\"='$cid' ");
if($res_old=pg_fetch_array($qry_old)){
    $approve_id = $res_old["approve_id"];
    $receipt_id = $res_old["receipt_id"];
    $appv_date = $res_old["appv_date"];

    if( substr($approve_id,strlen($approve_id)-2,2) == "#P" ){
        $approve_id = substr($approve_id,0,strlen($approve_id)-2);
    }
}

pg_query("BEGIN WORK");
$status=0;
$print_stat = 0;
$msg_error = "";

if($moneyold < $moneypay){
    //เบิกเพิ่ม
    $moneyrs = $moneypay-$moneyold;

    $rs=@pg_query("select account.\"gen_no\"('$now_date','VP')");
    $vp_id=@pg_fetch_result($rs,0);
    if(empty($vp_id)){
        $msg_error .= "Error : gen_no VP\n";
        $status++;
    }

    $insert="insert into account.\"voucher\" (\"vc_id\",\"vc_detail\",\"marker_id\",\"approve_id\",\"receipt_id\",\"cash_amt\",\"chq_acc_no\",\"chque_no\",\"do_date\",\"job_id\",\"vc_type\",\"autoid_abh\",\"appv_date\",\"recp_date\",\"compID\",\"comBranch\") values ('$vp_id','เบิกเพิ่มของ VC ID:$cid','$user_id','$approve_id','$receipt_id','$moneyrs',DEFAULT,DEFAULT,'$now_date','$jobid','P',DEFAULT,'$appv_date','$now_date','TAL','BK01')";
    $rs_voucher=@pg_query($insert);
    if(!$rs_voucher){
        $msg_error .= "Error : insert voucher 1\n";
        $status++;
    }
    
    $print_stat = 1;
    
}elseif($moneyold > $moneypay){
    //ทอนเงิน
    $moneyrs = $moneypay-$moneyold;

    $rs=@pg_query("select account.\"gen_no\"('$now_date','VR')");
    $vr_id=@pg_fetch_result($rs,0);
    if(empty($vr_id)){
        $msg_error .= "Error : gen_no VR\n";
        $status++;
    }

    $insert="insert into account.\"voucher\" (\"vc_id\",\"vc_detail\",\"marker_id\",\"approve_id\",\"receipt_id\",\"cash_amt\",\"chq_acc_no\",\"chque_no\",\"do_date\",\"job_id\",\"vc_type\",\"autoid_abh\",\"appv_date\",\"recp_date\",\"compID\",\"comBranch\") values ('$vr_id','เงินทอนของ VC ID:$cid','$user_id','$approve_id','$receipt_id','$moneyrs',DEFAULT,DEFAULT,'$now_date','$jobid','R',DEFAULT,'$appv_date','$now_date','TAL','BK01')";
    $rs_voucher=@pg_query($insert);
    if(!$rs_voucher){
        $msg_error .= "Error : insert voucher 2\n";
        $status++;
    }
    
    $print_stat = 2;
    
}

$up_sql=@pg_query("UPDATE account.\"job_voucher\" SET \"vcp_finish\"='TRUE',\"end_date\"='$now_date' WHERE \"job_id\"='$jobid'");
if(!$up_sql){
    $msg_error .= "Error : UPDATE job_voucher\n";
    $status++;
}

$up_sql=@pg_query("UPDATE account.\"voucher\" SET \"vc_detail\"='$detail' WHERE \"vc_id\"='$cid'");
if(!$up_sql){
    $msg_error .= "Error : UPDATE voucher\n";
    $status++;
}


if($status == 0){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) ทำรายการ Voucher รับเข้า', '$datelog')");
	//ACTIONLOG---
    pg_query("COMMIT");
    $data['success'] = true;
    if($print_stat == 0){
        $data['message'] = "<div align=center>บันทึกข้อมูลเรียบร้อยแล้ว</div>";
    }elseif($print_stat == 1){
        $data['message'] = "<div align=center>บันทึกข้อมูลเรียบร้อยแล้ว<br /><br /><input type=\"button\" name=\"btnprint\" id=\"btnprint\" value=\"พิมพ์ $vp_id\" onclick=\"javascript:window.open('fvoucher_print.php?id=$vp_id' , '33ffsf4f7e$vp_id','menuber=no,toolbar=yes,location=no,scrollbars=no, status=no,resizable=no,width=800,height=600')\"></div>";
    }elseif($print_stat == 2){
        $data['message'] = "<div align=center>บันทึกข้อมูลเรียบร้อยแล้ว<br /><br /><input type=\"button\" name=\"btnprint\" id=\"btnprint\" value=\"พิมพ์ $vr_id\" onclick=\"javascript:window.open('fvoucher_print.php?id=$vr_id' , '33ffsf4f7e$vr_id','menuber=no,toolbar=yes,location=no,scrollbars=no, status=no,resizable=no,width=800,height=600')\"></div>";
    }
}else{
    pg_query("ROLLBACK");
    $data['success'] = false;
    $data['message'] = "ไม่สามารถบันทึกได้!\n$msg_error";
}

echo json_encode($data);
?>
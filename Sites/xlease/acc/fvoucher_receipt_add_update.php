<?php
include("../config/config.php");

pg_query("BEGIN WORK");
$status=0;

$now_date = nowDate();//ดึง วันที่จาก server
$bt = pg_escape_string($_POST['bt']);
$vcid = pg_escape_string($_POST['vcid']);
$chkradio = pg_escape_string($_POST['chkradio']);
$list_vender = pg_escape_string($_POST['list_vender']);
$list_user = pg_escape_string($_POST['list_user']);
$othertxt = pg_escape_string($_POST['othertxt']);
$vendertxt = pg_escape_string($_POST['vendertxt']);

if($chkradio == 1){
    $str_id = $list_user;
}elseif($chkradio == 2){
    $str_id = $list_vender;
    $qry=pg_query("select \"vc_detail\" from account.tal_voucher WHERE \"vc_id\"='$vcid' ");
    if($res=pg_fetch_array($qry)){
        $vc_detail = $res["vc_detail"]."\nREC#".$vendertxt;
    }
    
    $up_detail=pg_query("UPDATE account.\"voucher\" SET \"vc_detail\"='$vc_detail' WHERE \"vc_id\"='$vcid'");
    if(!$up_detail){
        $status++;
    }
}elseif($chkradio == 3){
    $str_id = "REC#";
    $qry=pg_query("select \"vc_detail\" from account.tal_voucher WHERE \"vc_id\"='$vcid' ");
    if($res=pg_fetch_array($qry)){
        $vc_detail = $res["vc_detail"]."\nREC#".$othertxt;
    }
    
    $up_detail=pg_query("UPDATE account.\"voucher\" SET \"vc_detail\"='$vc_detail' WHERE \"vc_id\"='$vcid'");
    if(!$up_detail){
        $status++;
    }
}

$up_sql=pg_query("UPDATE account.\"voucher\" SET \"receipt_id\"='$str_id',\"recp_date\"='$now_date' WHERE \"vc_id\"='$vcid'");
if(!$up_sql){
    $status++;
}

if($status == 0){
    pg_query("COMMIT");
    //pg_query("ROLLBACK");
    $data['success'] = true;
    if($bt == 1){
        $data['message'] = "บันทึกข้อมูลเรียบร้อยแล้ว";
    }else{
        $data['message'] = "บันทึกข้อมูลเรียบร้อยแล้ว\nกดปุ่มพิมพ์ $vcid เพื่อพิมพ์ใบสำคัญจ่าย";
    }
}else{
    pg_query("ROLLBACK");
    $data['success'] = false;
    $data['message'] = "ไม่สามารถบันทึกได้!";
}

echo json_encode($data);
?>
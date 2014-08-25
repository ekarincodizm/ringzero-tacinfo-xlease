<?php
include("../config/config.php");
$datepick=pg_escape_string($_POST['datepick']);
$bank=pg_escape_string($_POST['bank']);

$qry_date=pg_query("select COUNT(\"id_tranpay\") AS chkdate from \"TranPay\" WHERE \"tr_date\" = '$datepick' AND \"terminal_id\"='TR-ACC' AND \"bank_no\"='$bank'");
 if($res_date=pg_fetch_array($qry_date)){
     $chkdate = $res_date["chkdate"];
     if($chkdate > 0){
        $data['success'] = false;
        $data['message'] = "วันที่เลือก ไม่สามารถใช้งานได้ !!!\nเนื่องจาก วันที่ $datepick ได้มีการเพิ่มข้อมูลไปแล้ว";
     }else{
         $data['success'] = true;
     }
}
 
echo json_encode($data);
?>
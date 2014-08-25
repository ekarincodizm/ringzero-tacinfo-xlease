<?php
include("../config/config.php");
$src=pg_escape_string($_POST['src']);

if(empty($src)){
    exit;
}

$qry_name=pg_query("SELECT * FROM account.tal_voucher WHERE \"vc_id\" = '$src'");
if($res_name=pg_fetch_array($qry_name)){
    $vc_detail = $res_name["vc_detail"]; $vc_detail = nl2br($vc_detail);
    $cash_amt = $res_name["cash_amt"]; $cash_amt_cv = number_format($cash_amt,2);
    $cq_amt = $res_name["cq_amt"]; $cq_amt_cv = number_format($cq_amt,2);
    $cq_id = $res_name["cq_id"];
    $acid_bank = $res_name["acid_bank"];
    $data['message'] .= "<hr><b><u>รายละเอียด</u> $src</b><br />";
    $data['message'] .= "<b>$vc_detail</b><br />";
    $data['success'] = true;
    if($cash_amt != 0){
        $data['message'] .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- จำนวนเงินสด : $cash_amt_cv<br />";
    }
    if($cq_amt != 0){
        $data['message'] .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- เลขที่เช็ค $cq_id<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- ธนาคาร $acid_bank<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- ยอดเงินในเช็ค : $cq_amt_cv<br />";
    }
}else{
    $data['success'] = false;
    $data['message'] = "ไม่พบข้อมูล";
}

echo json_encode($data);
?>
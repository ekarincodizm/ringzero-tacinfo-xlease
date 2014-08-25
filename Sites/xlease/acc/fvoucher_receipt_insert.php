<?php
session_start();
include("../config/config.php");
$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$now_date = nowDate();//ดึง วันที่จาก server
$user_id = $_SESSION["av_iduser"];

$detail = pg_escape_string($_POST['detail']);

$chkbuy = pg_escape_string($_POST['chkbuy']);

$cash_amt = pg_escape_string($_POST['cash_amt']);

$cq_acid = pg_escape_string($_POST['cq_acid']);
$cq_type = pg_escape_string($_POST['cq_type']);
$cq_id = pg_escape_string($_POST['cq_id']);
$cq_date = pg_escape_string($_POST['cq_date']);
$payto = pg_escape_string($_POST['payto']);
$cq_amt = pg_escape_string($_POST['cq_amt']);

//สิ่งที่เพิ่มเข้าไป by por
$cvtid=pg_escape_string($_POST['vtid']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    

<style type="text/css">
.ui-widget {
    font-family:tahoma;
    font-size:13px;
}
</style>

</head>
<body>

<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:left"><input name="button" type="button" onclick="window.location='fvoucher_receipt.php'" value="ย้อนกลับ" /></div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
<div style="clear:both"></div>

<fieldset><legend><B>Voucher Receipt</B></legend>

<div align="center" style="margin:20px 0px 20px 0px">

<?php
pg_query("BEGIN WORK");
$status = 0;

$insert_job="insert into account.\"job_voucher\" (\"st_date\",\"end_date\") values  ('$now_date',DEFAULT)";
$result_job=pg_query($insert_job);
if(!$result_job){
    $status++;
}

$cur_jobid=pg_query("select currval('account.\"job_voucher_job_id_seq\"');");
$rs_jobid=pg_fetch_result($cur_jobid,0);
if(empty($rs_jobid)){
    $status++;
}

$rs=pg_query("select account.\"gen_no\"('$now_date','VR')");
$vp_id=pg_fetch_result($rs,0);
if(empty($vp_id)){
    $status++;
}

if($chkbuy == 1){
    $cash_amt = $cash_amt * -1;
    $insert_voucher="insert into account.\"voucher\" (\"vc_id\",\"vc_detail\",\"marker_id\",\"approve_id\",\"receipt_id\",\"cash_amt\",\"chq_acc_no\",\"chque_no\",\"do_date\",\"job_id\",\"vc_type\",\"autoid_abh\",\"compID\",\"comBranch\") values ('$vp_id','$detail','$user_id',DEFAULT,DEFAULT,'$cash_amt',DEFAULT,DEFAULT,'$now_date','$rs_jobid','R',DEFAULT,'TAL','BK01')";
    $rs_voucher=pg_query($insert_voucher);
    if(!$rs_voucher){
        $status++;
    }
	
	$ins_detail="insert into account.\"voucher_details\" (\"vc_id\",\"vtid\") values ('$vp_id','$cvtid')";
	$rs_detail=pg_query($ins_detail);
    if(!$rs_detail){
        $status++;
    }
}elseif($chkbuy == 2){
    $insert_chqcom="insert into account.\"ChequeOfCompany\" (\"AcID\",\"ChqID\",\"DateOnChq\",\"Amount\",\"TypeOfPay\",\"DoDate\",\"PayTo\") values ('$cq_acid','$cq_id','$cq_date','$cq_amt','$cq_type','$now_date','$payto')";
    $result_chqcom=pg_query($insert_chqcom);
    if(!$result_chqcom){
        $status++;
    }

    $insert_voucher="insert into account.\"voucher\" (\"vc_id\",\"vc_detail\",\"marker_id\",\"approve_id\",\"receipt_id\",\"cash_amt\",\"chq_acc_no\",\"chque_no\",\"do_date\",\"job_id\",\"vc_type\",\"autoid_abh\",\"compID\",\"comBranch\") values ('$vp_id','$detail','$user_id',DEFAULT,DEFAULT,DEFAULT,'$cq_acid','$cq_id','$now_date','$rs_jobid','R',DEFAULT,'TAL','BK01')";
    $rs_voucher=pg_query($insert_voucher);
    if(!$rs_voucher){
        $status++;
    }
	
	$ins_detail="insert into account.\"voucher_details\" (\"vc_id\",\"vtid\") values ('$vp_id','$cvtid')";
	$rs_detail=pg_query($ins_detail);
    if(!$rs_detail){
        $status++;
    }
}

//=======================================//

if($status == 0){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) บันทึก Voucher Receipt', '$datelog')");
	//ACTIONLOG---
    pg_query("COMMIT");
    //pg_query("ROLLBACK");
    echo "เพิ่มข้อมูลเรียบร้อยแล้ว";
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถเพิ่มข้อมูลได้";
}
?>

</div>

 </fieldset>

        </td>
    </tr>
</table>

</body>
</html>
<?php
session_start();
include("../config/config.php");

$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$get_id_user = $_SESSION["av_iduser"];
$nowdate = date("Y/m/d");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<fieldset><legend><B>อนุมัติ</B></legend>
<div align="center">
<?php
$payid = pg_escape_string($_POST['payid']);
$select_money = pg_escape_string($_POST['select_money']);
$cash_money = pg_escape_string($_POST['cash_money']);
$cheque_bank = pg_escape_string($_POST['cheque_bank']);
$cheque_number = pg_escape_string($_POST['cheque_number']);
$cheque_date = pg_escape_string($_POST['cheque_date']);
$cheque_money = pg_escape_string($_POST['cheque_money']);
$cheque_remark = pg_escape_string($_POST['cheque_remark']);
$company = pg_escape_string($_POST['company']);

pg_query("BEGIN WORK");

if($select_money == 1){
        
    $in_sql="UPDATE gas.\"PayToGas\" SET \"cash\"='$cash_money',\"idauthority\"='$get_id_user',\"Remark\"='$cheque_remark' WHERE \"payid\"='$payid' ";
    if($result=pg_query($in_sql)){
        $status = 0;
    }else{
        $status = 1;
    }    
    
}else{
    $in_sql="UPDATE gas.\"PayToGas\" SET \"CQBank\"='$cheque_bank',\"CQID\"='$cheque_number',\"CQDate\"='$cheque_date',\"CQAmt\"='$cheque_money',\"idauthority\"='$get_id_user',\"Remark\"='$cheque_remark' WHERE \"payid\"='$payid' ";
    if($result=pg_query($in_sql)){
        $status = 0;
    }else{
        $status = 1;
    }
}

    $qry_1=pg_query("select * from gas.\"PoGas\" WHERE \"payid\"='$payid'");
    while($res_1=pg_fetch_array($qry_1)){
        $costofgas = $res_1["costofgas"]; $costofgas = round($costofgas,2);
        $vatofcost = $res_1["vatofcost"]; $vatofcost = round($vatofcost,2);
        $poid = $res_1["poid"];
        $vat_date = $res_1["vat_date"];
        
        $select_sql1 = pg_query("SELECT account.save_acc_gas_admin('$vat_date', '$cheque_number', '$costofgas', '$vatofcost', '$poid','$company');");
        if($res_sql1=pg_fetch_result($select_sql1,0)){
            $status = 0;
        }else{
            $status = 1;
        }
        
    }


    $in_sql3="UPDATE gas.\"PoGas\" SET \"status_approve\"='TRUE' WHERE \"payid\"='$payid' ";
     if($result=pg_query($in_sql3)){
        $status = 0;
    }else{
        $status = 1;
    }

if($status == 0){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$get_id_user', '(TAL) อนุมัติเรื่อง Gas', '$datelog')");
	//ACTIONLOG---
    pg_query("COMMIT");
    echo "บันทึกข้อมูลเรียบร้อยแล้ว";
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้";
}

?>
</div>
<br><br>
<center>
<input type="button" value="  Back  " onclick="location.href='frm_approval_select.php'">
</center>
</fieldset>

        </td>
    </tr>
</table>

</body>
</html>
<?php
session_start();
include("../config/config.php");
$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$cid = pg_escape_string($_GET["cid"]);
$rid = pg_escape_string($_GET["rid"]);
$app_id = $_SESSION["av_iduser"]);
$memo = pg_escape_string($_GET["memo"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
</head>
<body>
 
<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>

<div class="wrapper">

<div style="float:left"><input name="button" type="button" onclick="window.location='approve_rec.php'" value=" ย้อนกลับ " /></div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
<div style="clear:both"></div>

<fieldset><legend><B>อนุมัติยกเลิกใบเสร็จ</B></legend>

<div align="center">

<?php
$status = 0;
$type=substr($rid,2,1);

pg_query("BEGIN WORK");

if($type=="R"){

    $result=pg_query("UPDATE \"FOtherpay\" SET \"Cancel\"='TRUE' WHERE \"RefAnyID\"='$rid' AND \"PayType\"='DEP'");
    if(!$result){
        $status+=1;
    }
    
    $result=pg_query("UPDATE \"Fr\" SET \"Cancel\"='TRUE',\"R_memo\"='$memo' WHERE \"R_Receipt\"='$rid' ");
    if(!$result){
        $status+=1;
    }
}elseif($type=="N" || $type=="K"){
    
    $qry_cc=pg_query("select \"RefAnyID\",\"PayType\" from \"FOtherpay\" WHERE \"O_RECEIPT\"='$rid' AND \"PayType\"='DEP'");
    if($res_cc=pg_fetch_array($qry_cc)){
        $RefAnyID = $res_cc['RefAnyID'];

        $result=pg_query("UPDATE \"FOtherpay\" SET \"Cancel\"='TRUE' WHERE \"RefAnyID\"='$RefAnyID'");
        if(!$result){
            $status+=1;
        }
    }

    $result=pg_query("UPDATE \"FOtherpay\" SET \"Cancel\"='TRUE',\"O_memo\"='$memo' WHERE \"O_RECEIPT\"='$rid' ");
    if(!$result){
        $status+=1;
    }
}elseif($type=="V"){
    $result=pg_query("UPDATE \"FVat\" SET \"Cancel\"='TRUE',\"V_memo\"='$memo' WHERE \"V_Receipt\"='$rid' ");
    if(!$result){
        $status+=1;
    }
}

$result=pg_query("UPDATE \"CancelReceipt\" SET \"admin_approve\"='TRUE',\"approveuser\"='$app_id' WHERE \"c_receipt\"='$cid' ");
if(!$result){
    $status+=1;
}

if($status == 0){
		//ACTIONLOG
			$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) อนุมัติยกเลิกใบเสร็จ', '$datelog')");
		//ACTIONLOG---
    pg_query("COMMIT");
    echo "บันทึกเรียบร้อยแล้ว";
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกได้";
}
?>

</div>

</fieldset>

</div>
        </td>
    </tr>
</table>

</body>
</html>

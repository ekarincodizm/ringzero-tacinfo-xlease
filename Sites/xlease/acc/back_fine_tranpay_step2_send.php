<?php
Ob_start();
session_start();
include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
    
</head>
<body>

<table width="750" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td align="center">

<div style="float:right"><input type="button" name="btn1" id="btn1" value="ปิดหน้านี้" class="ui-button" onclick="window.close(); window.opener.location.reload();"></div>
<div style="float:left">&nbsp;</div>
<div style="clear:both"></div>

<fieldset>
<?php
pg_query("BEGIN WORK");

$iduser = $_SESSION["av_iduser"];
$id = pg_escape_string($_POST['id']);
$bank = pg_escape_string($_POST['bank']);
$idno = pg_escape_string($_POST['idno']);
$date = pg_escape_string($_POST['date']);
$amt = pg_escape_string($_POST['amt']);
$trantype = pg_escape_string($_POST['trantype']);
$chk = pg_escape_string($_POST['chk']);

$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];

$c_chk = count($chk);

if($c_chk == 0){
    header("Refresh: 0; url=back_fine_tranpay_step2.php?id=$id&bank=$bank&idno=$idno&date=$date&amt=$amt&trantype=$trantype");
    echo "<script language=Javascript>alert ('กรุณาเลือกรายการ');</script>";
}else{

foreach($chk AS $v){
    $arr_v = explode("#",$v);
    $sumary+=$arr_v[4];
}

if($sumary != $amt){
    header("Refresh: 0; url=back_fine_tranpay_step2.php?id=$id&bank=$bank&idno=$idno&date=$date&amt=$amt&trantype=$trantype");
    echo "<script language=Javascript>alert ('ยอดเงินไม่ถูกต้อง กรุณาตรวจสอบยอดเงินให้ตรงกัน');</script>";
}else{


if($trantype == "TR"){
    $type = "TR-ACC";
}else{
    $type = "Bill Payment";
}

foreach($chk AS $vi){
    $nn++;
    $arr_vi = explode("#",$vi);
    
    if($nn==1){
        $ud1=pg_query("UPDATE \"TranPay\" SET \"post_on_date\"='$arr_vi[3]',\"post_on_asa_sys\"='TRUE',\"post_by\"='$iduser' WHERE \"id_tranpay\"='$id';");
        if(!$ud1){
            $status++;
        }
    }
    
    if($arr_vi[0] == "FR"){
        $ud2=pg_query("UPDATE \"Fr\" SET \"R_memo\"='$type',\"PayType\"='$bank' WHERE \"IDNO\"='$arr_vi[1]' AND \"R_DueNo\"='$arr_vi[5]' AND \"R_Receipt\"='$arr_vi[2]';");
        if(!$ud2){
            $status++;
        }
    }elseif($arr_vi[0] == "OT"){
        $ud3=pg_query("UPDATE \"FOtherpay\" SET \"O_memo\"='$type',\"PayType\"='$bank' WHERE \"IDNO\"='$arr_vi[1]' AND \"O_RECEIPT\"='$arr_vi[2]';");
        if(!$ud3){
            $status++;
        }
    }
    
}

if($status == 0){

	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) ปรับรายการ Tranpay', '$add_date')");
	//ACTIONLOG---
    pg_query("COMMIT");
    echo "บันทึกข้อมูลเรียบร้อยแล้ว";
}else{
    pg_query("ROLLBACK");
    header("Refresh: 0; url=back_fine_tranpay_step2.php?id=$id&bank=$bank&idno=$idno&date=$date&amt=$amt&trantype=$trantype");
    echo "<script language=Javascript>alert ('ไม่สามารถบันทึกข้อมูลได้');</script>";
}

}//check money match

}//check select item
?>

</fieldset>

        </td>
    </tr>
</table>

</body>
</html>
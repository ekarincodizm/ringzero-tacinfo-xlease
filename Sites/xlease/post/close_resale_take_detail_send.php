<?php
include("../config/config.php");

$oid = $_POST['oid'];
$nid = $_POST['nid'];
$datepicker = $_POST['datepicker'];
$money = $_POST['money'];
$user_id = $_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

</head>
<body>

<table width="470" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<fieldset><legend><B>ปิดสัญญารถยึด/ขายคืน</B></legend>

<div class="ui-widget" align="center">
<?php
pg_query("BEGIN WORK");
$status = 0;

$in_sql="UPDATE \"Fp\" SET \"P_BEGINX\"='$money' WHERE \"IDNO\"='$nid';";
if(!$result=pg_query($in_sql)){
    $status++;
}

$in_sql="UPDATE \"Fp\" SET \"P_CLDATE\"='$datepicker',\"P_ACCLOSE\"='TRUE' WHERE \"IDNO\"='$oid';";
if(!$result=pg_query($in_sql)){
    $status++;
}

if($status == 0){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) ปิดสัญญารถยึด/ขายคืน', '$add_date')");
	//ACTIONLOG---
    pg_query("COMMIT");
    echo "บันทึกเรียบร้อยแล้ว";
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกได้";
}
?>

<br />
<br />
<input type="button" name="btn1" id="btn1" value="ปิดหน้านี้" class="ui-button" onclick="window.close(); window.opener.location.reload();">

</div>

 </fieldset>

        </td>
    </tr>
</table>

</body>
</html>
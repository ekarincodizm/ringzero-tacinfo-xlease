<?php
session_start();
include("../../config/config.php");

$abh_autoid = pg_escape_string($_GET["abh_autoid"]);

$add_date = nowDateTime();
$user_id = $_SESSION["av_iduser"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script>
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script>

</head>
<body>

<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
        
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();" class="ui-button"></div>
<div style="clear:both;"></div>

<fieldset><legend><B>ลบรายการ</B></legend>

<div align="center">

<?php
    
pg_query("BEGIN WORK");
$status = 0;

// ขอยกเลิกรายการพร้อมอนุมัติทันที
$del_h_temp = "select account.thcap_cancel_accbook('$abh_autoid','$user_id')";
if(pg_result(pg_query($del_h_temp),0) != "t"){$status++;}

if($status==0)
{
	//ACTIONLOG
	$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) บันทึกบัญชี', '$add_date')");
	//ACTIONLOG---
	pg_query("COMMIT");
	//pg_query("ROLLBACK");
	
	?>
	
	<script>
		opener.location.reload(true); // reload หน้าหลัก
	</script>

	<?php
	
	echo "ลบข้อมูลเรียบร้อยแล้ว";
}
else
{
	?>
	
	<script>
		opener.location.reload(true); // reload หน้าหลัก
	</script>

	<?php
	
	pg_query("ROLLBACK");
	echo "การลบข้อมูลผิดพลาด!!";
}

?>

</div>

</fieldset>

        </td>
    </tr>
</table>

</body>
</html>
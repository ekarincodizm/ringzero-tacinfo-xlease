<?php
session_start();
include("../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
$id_user=$_SESSION["av_iduser"];
$quryuser=pg_query("select \"emplevel\" from \"fuser\" where \"id_user\"='$id_user' ");
list($leveluser)=pg_fetch_array($quryuser);

$app_date = Date('Y-m-d H:i:s');
$contractID = pg_escape_string($_GET["contractID"]);
$autoID = pg_escape_string($_GET["waive_auto_id"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
 <title>(THCAP) ตรวจสอบยกเว้นเช็คค้ำตั๋วเงิน</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>
<script>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
function Check(){
	var ReaSon = document.getElementById('reason').value;
	
	if(ReaSon==""){
		alert('กรุณาระบุเหตุผลที่ขอละเว้น');
		return false;
	} else {
		return true;
	}
}
</script>
<body>
	<form action="Insert_Appv.php" method="post">
		
			<table width="40%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
				<tr>
					<td align="center">
						<h2>เหตุผลที่ขอยกเว้นเช็คค้ำตั๋วเงิน</h2>
					</td>
				</tr>
				<tr>
					<td align="center">
						<h3>เลขที่สัญญา: <?php echo $contractID ?></h3><input type="text" name="contractID" value ="<?php echo $contractID; ?>" hidden >
					</td>
				</tr>
				<tr>
					<td align="center">
					<textarea name ="reason" id="reason" cols="50" rows="10"></textarea>
					</td>
				</tr>
				<tr>
					<td align="center">
					<input type="submit" name="submit" value="บันทึก" onclick="return Check();" style="cursor:pointer"> <input type='button' onclick="window.close();" value="ยกเลิก">
					</td>
				</tr>
			</table>
		
	</form>
</body>
</html>
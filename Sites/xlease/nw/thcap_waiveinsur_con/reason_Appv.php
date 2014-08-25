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
$statusHis = pg_escape_string($_GET["statusHis"]);
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
<?php
$qry_con=pg_query("select waive_reason from \"thcap_contract\" a
			left join thcap_contract_waive_fa_chqguaranteed b on a.\"contractID\"= b.\"contractID\"
			where \"waive_auto_id\"='$autoID'");
			$res_Reson=pg_fetch_result($qry_con,0);
?>
<body>
	<form name="AppvFrm" method="post" action="Update_Appv.php">
		
			<table width="40%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
				<tr>
					<td align="center">
						<h2>เหตุผลที่ขอยกเว้นเช็คค้ำตั๋วเงิน</h2>
					</td>
				</tr>
				<tr>
					<td align="center">
						<h3>เลขที่สัญญา: <?php echo $contractID ?></h3> 
					</td>
				</tr>
				<tr>
					<td align="center">
					<textarea readonly name="reason" cols="50" rows="10" ><?php echo $res_Reson ?></textarea>
					</td>
				</tr>
				<?php
					if($statusHis==1){
						$hidden="hidden";
					} else {
						$hidden="";
					}
				?>
				<tr>
					<td align="center">
					<input type="hidden" name="auto_id" value="<?php echo $autoID; ?>">
					<input <?php echo $hidden; ?>  type="submit" name="Appv" value="อนุมัติ" /> <input <?php echo $hidden; ?> type="submit" name="NotAppv" value="ไม่อนุมัติ"/> <input type='button' onclick="window.close();" value="ปิด">
					</td>
				</tr>
			</table>
	</form>
</body>
</html>
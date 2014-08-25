<?php
include("../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}

$yy = pg_escape_string($_GET["yy"]);
$mm = pg_escape_string($_GET["mm"]);

$month = array('01'=>'มกราคม', '02'=>'กุมภาพันธ์', '03'=>'มีนาคม', '04'=>'เมษายน', '05'=>'พฤษภาคม', '06'=>'มิถุนายน', '07'=>'กรกฏาคม', '08'=>'สิงหาคม' ,'09'=>'กันยายน' ,'10'=>'ตุลาคม', '11'=>'พฤศจิกายน', '12'=>'ธันวาคม');
$month_shot = array('1'=>'มกราคม', '2'=>'กุมภาพันธ์', '3'=>'มีนาคม', '4'=>'เมษายน', '5'=>'พฤษภาคม', '6'=>'มิถุนายน', '7'=>'กรกฏาคม', '8'=>'สิงหาคม' ,'9'=>'กันยายน' ,'10'=>'ตุลาคม', '11'=>'พฤศจิกายน', '12'=>'ธันวาคม');

$show_month = $month[$mm];
$show_yy = $yy+543;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
    <title>บันทึก บัญชีกระดาษทำการ</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/number.js"></script>
	
	<script>
		function validate()
		{
			if(document.getElementById("save_name").value == '')
			{
				alert('กรุณา ตั้งชื่อ');
				return false;
			}
			else
			{
				return true;
			}
		}
	</script>
</head>

<body>
	<div  align="center">
		<h2>บันทึก บัญชีกระดาษทำการ</h2>
		<?php echo "ประจำเดือน $show_month ปี $show_yy"; ?>
	</div>
	<br><br>
	<form name="frm1" id="frm1" action="process_save.php" method="post">
		<center>
			<table>
				<tr>
					<td align="right">ตั้งชื่อ : </td>
					<td align="left"><input type="textbox" id="save_name" name="save_name" size="60"/> <font color="#FF0000">*</font></td>
				</tr>
				<tr>
					<td align="right">หมายเหตุ : </td>
					<td align="left"><textarea name="save_notes" cols="45" rows="4"></textarea></td>
				</tr>
				<tr>
					<td align="center" colspan="2">
						<input type="hidden" name="mm" value="<?php echo $mm; ?>" />
						<input type="hidden" name="yy" value="<?php echo $yy; ?>" />
						<input type="submit" style="cursor:pointer;" value="บันทึก" onClick="return validate();"/>
						&nbsp;&nbsp;&nbsp;
						<input type="button" value=" ยกเลิก " onclick="window.close();" style="cursor:pointer;"/>
					</td>
				</tr>
			</table>
		</center>
	</form>
</body>
</html>
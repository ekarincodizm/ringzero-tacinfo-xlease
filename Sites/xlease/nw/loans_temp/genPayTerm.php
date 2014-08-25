<?php
// ส่วนติดต่อกับฐานข้อมูล    
include("../../config/config.php");

$term = $_GET["term"]; // จำนวนงวด
$conFirstDue = $_GET["conFirstDue"]; // วันที่ชำระงวดแรก
$conMinPay = $_GET["conMinPay"]; // จำนวนเงินกู้
$conRepeatDueDay = $_GET["conRepeatDueDay"]; // จ่ายทุกๆวันที่

if(strlen($conRepeatDueDay) == 1){$conRepeatDueDay = "0".$conRepeatDueDay;} // ถ้า จ่ายทุกๆวันที่ ที่รับมาเป็นเลขตัวเดียว ให้เติมศูนย์ด้านหน้าอีกหนึ่งตัว
?>
	
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">

<link type="text/css" rel="stylesheet" href="act.css"></link>

<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script language=javascript>
$(document).ready(function(){
	var i;
	for(i = 1; i <= <?php echo $term; ?>; i++)
	{
		$("#genDate"+i).datepicker({
			showOn: 'button',
			buttonImage: 'images/calendar.gif',
			buttonImageOnly: true,
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd'
		});
	}
});
</script>

<table width="auto" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#79BCFF">
		<th>งวดที่</th>
		<th>วันครบกำหนดชำระ</th>
		<th>จำนวนเงิน</th>
	</tr>

	<?php
	
	for($i=1; $i <= $term; $i++)
	{
		if($i%2==0){
			echo "<tr class=\"odd\">";
		}else{
			echo "<tr class=\"even\">";
		}
		
		if($i == 1)
		{
			$nextConDue = $conFirstDue;
		}
		elseif($i == 2)
		{
			$arrayConDue = explode("-",$nextConDue);
			$arrayConDue[2] = $conRepeatDueDay; // ถ้าเป็นงวดที่สอง ให้กำหนดวันที่จะชำระ เป็นวันที่จาก จ่ายทุกๆวันที่
			$plusConDue = mktime(0,0,0,$arrayConDue[1]+1,$arrayConDue[2],$arrayConDue[0]); // เวลา เดือน วัน ปี
			$nextConDue = date("Y-m-d",$plusConDue); // วันที่จะครบกำหนดชำระ แบบ ปี-เดือน-วัน
		}
		else
		{
			$arrayConDue = explode("-",$nextConDue);
			$plusConDue = mktime(0,0,0,$arrayConDue[1]+1,$arrayConDue[2],$arrayConDue[0]); // เวลา เดือน วัน ปี
			$nextConDue = date("Y-m-d",$plusConDue); // วันที่จะครบกำหนดชำระ แบบ ปี-เดือน-วัน
		}

		echo "<td align=\"center\">$i</td>";
		echo "<td><input style=\"text-align:center;\" type=\"text\" name=\"genDate$i\" id=\"genDate$i\" value=\"$nextConDue\"></td>";
		echo "<td><input style=\"text-align:right;\" type=\"text\" name=\"genMinPay$i\" id=\"genMinPay$i\" value=\"$conMinPay\"></td>";
		echo "</tr>";
	}
	?>
</table>
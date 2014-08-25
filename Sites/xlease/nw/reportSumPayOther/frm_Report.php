<?php
session_start();
include("../../config/config.php");
$TypeID=$_POST["TypeID"];
$year1=$_POST["year1"];
$y1=$year1+543;
$year2=$_POST["year2"];
$y2=$year2+543;
$nubyear=($year2-$year1)+1;  //ไว้สำหรับเป็น colspan ของ header

$query_type=pg_query("select \"TName\" from \"TypePay\" where \"TypeID\"='$TypeID'");
$res_type=pg_fetch_array($query_type);
$TName=$res_type["TName"];


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>รายงานสรุปรายได้อื่นๆ</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
</head>
<body>
<fieldset>
	<table width="100%" align="center" border="0" cellspacing="1" cellpadding="1">
	<tr>
		<td colspan="<?php echo $nubyear+1;?>" align="center">
			<div align="center"><h2>รายงานสรุปรายได้อื่นๆ</h2><h3><?php echo "( $TName )";?></h3></div>
			<div align="center"><h3>ตั้งแต่ปี พ.ศ.<?php echo $y1;?> ถึงปี พ.ศ.<?php echo $y2;?><h3></div>
		</td>
	</tr>
	<tr>
		<th rowspan="2" width="150" bgcolor="#0A8BC9"><font color="#FFFFFF">เดือน</font></th>
		<th colspan="<?php echo $nubyear;?>" bgcolor="#0A8BC9" height="25"><font color="#FFFFFF">รายได้/ปี พ.ศ.(บาท)</font></th>
	</tr>
	<tr height="25">
	<?php
		for($y=$y1;$y<=$y2;$y++){
			if($y%2 == 0){
				$color="#F4AB00";
			}else{
				$color="#FFD777";
			}
			echo "<td align=center bgcolor=$color><b>$y</b></td>";
		}
	?>
	</tr>
	<?php
		for($month=1;$month<=12;$month++){
			if($month <= "9"){
				$month="0".$month;
			}
			
			if($month=="01"){
				$txtmonth="มกราคม";
			}else if($month=="02"){
				$txtmonth="กุมภาพันธ์";
			}else if($month=="03"){
				$txtmonth="มีนาคม";
			}else if($month=="04"){
				$txtmonth="เมษายน";
			}else if($month=="05"){
				$txtmonth="พฤษภาคม";
			}else if($month=="06"){
				$txtmonth="มิถุนายน";
			}else if($month=="07"){
				$txtmonth="กรกฎาคม";
			}else if($month=="08"){
				$txtmonth="สิงหาคม";
			}else if($month=="09"){
				$txtmonth="กันยายน";
			}else if($month=="10"){
				$txtmonth="ตุลาคม";
			}else if($month=="11"){
				$txtmonth="พฤศจิกายน";
			}else if($month=="12"){
				$txtmonth="ธันวาคม";
			}
			echo "<tr>";
			echo "<td height=25 bgcolor=#DAF2FE>&nbsp;$txtmonth</td>"; //วนเดือน
			$sum_money=0;
			for($yy=$year1;$yy<=$year2;$yy++){
				$query=pg_query("select sum(\"O_MONEY\") as money from \"FOtherpay\" where \"O_Type\"='$TypeID' and (EXTRACT(MONTH FROM \"O_DATE\")='$month' AND EXTRACT(YEAR FROM \"O_DATE\")='$yy')");
				$num_sum=pg_num_rows($query);
				if($num_sum==0){
					$money=0;
				}else{
					$res_sum=pg_fetch_array($query);
					$money=$res_sum["money"];
				}
				$money=number_format($money,2);
				if($yy%2 == 0){
					$color2="#FFF3D7";
				}else{
					$color2="#FFD777";
				}
				echo "<td align=right bgcolor=$color2>$money</td>"; //วนปี
			}
			echo "</tr>";
		}
		echo "<tr><td align=right height=25 bgcolor=#0A8BC9><font color=#FFFFFF><b>รวม</b></font></td>";
		for($yy=$year1;$yy<=$year2;$yy++){
			$querysum=pg_query("select sum(\"O_MONEY\") as money2 from \"FOtherpay\" where \"O_Type\"='$TypeID' and  EXTRACT(YEAR FROM \"O_DATE\")='$yy'");
			$num_sum2=pg_num_rows($querysum);
			if($num_sum2==0){
				$money=0;
			}else{
				$res_sum2=pg_fetch_array($querysum);
				$money2=$res_sum2["money2"];
			}
			$money2=number_format($money2,2);
			if($yy%2 == 0){
				$color2="#FFD777";
			}else{
				$color2="#F4AB00";
			}
			echo "<td align=right bgcolor=$color2><b>$money2</b></td>"; //วนปี
		}
		echo "</tr>";

	?>	
	<tr bgcolor="#FFFFFF">
		<td height="50"><input type="button" value="กลับ" onclick="window.location='frm_Index.php'"></td>
		<td colspan="<?php echo $nubyear;?>" align="right" height="25">
			<div style="float:right;">
				<form method="post" name="form1" action="../../pChart/reportPayOther.php" target="_blank"> 
					<input type="hidden" name="year1" value="<?php echo $year1?>">
					<input type="hidden" name="year2" value="<?php echo $year2?>">
					<input type="hidden" name="TypeID" value="<?php echo $TypeID?>">
					<input type="submit" value="พิมพ์กราฟ">
				</form>
			</div>
			<div style="float:right;">
				<form method="post" name="form2" action="pdf_report.php" target="_blank"> 
					<input type="hidden" name="year1" value="<?php echo $year1?>">
					<input type="hidden" name="year2" value="<?php echo $year2?>">
					<input type="hidden" name="TypeID" value="<?php echo $TypeID?>">
					<input type="submit" value="พิมพ์รายงาน">
				</form>	
			</div>
		</td>
	</tr>	
	</table>
</fieldset> 
</body>
</html>
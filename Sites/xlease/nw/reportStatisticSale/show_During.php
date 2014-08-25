<?php
session_start();
include("../../config/config.php");
$y1 = $_POST["y1"];
$y1_1=$y1+543;

$y2 = $_POST["y2"];
$y2_1=$y2+543;

$m1 = $_POST["m1"];
$m2 = $_POST["m2"];

for($j=1;$j<=2;$j++){
	if($j==1){
		$month=$m1;
	}else{
		$month=$m2;
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
	if($j==1){
		$month1=$txtmonth;
	}else{
		$month2=$txtmonth;
	}
}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>รายงานสินเชื่อในช่วงปี</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>
			<div class="wrapper">
			<fieldset>
	<table width="100%">
	<tr><td>
		<div align="center"><h2>รายงานสินเชื่อในช่วงปี</h2></div>
		<div align="center"><h3>ปี พ.ศ.<?php echo $y1_1; ?>-<?php echo "$y2_1 (เดือน$month1 - $month2)";?></h3></div>
		<table width="600" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
		<tr align="center" bgcolor="#79BCFF">
			<th>ปี พ.ศ.</th>
			<th>ยอดสัญญารวม</th>
			<th>ยอดสินเชื่อรวม</th>
			<th>ยอดเฉลี่ยของสินเชื่อแต่ละสัญญา</th>
		</tr>
		<?php
		for($y=$y1;$y<=$y2;$y++){
			$txty=$y+543; //ปี พ.ศ.ที่ต้องการให้แสดง
			//ค้นหาข้อมูลในแต่ละปี
				$query=pg_query("select count(\"IDNO\") as numidno,sum(\"P_BEGIN\") as sumbeginx from \"Fp\" 
				where ((EXTRACT(MONTH FROM \"P_STDATE\")between '$m1' and '$m2') AND EXTRACT(YEAR FROM \"P_STDATE\")='$y')");
				$numrow=pg_num_rows($query);
				$sumidno=0;
				$sumbegin=0;
				while($result=pg_fetch_array($query)){
					$numidno=$result["numidno"]; //จำนวนสัญญา
					$beginx =$result["sumbeginx"]; //ยอดสินเชื่อ
					$sumbeginx=number_format($beginx,2);
								
					$sumidno = $sumidno+$numidno; //รวมจำนวนสัญญา
					$sumbegin = $sumbegin+$beginx; //รวมยอดสินเชื่อ		
				}
				if($numrow==0){
					$sumidno=0;
					$sumbegin=0;
				}
				if($j%2==0){
					echo "<tr class=\"odd\">";
				}else{
					echo "<tr class=\"even\">";
				}
				
				if($sumidno == 0){
					$avg="0.00";
				}else{
					$avg=number_format(($sumbegin/$sumidno),2);
				}
				echo "<td height=25 align=center><b>&nbsp;$txty</b></td>";
				echo "<td align=center>$sumidno</td>";
				$sumbeginx=number_format($sumbegin,2);
				echo "<td align=right>$sumbeginx</td>";
				echo "<td align=right>$avg</td>";
				echo "</tr>";
			$allidno=$allidno+$sumidno;
			$allbegin=$allbegin+$sumbegin;
		}
		if($allidno==0){
			$allavg="0.00";
		}else{
			$allavg=$allbegin/$allidno;
		}
		?>	
		<tr height="30" bgcolor="#FFCCFF" align="right"><td><b>รวม</b></td><td align="center"><b><?php echo number_format($allidno);?></b></td><td><b><?php echo number_format($allbegin,2);?></b></td><td><b><?php echo number_format($allavg,2);?></td></tr>	
		<tr bgcolor="#FFFFFF">
			<td height="25" ><input type="button" value="กลับ" onclick="window.location='frm_DuringYear.php'"></td>
			<td colspan="3" align="right" height="25">
				<div style="float:right;">
					<form method="post" name="form1" action="../../pChart/reportDuring.php" target="_blank"> 
						<input type="hidden" name="y1" value="<?php echo $y1?>">
						<input type="hidden" name="y2" value="<?php echo $y2?>">
						<input type="hidden" name="m1" value="<?php echo $m1?>">
						<input type="hidden" name="m2" value="<?php echo $m2?>">
						<input type="submit" value="พิมพ์กราฟ">
					</form>
				</div>
				<div style="float:right;">
					<form method="post" name="form2" action="pdf_During.php" target="_blank"> 
						<input type="hidden" name="y1" value="<?php echo $y1?>">
						<input type="hidden" name="y2" value="<?php echo $y2?>">
						<input type="hidden" name="m1" value="<?php echo $m1?>">
						<input type="hidden" name="m2" value="<?php echo $m2?>">
						<input type="submit" value="พิมพ์รายงาน">
					</form>	
				</div>
			</td>
		</tr>	
		</table>
	</td></tr>
	</table>
</fieldset> 
			</div>
        </td>
    </tr>
</table>    
</body>
</html>
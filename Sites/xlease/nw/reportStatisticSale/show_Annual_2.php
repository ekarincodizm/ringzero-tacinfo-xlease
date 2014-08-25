<?php
$y1=$_POST["y1"];
$y1_1=$y1+543;
$y2=$_POST["y2"];
$y2_1=$y2+543;
?>
<fieldset>
	<table width="100%" align="center" border="0">
	<tr><td colspan="2" align="center">
		<div align="center"><h2>รายงานสินเชื่อประจำปี</h2></div>
		<div align="center"><h3>เปรียบเทียบระหว่างปี พ.ศ.<?php echo $y1_1;?> และ ปี พ.ศ.<?php echo $y2_1;?><h3></div>
	</td></tr>
	<tr>
	<?php
	for($j=1;$j<=2;$j++){
		if($j==1){
			$year=$y1;
		}else{
			$year=$y2;
		}
		$txty=$year+543;
		
		if($j==1){
			$cmain="#79BCFF";
			$color="#EDF8FE";
			$color1="#D5EFFD";
		}else{
			$cmain="#0ED394";
			$color="#E1FDF4";
			$color1="#B4FAE4";
		}
	?>
	<td>
		<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
		<tr bgcolor="#FFFFFF" height="25">
			<td colspan="4"><b>ปี พ.ศ.<?php echo $txty;?></b></td>
		</tr>
		<tr align="center" bgcolor="<?php echo $cmain?>">
			<th>เดือน</th>
			<th>จำนวนสัญญา</th>
			<th>ยอดสินเชื่อ</th>
			<th>ยอดสินเชื่อเฉลี่ยต่อสัญญา</th>
		</tr>
		<?php
		$allidno=0;
		$allbegin=0;
		for($i=1;$i<=12;$i++){
			if($i < 10){
				$month="0".$i;
			}else{
				$month=$i;
			}				
			$query=pg_query("select count(\"IDNO\") as numidno,sum(\"P_BEGIN\") as sumbeginx from \"Fp\" 
			where (EXTRACT(MONTH FROM \"P_STDATE\")='$month' AND EXTRACT(YEAR FROM \"P_STDATE\")='$year')");
			
			$sumidno=0;
			$sumbegin=0;
								
			while($result=pg_fetch_array($query)){
				$numidno=$result["numidno"]; //จำนวนสัญญา
				$beginx =$result["sumbeginx"]; //ยอดสินเชื่อ
				$sumbeginx=number_format($beginx,2);
								
				$sumidno = $sumidno+$numidno; //รวมจำนวนสัญญา
				$sumbegin = $sumbegin+$beginx; //รวมยอดสินเชื่อ		
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
			
			if($sumidno == 0){
				$avg="0.00";
			}else{
				$avg=number_format(($sumbegin/$sumidno),2);
			}
			
			if($i%2==0){
				echo "<tr bgcolor=$color>";
			}else{
				echo "<tr bgcolor=$color1>";
			}
			
			echo "<td height=25><b>&nbsp;$txtmonth</b></td>";
			echo "<td align=center>$sumidno</td>";
			$sumbeginx=number_format($sumbegin,2);
			echo "<td align=right>$sumbeginx</td>";
			echo "<td align=right>$avg</td>";
			echo "</tr>";
			
			$allidno=$allidno+$sumidno;
			$allbegin=$allbegin+$sumbegin;
		} //จบลูป for   
		if($allidno==0){
			$allavg="0.00";
		}else{
			$allavg=$allbegin/$allidno;
		}
		?>	
		<tr height="30" bgcolor="<?php echo $cmain;?>" align="right"><td><b>รวม</b></td><td align="center"><b><?php echo number_format($allidno);?></b></td><td><b><?php echo number_format($allbegin,2);?></b></td><td><b><?php echo number_format($allavg,2);?></td></tr>	
		</table>
	</td>
	<?php 
	} //end for
	?>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td height="25" ><input type="button" value="กลับ" onclick="window.location='frm_Annual.php?condition=2'"></td>
		<td colspan="3" align="right" height="25">
			<div style="float:right;">
				<form method="post" name="form1" action="../../pChart/reportAnnual2.php" target="_blank"> 
					<input type="hidden" name="y1" value="<?php echo $y1?>">
					<input type="hidden" name="y2" value="<?php echo $y2?>">
					<input type="submit" value="พิมพ์กราฟ">
				</form>
			</div>
			<div style="float:right;">
				<form method="post" name="form2" action="pdf_annual2.php" target="_blank"> 
					<input type="hidden" name="y1" value="<?php echo $y1?>">
					<input type="hidden" name="y2" value="<?php echo $y2?>">
					<input type="submit" value="พิมพ์รายงาน">
				</form>	
			</div>
		</td>
	</tr>	
	</table>
</fieldset> 

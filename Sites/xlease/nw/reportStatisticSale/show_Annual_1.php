<?php
$year=$_POST["year"];
$year2=$year+543;
?>
<fieldset>
	<table width="100%">
	<tr><td>
		<div align="center"><h2>รายงานสินเชื่อประจำปี</h2></div>
		<div align="center"><h3>ประจำปี พ.ศ.<?php echo $year2; ?></h3></div>
		<table width="600" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
		<tr align="center" bgcolor="#79BCFF">
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
				echo "<tr class=\"odd\">";
			}else{
				echo "<tr class=\"even\">";
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
		<tr height="30" bgcolor="#FFCCFF" align="right"><td><b>รวม</b></td><td align="center"><b><?php echo number_format($allidno);?></b></td><td><b><?php echo number_format($allbegin,2);?></b></td><td><b><?php echo number_format($allavg,2);?></td></tr>	
		<tr bgcolor="#FFFFFF">
			<td height="25" ><input type="button" value="กลับ" onclick="window.location='frm_Annual.php?condition=1'"></td>
			<td colspan="3" align="right" height="25">
				<div style="float:right;">
					<form method="post" name="form1" action="../../pChart/reportAnnual1.php" target="_blank"> 
						<input type="hidden" name="year" value="<?php echo $year?>">
						<input type="submit" value="พิมพ์กราฟ">
					</form>
				</div>
				<div style="float:right;">
					<form method="post" name="form2" action="pdf_annual1.php" target="_blank"> 
						<input type="hidden" name="year" value="<?php echo $year?>">
						<input type="submit" value="พิมพ์รายงาน">
					</form>	
				</div>
			</td>
		</tr>	
		</table>
	</td></tr>
	</table>
</fieldset> 

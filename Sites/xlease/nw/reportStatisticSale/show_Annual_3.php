<?php
$year1 = $_POST["year1"];
$year1_1=$year1+543;

$year2 = $_POST["year2"];
$year2_1=$year2+543;
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>
			<div class="wrapper">
			<fieldset>
	<table width="100%">
	<tr><td>
		<div align="center"><h2>รายงานสินเชื่อเปรียบเทียบหลายปี</h2></div>
		<div align="center"><h3>ระหว่างปี พ.ศ.<?php echo $year1_1; ?>-<?php echo "$year2_1 (เดือนมกราคม - ธันวาคม)";?></h3></div>
		<table width="600" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
		<tr align="center" bgcolor="#79BCFF">
			<th>ปี พ.ศ.</th>
			<th>ยอดสัญญารวม</th>
			<th>ยอดสินเชื่อรวม</th>
			<th>ยอดเฉลี่ยของสินเชื่อแต่ละสัญญา</th>
		</tr>
		<?php
		for($y=$year1;$y<=$year2;$y++){
			$txty=$y+543; //ปี พ.ศ.ที่ต้องการให้แสดง
			//ค้นหาข้อมูลในแต่ละปี
				$query=pg_query("select count(\"IDNO\") as numidno,sum(\"P_BEGIN\") as sumbeginx from \"Fp\" 
				where ((EXTRACT(MONTH FROM \"P_STDATE\")between '01' and '12') AND EXTRACT(YEAR FROM \"P_STDATE\")='$y')");
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
			<td height="25" ><input type="button" value="กลับ" onclick="window.location='frm_Annual.php?condition=3'"></td>
			<td colspan="3" align="right" height="25">
				<div style="float:right;">
					<form method="post" name="form1" action="../../pChart/reportAnnual3.php" target="_blank"> 
						<input type="hidden" name="year1" value="<?php echo $year1?>">
						<input type="hidden" name="year2" value="<?php echo $year2?>">
						<input type="submit" value="พิมพ์กราฟ">
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

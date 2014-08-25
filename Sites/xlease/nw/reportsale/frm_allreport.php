<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>

<fieldset>
<table width="800">
<tr><td>
<div align="center"><h2>รายงานพนักงานขายแบบรวม</h2></div>
<div align="center"><h3><?php echo $txtcon.$txtmonth;?>&nbsp;ค.ศ. <?php echo $year;?></h3></div>
<table width="600" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
	<tr align="center" bgcolor="#79BCFF">
		<th>รหัสพนักงาน</th>
		<th>ชื่อพนักงาน</th>
		<th>จำนวนสัญญาที่ทำได้</th>
		<th>ยอดสินเชื่อที่ปล่อย</th>
	</tr>
	<?php
		$query=pg_query("select a.\"id_user\",b.\"fullname\",count(a.\"IDNO\") as numidno,sum(c.\"P_BEGIN\") as sumbeginx from \"nw_startDateFp\" a
		left join \"Vfuser\" b on a.\"id_user\" = b.\"id_user\"
		left join \"Fp\" c on a.\"IDNO\" = c.\"IDNO\" where EXTRACT(YEAR FROM a.\"startDate\")='$year' $conmonth
		group by a.\"id_user\",b.\"fullname\" order by a.\"id_user\"");

		$numrows=pg_num_rows($query);
		$sumidno=0;
		$sumbegin=0;
		$i=0;
		while($result=pg_fetch_array($query)){
			$id_user=$result["id_user"];
			$fullname=$result["fullname"];
			$numidno=$result["numidno"];
			$beginx =$result["sumbeginx"];
			$sumbeginx=number_format($beginx,2);
		
			if($i%2==0){
				echo "<tr class=\"odd\">";
			}else{
				echo "<tr class=\"even\">";
			}
			echo "<td align=center height=25>$id_user</td>";
			echo "<td>$fullname</td>";
			echo "<td align=center><b><u><a href=\"#\" onclick=\"javascript:popU('frm_onlyreport.php?id_user=$id_user&month=$month&year=$year&SelectChart=$SelectChart','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=990,height=700')\">$numidno</a></u></b></td>";
			echo "<td align=right>$sumbeginx</td>";
			echo "</tr>";
			
			$sumidno = $sumidno+$numidno;
			$sumbegin = $sumbegin+$beginx;
			$i++;
		}
		if($numrows==0){
			echo "<tr bgcolor=#FFFFFF height=50><td colspan=4 align=center>ไม่มีข้อมูล</td></tr>";
		}else{
	?>
		<tr bgcolor="#FFCCFF">
			<td colspan="2" align="right" height="25" ><b>รวม</b></td>
			<td  align="center"><b><?php echo "<b><u><a href=\"#\" onclick=\"javascript:popU('frm_allreport_select_all.php?month=$month&year=$year&txtmonth=$txtmonth&SelectChart=$SelectChart','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=980,height=600')\">$sumidno</a></u></b>";?></b></td>
			<td align="right"><b><?php echo number_format($sumbegin,2);?></b></td>
		</tr>
		
		<?php }?>
		
		<tr bgcolor="#FFFFFF">
			<td height="25" ><input type="button" value="กลับ" onclick="window.location='frm_Index.php'"></td>
			<td colspan="3" align="right" height="25">
				<div style="float:right;">
				<?php
				if($SelectChart=="a1"){
					$txtaction="../../pChart/reportsale2.php";
				}else{
					$txtaction="../../pChart/reportsale.php";
				}
				?>
				
				<form method="post" name="form1" action="<?php echo $txtaction;?>" target="_blank"> 
					<input type="hidden" name="month" value="<?php echo $month?>">
					<input type="hidden" name="year" value="<?php echo $year?>">
					<input type="hidden" name="txtmonth" value="<?php echo $txtmonth?>">
					<input type="hidden" name="txtcon" value="<?php echo $txtcon;?>">
					<input type="hidden" name="SelectChart" value="<?php echo $SelectChart;?>">
					<input type="hidden" name="conmonth" value="<?php echo $month;?>">
					<input type="submit" value="พิมพ์กราฟ" <?php if($numrows==0){?> disabled <?php }?>>
				</form>
				</div>
				<div style="float:right;">
				<form method="post" name="form2" action="pdf_allreport.php" target="_blank"> 
				<input type="hidden" name="month" value="<?php echo $month?>">
				<input type="hidden" name="year" value="<?php echo $year?>">
				<input type="hidden" name="txtmonth" value="<?php echo $txtmonth?>">
				<input type="hidden" name="txtcon" value="<?php echo $txtcon;?>">
				<input type="hidden" name="SelectChart" value="<?php echo $SelectChart;?>">
				<input type="submit" value="พิมพ์รายงาน" <?php if($numrows==0){?> disabled <?php }?>>
				</form>	
				</div>
			</td>
		</tr>
		
</table>
</td>
</tr>
</table>
<br>
</fieldset> 
			
						
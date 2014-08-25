<?php
include("../../config/config.php");

$month = pg_escape_string($_GET['month']);
$year = pg_escape_string($_GET['year']);
$SelectChart= pg_escape_string($_GET['SelectChart']);
if($SelectChart=="a1"){
	$conmonth="";
	$txtcon="ประจำเดือนมกราคม-ธันวาคม";
}else{
	$conmonth="AND EXTRACT(MONTH FROM \"nw_startDateFp\".\"startDate\")='$month' ";
	$txtcon="ประจำเดือน";
	$txtmonth = pg_escape_string($_GET["txtmonth"]);
}



?>
<html>
<head>
<script language=javascript>
function popU(U,N,T){
    wnd = window.open(U, N, T);
}
</script>
<!-- <link type="text/css" rel="stylesheet" href="act.css"></link> -->
</head>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<fieldset>
<table width="900">
<tr><td>
<div align="center"><h2>รายงานพนักงานขายแบบรวม</h2></div>
<div align="center"><h3><?php echo "$txtcon$txtmonth ค.ศ. $year"?></h3></div>
<table width="880" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
	<tr align="center" bgcolor="#79BCFF">
		<th>รหัสพนักงาน</th>
		<th>ชื่อพนักงาน</th>
		<th>เลขที่สัญญาที่ทำ</th>
		<th>วันที่ทำสัญญา</th>
		<th>ยอดสินเชื่อที่ปล่อย</th>
		<th>สถานะ</th>
	</tr>
	<?php
		$query=pg_query("select \"nw_startDateFp\".\"id_user\" , \"nw_startDateFp\".\"IDNO\" , \"Fp\".\"P_STDATE\" , \"Fp\".\"P_BEGIN\" , \"Fp\".\"P_ACCLOSE\" , 
		\"Fp\".\"P_CLDATE\",\"Vfuser\".\"fullname\"
						from \"nw_startDateFp\" , \"Vfuser\" , \"Fp\"
						where \"nw_startDateFp\".\"id_user\" = \"Vfuser\".\"id_user\"
							and \"nw_startDateFp\".\"IDNO\" = \"Fp\".\"IDNO\"
							and (EXTRACT(YEAR FROM \"nw_startDateFp\".\"startDate\")='$year' $conmonth)
						order by \"nw_startDateFp\".\"id_user\" , \"nw_startDateFp\".\"IDNO\"
						");
		$numrows=pg_num_rows($query);
		$sumbegin=0;
		$i=0;
		$sumclose = 0;
		$summaryone = 0;
		$sumtwo = 0;
		$sumthree = 0;
		$summormal = 0;
		while($result=pg_fetch_array($query)){
			$id_user=$result["id_user"];
			$fullname=$result["fullname"];
			$IDNO=$result["IDNO"];
			$P_STDATE=$result["P_STDATE"];
			$beginx =$result["P_BEGIN"];
			$sumbeginx=number_format($beginx,2);
			$P_ACCLOSE = trim($result["P_ACCLOSE"]);
			$P_CLDATE = trim($result["P_CLDATE"]);	
			
			
			$qry_behind = pg_query("SELECT xls_get_backduenum('$IDNO',1)");
			list($state) = pg_fetch_array($qry_behind);
			$qry_behind = pg_query("SELECT xls_get_backduenum('$IDNO')");
			list($codestate) = pg_fetch_array($qry_behind);
			
			if($codestate == '00'){ $sumclose++ ;}
			else if($codestate == '1'){ $summaryone++ ; }
			else if($codestate == '2'){ $sumtwo++ ; }
			else if($codestate >= '3'){ $sumthree++ ; }
			else{ $sumnormal++ ; }
			
			
			//------- เช็ีคว่าใช่คนเดิมหรือไม่
			$checkIDone = $id_user;
			if($i==0)
			{
				$checkIDtwo = $checkIDone;
			}
			else
			{
				if($checkIDone != $checkIDtwo)
				{
					echo "<td colspan=\"4\" align=\"right\" height=\"25\" ><b>รวม</b></td>";
					echo "<td align=\"right\"><b>".number_format($sumone,2)."</b></td>";
					$checkIDtwo = $checkIDone;
					$sumone = 0;
				}
			}
			//------- จบการเช็คคนเดิม
			
			if($i%2==0){
				echo "<tr bgcolor=\"#EDF8FE\">";
			}else{
				echo "<tr bgcolor=\"#D5EFFD\">";
			}
			echo "<td align=center>$id_user</td>";
			echo "<td align=left>$fullname</td>";
			echo "<td align=center><a onclick=\"javascript:popU('../../post/frm_viewcuspayment.php?idno_names=$IDNO','toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no,width=800,height=600')\" style=\"cursor:pointer\"><u>$IDNO</u></a></td>";
			echo "<td align=center>$P_STDATE</td>";
			echo "<td align=right>".number_format($beginx,2)."</td>";
			echo "<td align=center>$state</td>";
			echo "</tr>";
			
			$sumone = $sumone+$beginx;
			$sumbegin = $sumbegin+$beginx;
			$i++;
			
			if($i == $numrows)
			{
				echo "<td colspan=\"4\" align=\"right\" height=\"25\" ><b>รวม</b></td>";
				echo "<td align=\"right\"><b>".number_format($sumone,2)."</b></td>";
			}
		}
		if($numrows==0){
			echo "<tr bgcolor=#FFFFFF height=50><td colspan=5 align=center>ไม่มีข้อมูล</td></tr>";
		}else{
	?>
		<tr bgcolor="#FFCCFF">
			<td colspan="4" align="right" height="25" ><b>รวมทั้งหมด</b></td>
			<td align="right"><b><?php echo number_format($sumbegin,2);?></b></td>
			<td></td>
		</tr>
		<tr>
			<td colspan="4">จำนวนสัญญาทั้งหมด: <b><?php echo number_format($numrows); ?></b></td>
		</tr>
		<tr>
			<td colspan="4">จำนวนสัญญาที่มีสถานะเป็นปกติ: <b><?php echo number_format($sumnormal); ?></b></td>
		</tr>
		<tr>
			<td colspan="4">จำนวนสัญญาที่มีสถานะค้าง  1 งวด: <b><?php echo number_format($summaryone); ?></b></td>
		</tr>
		<tr>
			<td colspan="4">จำนวนสัญญาที่มีสถานะค้าง  2 งวด: <b><?php echo number_format($sumtwo); ?></b></td>
		</tr>
		<tr>
			<td colspan="4">จำนวนสัญญาที่มีสถานะค้าง  3 งวดขึ้นไป: <b><?php echo number_format($sumthree); ?></b></td>
		</tr>
		<tr>
			<td colspan="4">จำนวนสัญญาที่ปิดบัญชีแล้ว: <b><?php echo number_format($sumclose); ?></b></td>
		</tr>
		
		<?php }?>
		
		<tr bgcolor="#FFFFFF">
			<td height="25" ><input type="button" value="CLOSE" onclick="javascript:window.close();"></td>
			<td colspan="5" align="right" height="25">
				<div style="float:right;">
				<form method="post" name="form2" action="pdf_allreport_select_all.php" target="_blank"> 
				<input type="hidden" name="month" value="<?php echo $month?>">
				<input type="hidden" name="year" value="<?php echo $year?>">
				<input type="hidden" name="txtmonth" value="<?php echo $txtmonth?>">
				<input type="hidden" name="SelectChart" value="<?php echo $SelectChart?>">
				<input type="hidden" name="id_user" value="<?php echo $id_user?>">
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
</html>
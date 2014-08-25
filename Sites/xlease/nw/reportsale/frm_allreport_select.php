<?php
include("../../config/config.php");

$id_user = $_GET['id_user'];
$month = $_GET['month'];
$year = $_GET['year'];
$SelectChart=$_GET['SelectChart'];
if($SelectChart=="a1"){
	$conmonth="";
	$txtcon="ประจำเดือนมกราคม-ธันวาคม";
}else{
	$conmonth="AND EXTRACT(MONTH FROM \"nw_startDateFp\".\"startDate\")='$month' ";
	$txtcon="ประจำเดือน";
	$txtmonth = $_POST["txtmonth"];
}


$query_name=pg_query("select \"Vfuser\".\"fullname\"
						from \"nw_startDateFp\" , \"Vfuser\"
						where \"nw_startDateFp\".\"id_user\" = \"Vfuser\".\"id_user\"
							and \"Vfuser\".\"id_user\" = '$id_user'
						");
while($result=pg_fetch_array($query_name)){
			$fullname=$result["fullname"];
			}
			
		
			
?>
<html>
<head>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script language=javascript>
var wnd = new Array();
function popU(U,N,T){
    wnd[N] = window.open(U, N, T);
}
</script>
</head>

<fieldset>
<table width="640">
<tr><td>
<div align="center"><h2>รายงานพนักงานขาย <?php echo " $fullname"; ?></h2></div>
<div align="center"><h3><?php echo "$txtcon$txtmonth ค.ศ. $year"?></h3></div>
<table width="600" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
	<tr align="center" bgcolor="#79BCFF">
		<th>เลขที่สัญญาที่ทำได้</th>
		<th>วันที่ทำสัญญา</th>
		<th>ยอดสินเชื่อที่ปล่อย</th>
		<th>สถานะ</th>
	</tr>
	<?php
		$query=pg_query("select \"nw_startDateFp\".\"IDNO\" , \"Fp\".\"P_STDATE\" , \"Fp\".\"P_BEGIN\" , \"Fp\".\"P_ACCLOSE\" , \"Fp\".\"P_CLDATE\"
						from \"nw_startDateFp\" , \"Vfuser\" , \"Fp\"
						where \"nw_startDateFp\".\"id_user\" = \"Vfuser\".\"id_user\"
							and \"nw_startDateFp\".\"IDNO\" = \"Fp\".\"IDNO\"
							and \"nw_startDateFp\".\"id_user\" = '$id_user'
							and (EXTRACT(YEAR FROM \"nw_startDateFp\".\"startDate\")='$year' $conmonth)
						order by \"nw_startDateFp\".\"IDNO\"
						");
		$numrows=pg_num_rows($query);
		$sumbegin=0;
		$i=0;
		while($result=pg_fetch_array($query)){
			$IDNO=$result["IDNO"];
			$P_STDATE=$result["P_STDATE"];
			$beginx =$result["P_BEGIN"];
			$P_ACCLOSE = trim($result["P_ACCLOSE"]);
			$P_CLDATE = trim($result["P_CLDATE"]);
			
			$sumbeginx=number_format($beginx,2);
		
			$qry_behind = pg_query("SELECT xls_get_backduenum('$IDNO',1)");
			list($re_behind) = pg_fetch_array($qry_behind);
			$str_dis = array("{", "}", "\"");
			$re_behind = str_replace($str_dis,"",$re_behind);
			list($state,$codestate) = explode(",",$re_behind);
			//list($state,$colorstate) = behindhand($IDNO,$P_ACCLOSE,$P_CLDATE,$P_STDATE);
		
		
			if($i%2==0){
				echo "<tr bgcolor=\"#EDF8FE\">";
			}else{
				echo "<tr bgcolor=\"#D5EFFD\">";
			}
			echo "<td align=center><a onclick=\"javascript:popU('../../post/frm_viewcuspayment.php?idno_names=$IDNO','toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no,width=800,height=600')\" style=\"cursor:pointer\"><u>$IDNO</u></a></td>";
			echo "<td align=center>$P_STDATE</td>";
			echo "<td align=right>".number_format($beginx,2)."</td>";
			echo "<td align=center>$state</td>";
			echo "</tr>";
			
			$sumbegin = $sumbegin+$beginx;
			$i++;
		}
		if($numrows==0){
			echo "<tr bgcolor=#FFFFFF height=50><td colspan=4 align=center>ไม่มีข้อมูล</td></tr>";
		}else{
	?>
		<tr bgcolor="#FFCCFF">
			<td colspan="2" align="right" height="25" ><b>รวม</b></td>
			<td align="right"><b><?php echo number_format($sumbegin,2);?></b></td>
			<td></td>
		</tr>
		
		<?php }?>
		
		<tr bgcolor="#FFFFFF">
			<td height="25" ><input type="button" value="CLOSE" onclick="javascript:window.close();"></td>
			<td colspan="3" align="right" height="25">
				<div style="float:right;">
				<form method="post" name="form2" action="pdf_onlyreport.php" target="_blank"> 
				<input type="hidden" name="month" value="<?php echo $month?>">
				<input type="hidden" name="year" value="<?php echo $year?>">
				<input type="hidden" name="txtmonth" value="<?php echo $txtmonth?>">
				<input type="hidden" name="SelectChart" value="<?php echo $SelectChart?>">
				<input type="hidden" name="id_user" value="<?php echo $id_user?>">
				<input type="hidden" name="name" value="<?php echo $fullname?>">
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
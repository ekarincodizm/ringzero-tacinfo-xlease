<?php
include("../../config/config.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
    <title>อนุมัติยกเลิกงานระหว่างยึด</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
</head>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
function checkhitorityappv(no){
	var checkhitorityappv = $.ajax({    
	  url: "frm_checkhitorityappv.php?idno="+no, 
	  async: false  
	}).responseText;
	popU('../thcap_cost_type_appv/frm_appv.php?autoid='+no+'&last_autoid='+checkhitorityappv,
	'','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1200,height=900');
	
}
</script>

<body>
<center><h2>อนุมัติยกเลิกงานระหว่างยึด</h2></center>
<br>
<fieldset>
	<legend><font color="black"><b>รายการที่อยู่ระหว่างรออนุมัติ</font></b></font></legend>
<br>
<table  align="center" width="90%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#79BCFF">
		<th>รายการที่</th>
		<th>เลขที่ NT</th>
		<th>เลขที่สัญญา</th>
		<th>ชื่อ</th>
        <th>ทะเบียน</th>
		<th>ผู้ทำรายการ</th>		
		<th>วันที่ทำรายการ</th>
		<th>ทำรายการ</th>
		
	</tr>
	<?php  

	$query = pg_query("select \"auto_id\",\"IDNO\",\"NTID\",\"doerid\",\"doerstamp\"   from \"nw_seize_cancel_btseizure\" where \"status\"='9' order by \"auto_id\" asc");
	$i=0;
	$numrows = pg_num_rows($query);
	while($result = pg_fetch_array($query))
	{
		$i++;
		$auto_id=$result['auto_id'];
		$IDNO=$result['IDNO'];
		$NTID=$result['NTID'];
		$doerid=$result['doerid'];
		$doerstamp=$result['doerstamp'];
		
		$qry_doer = pg_query("select fullname from \"Vfuser\" where id_user = '$doerid' ");
		$doername = pg_fetch_result($qry_doer,0);
		
		$qry_vc=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$IDNO' ");
		if($res_vc=pg_fetch_array($qry_vc)){
			$full_name = $res_vc["full_name"];			
			$asset_type = $res_vc["asset_type"];
			$C_REGIS = $res_vc["C_REGIS"];
			$car_regis = $res_vc["car_regis"];
			if($asset_type == 1) $show_regis = $C_REGIS; else $show_regis = $car_regis;
		}
		if($i%2==0){
					echo "<tr bgcolor=\"#B2DFEE\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#B2DFEE';\">";
				}else{
					echo "<tr bgcolor=\"#BFEFFF\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#BFEFFF';\">";
					}
		echo "<td align=\"center\">$i</td>"; ?>
		<td align="center">
		<span onclick="javascript:popU('../../post/notice_reprint_pdf.php?idno=<?php echo $IDNO; ?>&ntid=<?php echo $NTID;?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" style="cursor: pointer;"><font color=blue><u><?php echo $NTID; ?></u></font></span>        
		</td>
		<td align="center">
		<span onclick="javascript:popU('../../post/frm_viewcuspayment.php?idno_names=<?php echo $IDNO; ?>&type=outstanding','<?php echo "$IDNO_outstanding"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" style="cursor: pointer;" title="ดูตารางการชำระ"><font color=blue><u><?php echo $IDNO; ?></u></font></span>        
		</td>	
		<?php 
		echo "<td align=\"left\">$full_name</td>";
		echo "<td align=\"center\">$show_regis</td>";
		echo "<td align=\"center\">$doername</td>";
		echo "<td align=\"center\">$doerstamp</td>";		
		echo "<td align=\"center\"><span onclick=\"javascript:popU('../seize_car/frm_cancel.php?autoid=$auto_id&method=edit_btseizure','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=700,height=600')\" style=\"cursor: pointer;\" ><u>ทำรายการ</u></span></td>";
		
		
	}
	if($numrows==0){
			echo "<tr bgcolor=#FFFFFF height=50><td colspan=8 align=center><b>ไม่พบรายการ</b></td><tr>";
		}else{
			echo "<tr bgcolor=\"#79BCFF\" height=25><td colspan=8><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
	}
	?>
	
	
</table>
</fieldset>
<?php 
//ประวัติการอนุมัติ 30 รายการล่าสุด
include('frm_history_limit.php');
?>
</div>
</body>
</html>
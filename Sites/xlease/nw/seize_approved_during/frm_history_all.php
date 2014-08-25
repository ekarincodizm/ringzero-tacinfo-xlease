<?php
include("../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
set_time_limit(60);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>ประวัติการอนุมัติอนุมัติยกเลิกงานระหว่างยึด</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
	<link type="text/css" rel="stylesheet" href="act.css"></link>

	<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
	<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>
	<center>
	<h1>ประวัติการอนุมัติอนุมัติยกเลิกงานระหว่างยึด</h1>
	<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
		<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
			<th>รายการที่</th>
			<th>เลขที่ NT</th>
			<th>เลขที่สัญญา</th>
			<th>ชื่อ</th>
			<th>ทะเบียน</th>
			<th>ผู้มอบอำนาจ</th>		
			<th>ผู้รับมอบอำนาจ</th>
			<th>พยานคนที่ 1</th>		
			<th>พยานคนที่ 2</th>
			<th>ผู้ทำการอนุมัติ</th>		
			<th>วันที่ทำการอนุมัติ</th>			
			<th>ผลการอนุมัติ</th>
			</tr>
		<?php
		$query = pg_query("select *   from \"nw_seize_cancel_btseizure\" where \"status\" <> '9' order by \"approvestamp\" desc ");
		$i=0;
		$numrows = pg_num_rows($query);
		while($result = pg_fetch_array($query))
		{
			$i++;
			$auto_id=$result['auto_id'];
			$IDNO=$result['IDNO'];
			$NTID=$result['NTID'];
			$approveid=$result['approveid'];
			$approvestamp=$result['approvestamp'];
			$seize_user=$result['seize_user'];   
			$authorize_user=$result['authorize_user'];
			$witness_user1=$result['witness_user1'];
			$witness_user2=$result['witness_user2'];  
			$status=$result['status'];  

			$qry_seize_user = pg_query("select fullname from \"Vfuser\" where id_user = '$seize_user' ");
			$seize_user = pg_fetch_result($qry_seize_user,0);
			
			$qry_authorize_user= pg_query("select fullname from \"Vfuser\" where id_user = '$authorize_user' ");
			$authorize_user = pg_fetch_result($qry_authorize_user,0);
		
			$qry_witness_user1 = pg_query("select fullname from \"Vfuser\" where id_user = '$witness_user1' ");
			$witness_user1 = pg_fetch_result($qry_witness_user1,0);
			
			$qry_witness_user2 = pg_query("select fullname from \"Vfuser\" where id_user = '$witness_user2' ");
			$witness_user2 = pg_fetch_result($qry_witness_user2,0);
			
			$qry_approve = pg_query("select fullname from \"Vfuser\" where id_user = '$approveid' ");
			$approvename = pg_fetch_result($qry_approve,0);
			
			if($status=='1'){
				$status="อนุมัติ";			
			}
			else if($status=='0'){
				$status="ไม่อนุมัติ";		
			}
		
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
			echo "<td align=\"center\">$authorize_user</td>";
			echo "<td align=\"center\">$seize_user</td>";	
			echo "<td align=\"center\">$witness_user1</td>";	
			echo "<td align=\"center\">$witness_user2</td>";	
			echo "<td align=\"center\">$approvename</td>";	
			echo "<td align=\"center\">$approvestamp</td>";
			echo "<td align=\"center\">$status</td>";
		}
		if($numrows == 0)
		{
			echo "<tr><td colspan=12 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
		}
		?>
	</table>
	</center>
</body>
</html>
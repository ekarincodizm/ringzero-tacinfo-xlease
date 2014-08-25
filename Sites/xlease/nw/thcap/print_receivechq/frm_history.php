<?php
if($limit==""){
	include("../../../config/config.php");
	$txthead="ประวัติการพิมพ์ใบรับเช็คทั้งหมด";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>ประวัติการพิมพ์ใบรับเช็ค</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
	<link type="text/css" rel="stylesheet" href="../act.css"></link>
<script language="javascript">
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
</head>
<!--แสดงประวัติการอนุมัติ -->
<table width="100%" cellSpacing="1" cellPadding="3" border="0" bgcolor="#F4F4F4" align="center">
<tr bgcolor="#FFFFFF">
	<td colspan="11" align="left" style="font-weight:bold;"><?php echo $txthead;?> <?php if($limit=="limit 30"){?>(<a style="color:#0099FF;cursor:pointer;" onclick="javascript:popU('frm_history.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=650')"><u>ทั้งหมด</u></a>)<?php } ?></td>
</tr>
<tr style="font-weight:bold;" valign="middle" bgcolor="#D4D4D4" align="center">
	<th>เลขที่ใบรับเช็ค</th>
	<th>เลขที่สัญญา</th>
	<th>วันที่รับเช็ค</th>
	<th>ผู้พิมพ์</th>
	<th>วันเวลาที่พิมพ์</th>
	<th>เหตุผลที่พิมพ์</th>
</tr>


<?php
	$qry=pg_query("select min(auto_id) as id, a.\"revChqNum\",\"revChqToCCID\",date(\"revChqDate\") as \"revChqDate\",\"fullname\",\"print_stamp\" from finance.thcap_receive_cheque_print_log a
	left join finance.\"V_thcap_receive_cheque_chqManage\" b on a.\"revChqNum\"=b.\"revChqNum\"
	left join \"Vfuser\" c on a.\"print_user\"=c.\"id_user\"
	group by a.\"revChqNum\",\"revChqToCCID\",date(\"revChqDate\"),\"fullname\",\"print_stamp\"
	order by \"print_stamp\" desc $limit");
	$numrows=pg_num_rows($qry);
	$i=0;
	while($result=pg_fetch_array($qry)){
		$id=$result["id"];
		$revChqNum=$result["revChqNum"];
		$revChqToCCID=$result["revChqToCCID"];
		$revChqDate=$result["revChqDate"];
		$fullname=$result["fullname"];
		$print_stamp=$result["print_stamp"];

		if($i%2==0){
			echo "<tr bgcolor=\"#F9F9F9\" align=center>";
		}else{
			echo "<tr bgcolor=\"#F3F3F3\" align=center>";
		}
			
		echo "
		<td>$revChqNum</td>
		<td><span onclick=\"javascript:popU('../../thcap_installments/frm_Index.php?show=1&idno=$revChqToCCID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\"><u>$revChqToCCID</u></font></span></td>
		<td>$revChqDate</td>
		<td align=left>$fullname</td>
		<td>$print_stamp</td>
		<td><img src=\"../images/detail.gif\" width=\"19\" height=\"19\" onclick=\"javascript:popU('frm_result.php?show=1&id=$id','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=350')\" style=\"cursor:pointer;\"></td>
		";
			
		echo "</tr>";
		$i++;
	}
	if($numrows==0){
		echo "<tr><td colspan=8 height=50 align=center bgcolor=\"#FFFFFF\"><b>-ไม่พบประวัติ-</b></td></tr>";
	}else{
		echo "<tr><td colspan=8 height=25 align=left bgcolor=\"#FFFFFF\"><b>มีทั้งหมด $numrows รายการ</b></td></tr>";		
	}
	?>

</table>    
</body>
</html>
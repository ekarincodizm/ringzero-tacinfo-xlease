<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>(THCAP) ประวัติการอนุมัติประเภทค่าใช้จ่าย</title>

<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">

<link type="text/css" rel="stylesheet" href="act.css" />
<link type="text/css" rel="stylesheet" href="styles/style.css" />

<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<style type="text/css">
.sortable {
	color: #000000;
	cursor:pointer;
	text-decoration:underline;
}
</style>
  
<script type="text/javascript" src="scripts/jquery-1.8.2.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript" src="scripts/jquery.tablesorter.js"></script>
<!--<script type="text/javascript" src="scripts/jquery.tableSort.js"></script>-->
	
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>

</head>

<body>
<center><h1>(THCAP) ประวัติการอนุมัติความสัมพันธ์ทางบัญชี</h1></center>
<table id="tb_approved" align="center" width="95%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#CDC9C9">
		<th>รายการ</th>
		<th>รหัสประเภทค่าใช้จ่าย</th>
		<th>ชื่อประเภทค่าใช้จ่าย</th>
		<th>ชื่อสมุดบัญชีพื้นฐาน</th>
		<th>ชื่อสมุดบัญชีคงค้าง</th>
		<th>ชื่อสมุดบัญชีทยอยรับรู้</th>
		<th>ผู้ทำรายการ</th>
		<th>วันเวลาที่ทำรายการ</th>
		<th>ผู้อนุมัติคนที่ 1</th>
		<th>วันเวลาที่อนุมัติคนที่ 1</th>
		<th>ผลการอนุมัติคนที่ 1</th>
		<th>ผู้อนุมัติคนที่ 2</th>
		<th>วันเวลาที่อนุมัติคนที่ 2</th>
		<th>ผลการอนุมัติคนที่ 2</th>
		<th>รายละเอียด</th>
	</tr>
	<?php
	$qry_fr=pg_query("select * from account.\"thcap_typePay_acc_temp\" where \"appvStatus1\" <> '9' or \"appvStatus2\" <> '9' order by \"autoID\" DESC ");
	$nub=pg_num_rows($qry_fr);
	
	$i = 0;
	while($res_fr=pg_fetch_array($qry_fr))
	{
		$autoID=$res_fr["autoID"];
		$tpID=$res_fr["tpID"];
		$tpBasis=$res_fr["tpBasis"]; // บัญชีพื้นฐาน
		$tpAccrual=$res_fr["tpAccrual"]; // บัญชีคงค้าง
		$tpAmortize=$res_fr["tpAmortize"]; // บัญชีทยอยรับรู้
		$doerID=$res_fr["doerID"];
		$doerStamp=$res_fr["doerStamp"];
		$appvID1=$res_fr["appvID1"];
		$appvStamp1=$res_fr["appvStamp1"];
		$appvStatus1=$res_fr["appvStatus1"];
		$appvID2=$res_fr["appvID2"];
		$appvStamp2=$res_fr["appvStamp2"];
		$appvStatus2=$res_fr["appvStatus2"];
		
		// หา ชื่อประเภทค่าใช้จ่าย
		$qry_tpDesc = pg_query("select \"tpDesc\" from account.\"thcap_typePay\" where \"tpID\" = '$tpID' ");
		$tpDesc = pg_result($qry_tpDesc,0);
		
		// หา ชื่อสมุดบัญชีพื้นฐาน
		if($tpBasis != "")
		{
			$qry_accBookName = pg_query("select \"accBookName\" from account.\"all_accBook\" where \"accBookserial\" = '$tpBasis' ");
			$tpBasisName = pg_result($qry_accBookName,0);
		}
		else
		{
			$tpBasisName = "";
		}
		
		// หา ชื่อสมุดบัญชีคงค้าง
		if($tpAccrual != "")
		{
			$qry_accBookName = pg_query("select \"accBookName\" from account.\"all_accBook\" where \"accBookserial\" = '$tpAccrual' ");
			$tpAccrualName = pg_result($qry_accBookName,0);
		}
		else
		{
			$tpAccrualName = "";
		}
		
		// หา ชื่อสมุดบัญชีทยอยรับรู้
		if($tpAmortize != "")
		{
			$qry_accBookName = pg_query("select \"accBookName\" from account.\"all_accBook\" where \"accBookserial\" = '$tpAmortize' ");
			$tpAmortizeName = pg_result($qry_accBookName,0);
		}
		else
		{
			$tpAmortizeName = "";
		}
		
		// หาชื่อพนักงานที่ทำรายการ
		$qry_doerName = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$doerID' ");
		$doerName = pg_result($qry_doerName,0);
		
		// หาชื่อผู้อนุมัติคนที่ 1
		if($appvID1 != "")
		{
			$qry_appvName = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$appvID1' ");
			$appvName1 = pg_result($qry_appvName,0);
		}
		else
		{
			$appvName1 = "";
		}
		
		// สถานะการอนุมัติคนที่ 1
		if($appvStatus1 == "0"){$qry_appvStatusText1 = "<font color=\"#FF0000\">ไม่อนุมัติ</font>";}
		elseif($appvStatus1 == "1"){$qry_appvStatusText1 = "<font color=\"#0000FF\">อนุมัติ</font>";}
		//elseif($appvStatus1 == "9"){$qry_appvStatusText1 = "รออนุมัติ";}
		else{$qry_appvStatusText1 = "";}
		
		// หาชื่อผู้อนุมัติคนที่ 2
		if($appvID1 != "")
		{
			$qry_appvName = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$appvID2' ");
			$appvName2 = pg_result($qry_appvName,0);
		}
		else
		{
			$appvName2 = "";
		}
		
		// สถานะการอนุมัติคนที่ 2
		if($appvStatus2 == "0"){$qry_appvStatusText2 = "<font color=\"#FF0000\">ไม่อนุมัติ</font>";}
		elseif($appvStatus2 == "1"){$qry_appvStatusText2 = "<font color=\"#0000FF\">อนุมัติ</font>";}
		//elseif($appvStatus2 == "9"){$qry_appvStatusText2 = "รออนุมัติ";}
		else{$qry_appvStatusText2 = "";}
		
		$i+=1;
		if($i%2==0){
			echo "<tr class=\"odd\" align=center>";
		}else{
			echo "<tr class=\"even\" align=center>";
		}
	?>
		<td><?php echo $i; ?></td>
		<td><?php echo $tpID; ?></td>
		<td align="left"><?php echo $tpDesc; ?></td>
		<td align="left"><?php echo $tpBasisName; ?></td>
		<td align="left"><?php echo $tpAccrualName; ?></td>
		<td align="left"><?php echo $tpAmortizeName; ?></td>
		<td align="left"><?php echo $doerName; ?></td>
		<td><?php echo $doerStamp; ?></td>
		<td align="left"><?php echo $appvName1; ?></td>
		<td><?php echo $appvStamp1; ?></td>
		<td><?php echo $qry_appvStatusText1; ?></td>
		<td align="left"><?php echo $appvName2; ?></td>
		<td><?php echo $appvStamp2; ?></td>
		<td><?php echo $qry_appvStatusText2; ?></td>
		<td><span onclick="javascript:popU('frm_detail_approve.php?id=<?php echo $autoID; ?>&view=v','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=750')" style="cursor: pointer;"><img src="../thcap/images/detail.gif" height="19" width="19" border="0"></span></td>
	<?php
		echo "</tr>";
	} //end while
	if($nub == 0)
	{
		echo "<tr bgcolor=\"#CDC5BF\"><td colspan=15 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
	}
	else
	{
		echo "<tr bgcolor=\"#CDC5BF\"><td colspan=\"15\"><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
	}
	?>
</table>
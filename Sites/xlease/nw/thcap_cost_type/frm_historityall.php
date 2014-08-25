<?php
include("../../config/config.php");
$sort = pg_escape_string($_GET["descOrascby"]);
$orderby = pg_escape_string($_GET["orderby"]);

if($sort == ""){
	$sort = "DESC";
}
if($orderby == ""){
	$orderby = "\"appvstamp\" ";
}
$query = pg_query("select * from \"thcap_cost_type_temp\" 
	where \"approved\"!='9' order by  $orderby $sort ");
$sortnew = $sort == 'DESC' ? 'ASC' : 'DESC';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>ประวัติการจัดการประเภทต้นทุนสัญญา</title>

<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">

<link type="text/css" rel="stylesheet" href="act.css" />
<link type="text/css" rel="stylesheet" href="styles/style.css" />

<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  

<script type="text/javascript" src="scripts/jquery-1.8.2.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript" src="scripts/jquery.tablesorter.js"></script>
</head>
<script type="text/javascript">

$(document).ready(function(){  
	window.opener.location.reload();});
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<body>
<center><h1>ประวัติการจัดการประเภทต้นทุนสัญญา</h1></center>
<table id="tb_approved" align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#CDC9C9">
		<th>รายการที่</th>
		<th><a href='frm_historityall.php?orderby=<?php echo "\"costname\""?>&descOrascby=<?php echo $sortnew ?>'><font color="black"><u>ชื่อประเภทต้นทุน</u></font></th> 
		<th><a href='frm_historityall.php?orderby=<?php echo "\"status_costtype\""?>&descOrascby=<?php echo $sortnew ?>'><font color="black"><u>ประเภทต้นทุน</u></font></th>
		<th><a href='frm_historityall.php?orderby=<?php echo "\"doerid\""?>&descOrascby=<?php echo $sortnew ?>'><font color="black"><u>ผู้ทำรายการ</u></font></th> 
		<th><a href='frm_historityall.php?orderby=<?php echo "\"doerstamp\""?>&descOrascby=<?php echo $sortnew ?>'><font color="black"><u>วันที่ทำรายการ</u></font></th>        
		<th>ประเภทสินเชื่อ</th>
		<th><a href='frm_historityall.php?orderby=<?php echo "\"appvid\""?>&descOrascby=<?php echo $sortnew ?>'><font color="black"><u>ผู้ทำการอนุมัติ</u></font></th>   
		<th><a href='frm_historityall.php?orderby=<?php echo "\"appvstamp\""?>&descOrascby=<?php echo $sortnew ?>'><font color="black"><u>วันที่ทำการอนุมัติ</u></font></th> 
		<th>หมายเหตุ</th>		
		<th>สถานะ</th>	
		<th>ผลการอนุมัติ</th>
	</tr>
	<?php
	$i=0;
	$numrows = pg_num_rows($query);
	while($result = pg_fetch_array($query))
	{
		$i++;
		$autoid=$result["autoid"];
		$costname= $result["costname"];	
		$costtype= $result["costtype"];			
		$doerid = $result["doerid"];
		$doerstamp= $result["doerstamp"];
		$appvid= $result["appvid"];
		$appvstamp= $result["appvstamp"];
		$typeloansuse = $result["typeloansuse"];
		$note = $result["note"];
		$resultappv= $result["approved"];
		$countedit=$result["edit_last_autoid"];
		$status_costtype=$result["status_costtype"];
		if($status_costtype=='0'){$status_costtype='ไม่ระบุ';}
		elseif($status_costtype=='1'){$status_costtype='ต้นทุนเริ่มแรก';}
		elseif($status_costtype=='2'){$status_costtype='ต้นทุนดำเนินการ';}
		
		$typeloan = substr($typeloansuse,1,strlen($typeloansuse)-2); 
		if($typeloan==""){$typeloan="ทุกประเภทสินเชื่อ";}
		//สถานะ
		if($countedit=='0'){ 
			$edit="เพิ่มใหม่";	 }
		else{
			$edit="แก้ไข";}
		
		//ชื่อคนทำรายการ
		$query_fullname = pg_query("select \"fullname\"  from \"Vfuser\" where \"id_user\" = '$doerid' ");
		$nameuser = pg_fetch_array($query_fullname);
		$fullnamedoerid=$nameuser["fullname"];
		//ชื่อผู้ทำการอนุมัติ
		$query_fullname = pg_query("select \"fullname\"  from \"Vfuser\" where \"id_user\" = '$doerid' ");
		$nameuser = pg_fetch_array($query_fullname);
		$fullnameappvid=$nameuser["fullname"];	
		
		if($resultappv=='1'){$resultappv="อนุมัติ";}
		else if($resultappv=='0'){$resultappv="ไม่อนุมัติ";}
		if($i%2==0){
			echo "<tr bgcolor=\"#EEE9E9\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE9E9';\">";
		}else{
			echo "<tr bgcolor=\"#FFFAFA\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFFAFA';\">";
		}
		echo "<td align=\"center\">$i</td>";
		echo "<td align=\"center\">$costname</td>";	
		echo "<td align=\"center\">$status_costtype</td>";	
		echo "<td align=\"center\">$fullnamedoerid</td>";
		echo "<td align=\"center\">$doerstamp</td>";
		echo "<td align=\"center\">$typeloan</td>";
		echo "<td align=\"center\">$fullnameappvid</td>";
		echo "<td align=\"center\">$appvstamp</td>";
		echo "<td align=\"center\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\" onclick=\"javascript:popU('frm_note.php?autoid=$autoid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=400');\" style=\"cursor:pointer;\"></td>";
		
		echo "<td align=\"center\">$edit</td>";
		echo "<td align=\"center\">$resultappv</td>";		
	}
	if($numrows==0){
			echo "<tr bgcolor=#FFFFFF height=50><td colspan=11 align=center><b>ไม่พบรายการ</b></td><tr>";
		}else{
			echo "<tr bgcolor=\"#CDC9C9\" height=25><td colspan=11><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
	}
	?>
	
</table>



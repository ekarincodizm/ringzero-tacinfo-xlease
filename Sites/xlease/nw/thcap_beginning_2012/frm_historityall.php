<?php
include("../../config/config.php");
$sort = pg_escape_string($_GET["descOrascby"]);
$orderby = pg_escape_string($_GET["orderby"]);

if($sort == ""){
	$sort = "DESC";
}
if($orderby == ""){
	$orderby = "\"doerstamp\" ";
}
$query = pg_query("select \"ledger_stamp\"::date,\"accBookserial\",\"ledger_balance\",\"doerid\",\"doerstamp\" from account.\"thcap_ledger_detail\" 
	where \"ledger_stamp\"::date='2012-12-31' and \"is_ledgerstatus\"='1' 
	order by  $orderby $sort");
$sortnew = $sort == 'DESC' ? 'ASC' : 'DESC';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>ประวัติการบันทึกยอดยกมาปี 2555</title>

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
<center><h1>ประวัติการบันทึกยอดยกมาปี 2555</h1></center>
<table id="tb_approved" align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#CDC9C9">
		<th>รายการที่</th>
		<th>วันที่ของยอดยกมา</th> 
		<th><a href='frm_historityall.php?orderby=<?php echo "\"abd_accbookid\""?>&descOrascby=<?php echo $sortnew ?>'><font color="black"><u>เลขบัญชี</u></font></th>		
		<th>ชื่อบัญชี</th> 		
		<th><a href='frm_historityall.php?orderby=<?php echo "\"ledger_balance\""?>&descOrascby=<?php echo $sortnew ?>'><font color="black"><u>ยอดยกปี 2555</u></font></th>        
		<th><a href='frm_historityall.php?orderby=<?php echo "\"doerid\""?>&descOrascby=<?php echo $sortnew ?>'><font color="black"><u>ผู้ทำรายการ</u></font></th>   
		<th><a href='frm_historityall.php?orderby=<?php echo "\"doerstamp\""?>&descOrascby=<?php echo $sortnew ?>'><font color="black"><u>วันที่ทำรายการ</u></font></th> 
		
	</tr>
	<?php
	$i=0;
	$numrows = pg_num_rows($query);
	while(($result = pg_fetch_array($query)))
	{
		//$autoid=$result["auto_id"];
		$ledgerstamp= $result["ledger_stamp"];				
		$accBookserial= $result["accBookserial"];		
		$ledgerbalance = $result["ledger_balance"];
		$doerid= $result["doerid"];
		$doerstamp= $result["doerstamp"];
		$ledgerbalance=number_format($ledgerbalance,2);
		//ผู้ทำรายการ
		$query_fullname = pg_query("select \"fullname\"  from \"Vfuser\" where \"id_user\" = '$doerid' ");
		$nameuser = pg_fetch_array($query_fullname);
		$fullnamedoerid=$nameuser["fullname"];
		//หาเลขบัญชี และ ชื่อบัญชี
		$query_accbook = pg_query("select \"accBookID\",\"accBookName\" from account.\"all_accBook\" 
		where \"accBookserial\"='$accBookserial'");
		$numrows_accbook = pg_num_rows($query_accbook);
		//ตรวจสอบก่อนว่ามี บัญชีนั้น จริง
		//$numrows_accbook=0
		if($numrows_accbook==0){
			break;
		}
		else{	
			$result_accbook = pg_fetch_array($query_accbook);
			$accBookID=$result_accbook["accBookID"]; 
			$accBookName=$result_accbook["accBookName"];
			if($i%2==0){
				echo "<tr bgcolor=\"#EEE9E9\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE9E9';\">";
			}else{
				echo "<tr bgcolor=\"#FFFAFA\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFFAFA';\">";
			}
			$i++;
			echo "<td align=\"center\">$i</td>";
			echo "<td align=\"center\">$ledgerstamp</td>";	
			echo "<td align=\"center\">$accBookID</td>";	
			echo "<td align=\"left\">$accBookName</td>";				
			echo "<td align=\"center\">$ledgerbalance</td>";
			echo "<td align=\"center\">$fullnamedoerid</td>";
			echo "<td align=\"center\">$doerstamp</td>";
		}
	}
	if($numrows_accbook==0){
		echo "<tr bgcolor=#FFFFFF height=50><td colspan=7 align=center><b>มีบางรายการไมีมี ข้อมูลเลขที่บัญชี อยู่ในระบบ</b></td><tr>";
	}
	else if($numrows==0){
			echo "<tr bgcolor=#FFFFFF height=50><td colspan=7 align=center><b>ไม่พบรายการ</b></td><tr>";
		}else{
			echo "<tr bgcolor=\"#CDC9C9\" height=25><td colspan=7><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
	}
	?>
	
</table>



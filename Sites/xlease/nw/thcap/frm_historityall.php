<?php
include("../../config/config.php");
$sort = $_GET["descOrascby"];
$orderby = $_GET["orderby"];

if($sort == ""){
	$sort = "DESC";
}
if($orderby == ""){
	$orderby = "a.\"replyByTakerStamp\" ";
}
$qry_fr = pg_query("			
			select * from  finance.\"V_thcap_receive_cheque_keeper_cheManage\" a
			left join \"Vfuser\"c on c.\"id_user\"= a.\"replyByTakerID\"
			left join  \"BankProfile\" d on d.\"bankID\"= a.\"bankOutID\" 
			where \"bankRevResult\" is not null 
			order by $orderby $sort  ");
$sortnew = $sort == 'DESC' ? 'ASC' : 'DESC';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>ประวัติการยืนยันนำเช็คเข้าธนาคาร</title>

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
<center><h1>ประวัติการยืนยันนำเช็คเข้าธนาคาร</h1></center>
<table  align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr style="font-weight:bold;" align="center" bgcolor="#CDC9C9">
		<td>รายการที่</td>
		<td><a href='frm_historityall.php?orderby=<?php echo "a.\"revChqToCCID\" "?>&descOrascby=<?php echo $sortnew ?>'><font color="black"><u>เลขที่สัญญา</u></font></td>
		<td width="120">ชื่อ-สกุลลูกค้า</td>
		<td><a href='frm_historityall.php?orderby=<?php echo "a.\"bankChqNo\" "?>&descOrascby=<?php echo $sortnew ?>'><font color="black"><u>เลขที่เช็ค</u></font></td>
		<td><a href='frm_historityall.php?orderby=<?php echo "a.\"bankChqDate\" "?>&descOrascby=<?php echo $sortnew ?>'><font color="black"><u>วันที่บนเช็ค</u></font></td>
		<td width="120"><a href='frm_historityall.php?orderby=<?php echo "d.\"bankName\" "?>&descOrascby=<?php echo $sortnew ?>'><font color="black"><u>ธนาคารที่ออกเช็ค</u></font></td>
		<td>จ่ายบริษัท</td>
		<td><a href='frm_historityall.php?orderby=<?php echo "a.\"bankChqAmt\" "?>&descOrascby=<?php echo $sortnew ?>'><font color="black"><u>ยอดเช็ค</u></font></td>
		<td>ผู้นำเช็คเข้า</td>
		<td>ธนาคารที่นำเข้า</td>	
		<td>ผู้ทำรายการ</td>
		<td><a href='frm_historityall.php?orderby=<?php echo "a.\"replyByTakerStamp\" "?>&descOrascby=<?php echo $sortnew ?>'><font color="black"><u>วันเวลาที่ทำรายการ</u></font></td>
		<td>ผลการนำเช็คเข้า</td>	
			
	</tr>
	<?php	
	$numrows = pg_num_rows($qry_fr);
	$i=0;
	while($res_fr=pg_fetch_array($qry_fr)){
		$chqKeeperID = $res_fr["chqKeeperID"];
		$revChqID = $res_fr["revChqID"];
		$bankChqNo=$res_fr["bankChqNo"];
		$revChqDate = $res_fr["revChqDate"]; 
		$bankName = $res_fr["bankName"]; 
		$bankOutBranch = $res_fr["bankOutBranch"]; 
		$bankChqToCompID = $res_fr["bankChqToCompID"]; 
		$bankChqAmt = $res_fr["bankChqAmt"]; 
		$revChqStatus=$res_fr["revChqStatus"];
		$bankChqDate=$res_fr["bankChqDate"];
		$BID=$res_fr["BID"];
		$giveTakerID=$res_fr["giveTakerID"];
		$bankRevResult=$res_fr["bankRevResult"];
		$isInsurChq = $res_fr["isInsurChq"];
		$fullnamedoerid = $res_fr["fullname"];
		$replyByTakerStamp = $res_fr["replyByTakerStamp"];
		$revChqToCCID = $res_fr["revChqToCCID"];
		//
		if($bankRevResult=='1'){$bankRevResult="เข้าปกติ";}
		else if($bankRevResult=='2'){$bankRevResult="เข้าToo Late";}
		else if($bankRevResult=='3'){
			$bankRevResult="เช็คเด้ง";
		}else {
			$bankRevResult="ยกเลิกนำเช็คเข้าธนาคาร";
		}					
		//หาชื่อลูกค้า
		$qry_cusname = pg_query("SELECT \"CusID\" ,thcap_fullname FROM \"vthcap_ContactCus_detail\" where \"contractID\" = '$revChqToCCID' and \"CusState\" = '0'");
		list($cusid,$fullname) = pg_fetch_array($qry_cusname);
									
		//หาชื่อธนาคาร
		if($BID!=""){
			$qry_ourbank = pg_query("SELECT \"BName\",\"BAccount\" FROM \"BankInt\" where \"BID\" = '$BID'");
			list($ourbankname,$BAccount) = pg_fetch_array($qry_ourbank);
		}
		else{ 
			$ourbankname="";
			$BAccount="";
		}						
		//หาชื่อผู้นำเข้า
		$qry_username = pg_query("SELECT fullname FROM \"Vfuser\" where id_user = '$giveTakerID'");
		list($userfullname) = pg_fetch_array($qry_username);
									
		$i+=1;
		if($i%2==0){
			if($isInsurChq==1){
				echo "<tr bgcolor=\"#e5cdf9\" align=center>";
			}else{
				echo "<tr class=\"odd\" align=center>";
			}
		}else{
			if($isInsurChq==1){
				echo "<tr bgcolor=\"#e5cdf9\" align=center>";
			}else{
				echo "<tr class=\"even\" align=center>";
			}
		}
		
		?>
		<td><?php echo $i; ?></td>							
		<td>
		<a style="cursor:pointer" onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $revChqToCCID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" title="ดูตารางผ่อนชำระ">
		<font color="red"><U><?php echo $revChqToCCID; ?></U></font></a>
		</td>
		<td align="left">
		<a style="cursor:pointer;" onclick="javascipt:popU('../search_cusco/index.php?cusid=<?php echo $cusid; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=750')" title="ดูข้อมูลลูกค้า">					
		(<font color="red"><U><?php echo $cusid; ?></U></font>)</a>
		<?php echo $fullname; ?>
		</td>
		<td><?php echo $bankChqNo; ?></td>
		<td><?php echo $bankChqDate; ?></td>
		<td align="left"><?php echo $bankName; ?></td>
		<td><?php echo $bankChqToCompID; ?></td>
		<td align="right"><?php echo number_format($bankChqAmt,2); ?></td>
		<td><?php echo $userfullname; ?></td>
		<td><?php echo "$ourbankname-$BAccount"; ?></td>
		<td><?php echo $fullnamedoerid ?></td>
		<td><?php echo $replyByTakerStamp; ?></td>
		<td><?php echo $bankRevResult; ?></td>
		</tr>
		<?php
	} //end whil
	if($numrows==0){
			echo "<tr bgcolor=#FFFFFF height=50><td colspan=13 align=center><b>ไม่พบรายการ</b></td><tr>";
		}else{
			echo "<tr bgcolor=\"#CDC9C9\" height=25><td colspan=13><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
	}
	?>
	
</table>

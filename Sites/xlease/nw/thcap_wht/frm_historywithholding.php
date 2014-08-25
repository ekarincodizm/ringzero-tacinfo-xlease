<?php 
include("../../config/config.php");
$sort = $_GET["descOrascby"];
$orderby = $_GET["orderby"];

if($sort == ""){
	$sort = "DESC";
}
if($orderby == ""){
	$orderby = "c.\"recStamp\"";
}
$qry=pg_query("SELECT a.\"receiptID\", a.\"receiveDate\",c.\"recUser\",c.\"recStamp\", a.\"whtRef\", a.\"sumdebtAmt\", a.\"sumWht\", 
							a.\"receiveUser\", a.\"recUser\",b.\"contractID\" ,d.\"fname\" FROM thcap_asset_wht c 
							left join fuser d on c.\"recUser\"=d.\"id_user\" 
							left join thcap_v_receipt_details b on c.\"receiptID\"=b.\"receiptID\"
							left join vthcap_wht a on a.\"receiptID\"=b.\"receiptID\" order by $orderby $sort");
$sortnew = $sort == 'DESC' ? 'ASC' : 'DESC';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>ประวัติรับใบหัก ณ ที่จ่าย</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">

<link type="text/css" rel="stylesheet" href="act.css" />
<link type="text/css" rel="stylesheet" href="styles/style.css" />

<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="scripts/jquery-1.8.2.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript" src="scripts/jquery.tablesorter.js"></script>
<script type="text/javascript">

function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</head>
<center><h1>ประวัติรับใบหัก ณ ที่จ่าย</h1></center>
<body>
<table width="1200" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td>       
			<div style="float:left">&nbsp;</div>
			<div align="center">
				<table id="tb_approved"  width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#BBBBEE">
					<tr style="font-weight:bold;" valign="middle" bgcolor="#CDC9C9"align="center">
						<th>รายการที่</th>
						<td>เลขที่ใบเสร็จ</td>
						<td><a href='frm_historywithholding.php?orderby=<?php echo "b.\"contractID\""?>&descOrascby=<?php echo $sortnew ?>'><font color="black"><u>เลขที่สัญญา</u></font></td>
						<td><a href='frm_historywithholding.php?orderby=<?php echo "a.\"receiveDate\""?>&descOrascby=<?php echo $sortnew ?>'><font color="black"><u>วันที่รับชำระ</u></font></td>
						<td>ยอดรวมใบเสร็จ</td>
						<td>เลขที่อ้างอิงภาษีหัก ณ ที่จ่าย</td>
						<td>จำนวนเงินในใบภาษี<br>หัก ณ ที่จ่าย</td>
						<td><a href='frm_historywithholding.php?orderby=<?php echo "d.\"fname\""?>&descOrascby=<?php echo $sortnew ?>'><font color="black"><u>ผู้ที่ทำรายการรับใบ</u></font></td>
						<td><a href='frm_historywithholding.php?orderby=<?php echo "c.\"recStamp\""?>&descOrascby=<?php echo $sortnew ?>'><font color="black"><u>วันที่ทำรายการรับใบ</u></font></td>
						<td>รายละเอียดรายการ<br>หัก ณ ที่จ่าย</td>							
					</tr>
					<?php
					$i=0;
					while($res=pg_fetch_array($qry)){
						list($receiptID, $receiveDate,$recUser,$recStamp, $whtRef, $sumdebtAmt, $sumWht,$receiveUser, $recUser,$contractID)=$res;
						$query_name = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$recUser' ");
						$levelname = pg_fetch_array($query_name);
						$empfullname=$levelname["fullname"];
						$i+=1;
						if($i%2==0){
							echo "<tr bgcolor=\"#EEE9E9\" height=25>";
						}else{
							echo "<tr bgcolor=\"#FFFAFA\" height=25>";
						}
						echo "
							<td align=center>$i</td>
							<td align=center><span onclick=\"javascript:popU('../thcap/Channel_detail.php?receiptID=$receiptID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=500')\" style=\"cursor:pointer;\"><u>$receiptID</u></span></td>
							<td align=center><span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><u>$contractID</u></span></td>
							<td align=center>$receiveDate</td>
							<td align=right>".number_format($sumdebtAmt,2)."</td>
							<td>$whtRef</td>
							<td align=right>".number_format($sumWht,2)."</td>
							<td align=center>$empfullname</td>
							<td align=center>$recStamp</td>							
							<td align=center><img src=\"images/detail.gif\" width=\"19\" height=\"19\" onclick=\"javascript : popU('../thcap/Channel_detail.php?receiptID=$receiptID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=500');\" style=\"cursor:pointer;\"></td>
						";
						?>
						</tr>
					<?php
					} //จบ  while
					?>
					<tr><td colspan="10" bgcolor="#CDC9C9" height=30><b><b>ข้อมูลทั้งหมด <?php echo $i;?> รายการ</b></td></tr>
				</table>
			</div>
        </td>
    </tr>
</table>
</body>
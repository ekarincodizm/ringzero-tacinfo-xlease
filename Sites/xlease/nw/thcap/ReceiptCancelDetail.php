<?php
session_start();
include("../../config/config.php");
$cancelID=$_GET['cancelID'];
$doerID=$_GET['doerID'];

$qrydoer=pg_query("select * from \"Vfuser\" WHERE \"username\"='$doerID'");
if($resvc1=pg_fetch_array($qrydoer)){
	$doername = $resvc1['fullname'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<title>รายละเอียดใบเสร็จที่ถูกยกเลิก</title>
<script language="javascript">
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>   
</head>
<body>
<div style="text-align:center"><h2>รายละเอียดใบเสร็จที่ถูกยกเลิก</h2></div>
<table width="100%" cellSpacing="1" cellPadding="3" border="0" bgcolor="#E8E8E8" align="center">
<?php
$qrycheck=pg_query("select b.\"fullname\" as requser,a.\"requestDate\",c.\"fullname\" as appuser,a.\"approveDate\",a.\"contractID\",a.\"receiptID\",a.\"result\" from thcap_temp_receipt_cancel a
left join \"Vfuser\" b on a.\"requestUser\"=b.\"id_user\" 
left join \"Vfuser\" c on a.\"approveUser\"=c.\"id_user\" where \"cancelID\"::text = '$cancelID'");
if($result=pg_fetch_array($qrycheck)){
	$requser=$result["requser"];
	$requestDate=$result["requestDate"];
	$appuser=$result["appuser"];
	$approveDate=$result["approveDate"];
	$receiptID=$result["receiptID"];
}

?>
<tr>
    <td height="25" width="50%" align="right"><b>ใบเสร็จที่ยกเลิก : </td><td><font color="red"><span onclick="javascript : popU('Channel_detail.php?receiptID=<?php echo $receiptID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=500');" style="cursor:pointer;"><u><?php echo $receiptID; ?></u></span></font></b></td>
</tr>
<tr>
    <td height="25" align="right"><b>ผู้ออกใบเสร็จ : </td><td><?php echo $doername; ?></b></td>
</tr>
<tr>
    <td height="25" align="right"><b>ผู้ขอยกเลิก : </td><td><?php echo $requser; ?></b></td>
</tr>
<tr>
    <td height="25" align="right"><b>วันเวลาที่ขอยกเลิก : </td><td><?php echo $requestDate; ?></b></td>
</tr>
<tr>
    <td height="25" align="right"><b>ผู้อนุมัติยกเลิก : </td><td><?php echo $appuser; ?></b></td>
</tr>
<tr>
    <td height="25" align="right"><b>วันเวลาที่อนุมัติยกเลิก : </td><td><?php echo $approveDate; ?></b></td>
</tr>
<tr>
    <td height="25" align="right" valign="top"><b>เหตุผลที่ยกเลิก : </td><td><textarea cols="30" rows="4" readonly><?php echo $result["result"]; ?></textarea></b></td>
</tr>
		

</table><br>
<?php 


	$num = 0;
		$listsql = pg_query("select c.\"tpDesc\",b.\"typePayRefValue\",b.\"typePayID\",b.\"contractID\",c.\"tpFullDesc\" from thcap_v_receipt_otherpay a 
left join thcap_temp_otherpay_debt b on a.\"debtID\" = b.\"debtID\" 
left join account.\"thcap_typePay\" c on c.\"tpID\" = b.\"typePayID\"
where a.\"receiptID\" = '$receiptID' ");
		$listrow = pg_num_rows($listsql);
		if($listrow > 0){
?>
<table width="100%" cellSpacing="1" cellPadding="3" frame="box" bgcolor="#E8E8E8" align="center">

<tr>
    <td height="25" colspan="2" align="center"><b> รายการที่เกี่ยวข้อง <?php echo $listrow; ?> รายการ</b>
	<hr width="450"></td>
</tr>

<?php 
	
		while($relist = pg_fetch_array($listsql)){
		$num++;
		$detail = $relist['tpDesc'];
		$tpFullDesc = $typequery['tpFullDesc'];
		$typePayRef=$relist["typePayRefValue"];
		$typePayID=$relist["typePayID"];
		$contractID=$relist["contractID"];
		list($typePayRefValue,$typePayRef2)=explode("-",$typePayRef);
		
		if($typePayID == "1003"){
				$qry_due=pg_query("select * from account.\"thcap_mg_payTerm\" where \"contractID\"='$contractID' and \"ptNum\"='$typePayRefValue' ");
				while($res_due=pg_fetch_array($qry_due)){
					$ptDate=trim($res_due["ptDate"]); // เธงเธฑเธเธ”เธดเธง
					$due = $tpFullDesc." ".$typePayRefValue." "."($ptDate)";
				}
		}
		else
		{
				$due = $tpFullDesc." ".$typePayRefValue;
		}
			
		if($detail == ""){
				$typesql1 = pg_query("select \"tpDesc\" from account.\"thcap_typePay\" where \"tpID\" = '1000' ");
				$typequery1 = pg_fetch_array($typesql1);
				$detail = $typequery1['tpDesc'];
				
		}
			
		
	echo  "<tr><td height=\"25\" width=\"50%\" align=\"right\"><b>รายการที่ $num : </td><td>$detail $due</b></td></tr>";		
			
		}
		
			
?>
<tr><td><br></td></tr>	
</table><br>
<?php } 

	$sqlchannel = pg_query("SELECT \"byChannel\",\"ChannelAmt\",\"byChannelRef\" FROM thcap_temp_receipt_channel where \"receiptID\" = '$receiptID'");
			
	$rowchannel = pg_num_rows($sqlchannel);
if($rowchannel > 0){

?>
<table width="100%" cellSpacing="1" cellPadding="3" frame="box" bgcolor="#E8E8E8" align="center">

<tr>
    <td height="25" colspan="4" align="center"><b> มีช่องทางการจ่ายดังนี้ </b>
	<hr width="450"></td>
</tr>

<?php 	$num = 0;
		while($rechannel = pg_fetch_array($sqlchannel)){
			$byChannel  = $rechannel["byChannel"];
			$ChannelAmt  = number_format($rechannel["ChannelAmt"],2);
			$byChannelRef  = $rechannel["byChannelRef"];
			
			$num++;
			if($byChannel=="" || $byChannel=="0"){$txtchannel="ไม่ระบุ";}
			else{
				if($byChannel=="999"){
					$txtchannel="ภาษีหัก ณ ที่จ่าย";
				}else{
					//นำไปค้นหาในตาราง BankInt
					$qrysearch=pg_query("select \"BAccount\",\"BName\" from \"BankInt\" where \"BID\"='$byChannel'");
					$ressearch=pg_fetch_array($qrysearch);
					list($BAccount,$BName)=$ressearch;
					$txtchannel="$BAccount-$BName";
					
					if($byChannel=="997" || $byChannel=="998"){
						$txtchannel=$txtchannel." เลขที่ $byChannelRef";
					}
				}
			}
			
	echo  "<tr><td height=\"25\" width=\"30%\" align=\"right\"><b>ช่องทางที่ $num : </td><td width=\"25%\">$txtchannel</b></td>";	
	echo  "<td height=\"25\" align=\"right\" width=\"15%\"><b>จำนวนเงิน  : </td><td align=\"left\">$ChannelAmt <b> บาท </b></td></tr>";		
			
		}
			
?>	
<tr><td><br></td></tr>	
</table><br>
<?php } ?>

<div style="text-align:center;"><input type="button" onclick="window.close();" value="ปิดหน้านี้"></div>
</body>
</html>
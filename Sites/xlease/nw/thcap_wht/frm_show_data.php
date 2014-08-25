<?php
session_start();
include("../../config/config.php");
$receiptID=$_GET['receiptID'];


//หาใบกำกับภาษี
$typesql = pg_query("SELECT * FROM thcap_v_receipt_otherpay where \"receiptID\" = '$receiptID' ");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<title>รายละเอียดช่องทางการจ่าย</title>
</head>
<body>
<div style="text-align:center"><h2>รายละเอียดช่องทางการจ่ายของใบเสร็จ</h2></div>
<div><b>รหัสใบเสร็จ : <font color="red"><?php echo $receiptID; ?></font></b></div>
<div><b>การจ่ายที่เกี่ยวข้อง :</b></div>  
<?php 
$i = 0;
while($typequery = pg_fetch_array($typesql)){
		$i++;
		$tpDesc = $typequery['tpDesc'];
		$typePayID = $typequery['typePayID'];
		$debtAmt = number_format($typequery['debtAmt'],2);
			
?>
<div style="margin-left:35px;"><font color=""><?php echo "- $typePayID ".$tpDesc." ".$debtAmt." บาท"; ?></font></div>

<?php } ?>
<br>
<table width="100%" cellSpacing="1" cellPadding="3"frame="box" bgcolor="#E8E8E8" align="center">
<?php 


	$sqlchannel = pg_query("SELECT \"byChannel\",\"ChannelAmt\" FROM thcap_temp_receipt_channel where \"receiptID\" = '$receiptID'");
			
	$rowchannel = pg_num_rows($sqlchannel);
if($rowchannel > 0){

?>
<tr>
    <td height="25" colspan="4" align="center"><b> มีช่องทางการจ่ายดังนี้ </b>
	<hr width="450"></td>
</tr>

<?php 	$num = 0;
		while($rechannel = pg_fetch_array($sqlchannel)){
			$byChannel  = $rechannel["byChannel"];
			$ChannelAmt  = $rechannel["ChannelAmt"];
			
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
				}
			}
			
			echo  "<tr><td height=\"25\" width=\"30%\" align=\"right\"><b>ช่องทางที่ $num : </td><td width=\"25%\">$txtchannel</b></td>";	
			echo  "<td height=\"25\" align=\"right\" width=\"20%\"><b>จำนวนเงิน  : </b></td><td align=\"left\">".number_format($ChannelAmt,2)." <b>บาท</b></td></tr>";		
			$sumamt=$sumamt+$ChannelAmt;
		}
		echo "<tr><td colspan=4><hr width=\"450\"></td></tr>";
		echo  "<tr><td height=\"25\" align=\"right\"></td><td width=\"25%\"></td>";	
		echo  "<td height=\"25\" align=\"right\"><b>รวมรับชำระ  : </b></td><td align=\"left\">".number_format($sumamt,2)." <b>บาท</b></td></tr>";		
}			
?>	
</table><br>
<div style="text-align:center;"><input type="button" onclick="window.close();" value="ปิดหน้านี้"></div>
</body>
</html>
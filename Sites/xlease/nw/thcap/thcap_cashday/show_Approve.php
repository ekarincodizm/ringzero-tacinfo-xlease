<?php
include("../../../config/config.php");
$receiveuserid = $_GET["receiveuserid"]; //user ที่รับชำระ
$auditdate = $_GET["auditdate"]; //วันที่รับชำระ

//หาชื่อพนักงาน
$qryname=pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\"='$receiveuserid'");
list($nameuser)=pg_fetch_array($qryname);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) ตรวจรับเงินสดประจำวันที่  <?php echo $auditdate; ?> ของ <?php echo $nameuser; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="../act.css"></link>
    
    <link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
function confirmappv(){
	if(confirm('กรุณายืนยันอีกครั้งว่าถูกต้อง')==true){
		return true;
	}
	else{
		return false;
	}
}
</script>   
</head>
<body>
<div align="center"><h2>(THCAP) ตรวจรับเงินสดประจำวัน </h2></div>
<div align="center"><b>วันที่  <?php echo $auditdate; ?> ของ <?php echo $nameuser; ?></b></div>
<table width="600" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
			<div style="clear:both;"></div>
			
			<!--รายละเอียดรายการ-->
			<fieldset><legend><b>รายละเอียดรายการ</b></legend>
				<div style="padding:10px;">
				<table width="500" border="0" cellspacing="1" cellpadding="1" align="center" bgcolor="#B4CDCD">
				<tr bgcolor="#7A8B8B" style="color:#FFF;"><th>เลขที่สัญญา</th><th>เลขที่ใบเสร็จ</th><th>จำนวนเงิน</th></tr>
				<?php
				$qrydata=pg_query("SELECT \"contractID\",\"receiptID\",sum(\"debtAmt\")
				FROM thcap_v_receipt_otherpay 
				WHERE \"byChannel\"='1' AND date(\"receiveDate\")='$auditdate' AND \"id_user\"='$receiveuserid'
				GROUP BY \"contractID\",\"receiptID\",\"nameuser\"");
				$i=0;
				while($resdata=pg_fetch_array($qrydata)){
					list($contractID,$receiptID,$debtAmt)=$resdata;
					if($i%2==0){
						$color="#D1EEEE";
					}else{
						$color="#E0FFFF";
					}
					echo "<tr bgcolor=$color align=center height=25>
					<td><span onclick=\"javascript:popU('../../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\"><u>$contractID</u></font></span></td>
					<td><span onclick=\"javascript : popU('../../thcap/Channel_detail.php?receiptID=$receiptID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=500');\" style=\"cursor:pointer;\"><u>$receiptID</u></span></td>
					<td align=right>".number_format($debtAmt,2)."</td>
					</tr>";
					$i++;
				}
				if($i==0){
					echo "<tr height=50 bgcolor=#FFF><td colspan=3 align=center><b>-ไม่พบข้อมูล-</b></td></tr>";
				}
				?>
				</table>
				</div>
				<div style="text-align:center;padding:10px;">
				<!--ส่งแบบ FORM ใน HTML-->
				<form name="my" method="post" action="process_cashday.php">
					<input type="submit" id="btn1" value="ยืนยันว่าถูกต้อง" onclick="return confirmappv();">
					<input type="hidden" name="receiveuserid" id="receiveuserid" value="<?php echo $receiveuserid;?>">
					<input type="hidden" name="auditdate" id="auditdate" value="<?php echo $auditdate;?>">
					<input type="hidden" name="method" id="method" value="approve">
					<input type="hidden" name="stsapp" id="stsapp" value="yes">
				</form>
				</div>
				</div>
			</fieldset>
			</div>
		</td>
	</tr>
</table>
</html>
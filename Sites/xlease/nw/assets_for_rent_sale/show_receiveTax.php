<?php
include("../../config/config.php");
$datepicker = $_POST['datepicker'];
if($datepicker==""){
	$datepicker=nowDate();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) รับใบกำกับภาษีซื้อ</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
    
<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}
</style>
<script>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>

<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td>       
			<div style="float:left">&nbsp;</div>
			<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();" class="ui-button"></div>
			<div style="clear:both;"></div>
			<div align="center" style="padding-top:20px">
				<div class="ui-widget">
					<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#CCCCCC">
					<tr><td colspan="11" bgcolor="#FFFFFF" align="center"><h2>ประวัติการรับใบกำกับภาษีซื้อ</h2></td></tr>
					<tr style="font-weight:bold;" valign="middle" bgcolor="#BBBBBB" align="center">
						<td>ที่</td>
						<td>ผู้ขาย</td>
						<td>วันที่ซื้อ</td>
						<td>ราคาหลังรวม VAT</td>
						<td>เลขที่ใบเสร็จ</td>
						<td>เลขที่ใบสั่งซื้อ</td>
						<td>ผู้รับใบกำกับภาษีซื้อ</td>
						<td>วันเวลาที่รับใบกำกับภาษีซื้อ</td>
						<td>รายละเอียด</td>
						<td>สถานะรับ</td>
					</tr>
						<?php
						$i=0;
						$qry=pg_query("SELECT * FROM vthcap_asset_biz_taxinvoice WHERE \"statusassetID\"<>'2' ORDER BY \"recStamp\" DESC");
						$nubqry=pg_num_rows($qry);
						while($res=pg_fetch_array($qry)){
							$assetID=$res["assetID"];
							$statusassetID=$res["statusassetID"];
							$i+=1;
							if($i%2==0){
								echo "<tr bgcolor=\"#DDDDDD\" height=\"25\" align=\"center\">";
							}else{
								echo "<tr bgcolor=\"#EEEEEE\" height=\"25\" align=\"center\">";
							}
							if($statusassetID==0){
								$txtstatus='คืน/ยกเลิก';
							}else{
								$txtstatus='รับแล้ว';
							}
							echo "
								<td>$i</td>
								<td align=left>".$res['corpname']."</td>
								<td>".$res['buyDate']."</td>
								<td  align=right>".number_format($res['afterVat'],2)."</td>
								<td>".$res['receiptNumber']."</td>
								<td>".$res['PurchaseOrder']."</td>
								<td  align=left>".$res['recname']."</td>
								<td>".$res['recStamp']."</td>
								<td><img src=\"images/detail.gif\" width=\"19\" height=\"19\" onclick=\"javascript : popU('../view_assets_for_rent_sale/show_appvDetail.php?bill_id=$assetID&status=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=600');\" style=\"cursor:pointer;\"></td>								
								<td>$txtstatus</td>
							";
							?>
							</tr>
						<?php
						}
						if($nubqry==0){
							echo "<tr><td colspan=\"10\" height=\"50\" align=\"center\">--ไม่พบข้อมูล--</td></tr>";
						}						
						?>
						</table>
						</form>
					</div>
				</div>
			</fieldset>
        </td>
    </tr>
</table>

</body>
</html>
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
    <title>(THCAP) รับใบหัก ณ ที่จ่าย</title>
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
<script type="text/javascript">
function addCommas(nStr)
{ // function สำหรับเพิ่มลูกน้ำ
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1))
	{
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
return x1 + x2;
}

function popU(U,N,T){
    newWindow = window.open(U, N, T);
}

</script>   
</head>
<body id="mm">

<table width="1100" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td>       
			<div style="float:left">&nbsp;</div>
			<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();" class="ui-button"></div>
			<div style="clear:both;"></div>
			<fieldset><legend><B>(THCAP) รับใบหัก ณ ที่จ่าย</B></legend>
				<div align="center">
					<div class="ui-widget">
						<form method="post" name="myfrm" action="frm_Index.php">
						<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
						<tr><td colspan="7"><b><font color="red" size="3">* คำเตือน: ผู้ที่กดรับใบหัก ณ ที่จ่าย หมายถึงท่านคือผู้ที่ดูแลและเก็บรักษา ใบหัก ณ ที่จ่าย นั้นๆ เท่านั้น</font></b></td></tr>
						<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
							<td>เลขที่ใบเสร็จ</td>
							<td>เลขที่สัญญา</td>
							<td>วันที่รับชำระ</td>
							<td>ยอดรวมใบเสร็จ</td>
							<td>เลขที่อ้างอิงภาษีหัก ณ ที่จ่าย</td>
							<td>จำนวนเงินในใบภาษี<br>หัก ณ ที่จ่าย</td>
							<td>รายละเอียดรายการ<br>หัก ณ ที่จ่าย</td>
							<td width="150">ทำรายการ</td>
						</tr>
						<?php
						$i=0;
						$qry=pg_query("SELECT a.\"receiptID\", a.\"receiveDate\", a.\"whtRef\", a.\"sumdebtAmt\", a.\"sumWht\", 
						a.\"receiveUser\", a.\"recUser\",b.\"contractID\" FROM vthcap_wht a
						inner join thcap_v_receipt_details b on a.\"receiptID\"=b.\"receiptID\" where a.\"recUser\" is null");
						while($res=pg_fetch_array($qry)){
							list($receiptID, $receiveDate, $whtRef, $sumdebtAmt, $sumWht,$receiveUser, $recUser,$contractID)=$res;
						
						$i+=1;
						$nameform="my".$i;
						if($i==1){?>
							<form name="1" method="post" >
							</form>
						<?php }
						if($i%2==0){
							echo "<tr class=\"odd\" height=25>";
						}else{
							echo "<tr class=\"even\" height=25>";
						}
						
						//นำเลขที่ใบเสร็จไปค้นหา byChannel 
						$qrychannel=pg_query("SELECT * FROM thcap_temp_receipt_channel where \"receiptID\"='$receiptID' and \"byChannel\"='999'");
						$numchannel=pg_num_rows($qrychannel);
						echo "
							<td align=center><span onclick=\"javascript:popU('../thcap/Channel_detail.php?receiptID=$receiptID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=500')\" style=\"cursor:pointer;\"><u>$receiptID</u></span></td>
							<td align=center><span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><u>$contractID</u></span></td>
							<td align=center>$receiveDate</td>
							<td align=right>".number_format($sumdebtAmt,2)."</td>
							<td>$whtRef</td>
							<td align=right>".number_format($sumWht,2)."</td>

							<td align=center><img src=\"images/detail.gif\" width=\"19\" height=\"19\" onclick=\"javascript : popU('../thcap/Channel_detail.php?receiptID=$receiptID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=500');\" style=\"cursor:pointer;\"></td>
						";
						?>
							<td align=center>
								<table width="100" border="0">
									<tr>
										<td width="60" align="right">
											<form name='<?php echo $nameform ;?>' method="post" action="process_wht.php">
												<input type="hidden" name="receiptid" id="receiptid" value="<?php echo $receiptID?>">
												<input type="hidden" name="cmd" id="cmd" value="add">
												<input hidden name="receive" value="รับ" type="submit"/>
												<input type="button" value="รับใบ" id="submitButton" onClick="if(confirm('เลขที่ใบเสร็จ :\t\t\t\t'+'<?php echo $receiptID;?>'+'\r\nวันที่รับชำระ :\t\t\t\t'+'<?php echo $receiveDate;?>'+'\r\n\ยอดรวมใบเสร็จ :\t\t\t'+'<?php echo $sumdebtAmt;?>'+'\t\tบาท\r\nเลขที่อ้างอิงภาษีหัก ณ ที่จ่าย :\t'+'<?php echo $whtRef;?>'+'\r\nจำนวนเงินในใบภาษีหัก ณ ที่จ่าย :'+'<?php echo $sumWht;?>'+'\t\tบาท')==true){
												document.forms['<?php echo $nameform;?>'].receive.click();}">
											</form>				
										</td>
										<td>
											<?php
											if($numchannel==0){
												echo "<img src=\"images/warning-icon.png\" width=\"24\" height=\"20\" title=\"อาจมีปัญหากรุณาแจ้งผู้ดูแลระบบ\">";
											}
											?>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<?php
						} 
						?>
						<tr><td colspan="8" bgcolor="#fcf7bf"><b><font color="red">*</font> <u>หมายเหตุ</u> <img src="images/warning-icon.png" width="24" height="20">  หมายถึง อาจมีปัญหากรุณาแจ้งผู้ดูแลระบบ</b></td></tr>
						</table>
						</form>

					</div>
				</div>
			</fieldset>
        </td>
    </tr>
</table>
	
<br>
<!--ตาราง แสดงประวัติรับใบหัก ณ ที่จ่าย 30 รายการล่าสุด-->
<table width="1200" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td>       
			<div style="float:left">&nbsp;</div>
			<fieldset><legend><B>ประวัติรับใบหัก ณ ที่จ่าย 30 รายการล่าสุด(<a style="color:#0099FF;cursor:pointer;"onclick="javascript:popU('frm_historywithholding.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1400,height=650')" ><u>ทั้งหมด</u></a>)</B></legend>
				<div align="center">
						<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#BBBBEE">
						<tr style="font-weight:bold;" valign="middle" bgcolor="#CDC9C9"align="center">
							<td>รายการที่</td>
							<td>เลขที่ใบเสร็จ</td>
							<td>เลขที่สัญญา</td>
							<td>วันที่รับชำระ</td>							
							<td>ยอดรวมใบเสร็จ</td>							
							<td>เลขที่อ้างอิงภาษีหัก ณ ที่จ่าย</td>
							<td>จำนวนเงินในใบภาษี<br>หัก ณ ที่จ่าย</td>
							<td>ผู้ที่ทำรายการรับใบ</td>
							<td>วันที่ทำรายการรับใบ</td>
							<td>รายละเอียดรายการ<br>หัก ณ ที่จ่าย</td>							
						</tr>
						<?php
						$i=0;
						$qry=pg_query("SELECT a.\"receiptID\", a.\"receiveDate\",c.\"recUser\",c.\"recStamp\", a.\"whtRef\", a.\"sumdebtAmt\", a.\"sumWht\", 
								a.\"receiveUser\", a.\"recUser\",b.\"contractID\" FROM thcap_asset_wht c 
								left join thcap_v_receipt_details b on c.\"receiptID\"=b.\"receiptID\"
								left join vthcap_wht a on a.\"receiptID\"=b.\"receiptID\" order by c.\"recStamp\" DESC limit 30");
						while($res=pg_fetch_array($qry)){
							list($receiptID, $receiveDate,$recUser,$recStamp,$whtRef, $sumdebtAmt, $sumWht,$receiveUser, $recUser,$contractID)=$res;
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
						} 
						?>
						<!--แสดงจำนวนข้อมูลทั้งหมด-->
						<tr><td colspan="10" bgcolor="#CDC9C9" height=30><b><b>ข้อมูลทั้งหมด <?php echo $i;?> รายการ</b></td></tr>
						</table>
					</div>
			</fieldset>
        </td>
    </tr>
</table>
</body>
</html>
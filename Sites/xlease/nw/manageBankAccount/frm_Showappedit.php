<?php
include("../../config/config.php");

$auto_id = $_GET["auto_id"];

$qrychk=pg_query("select \"BID\" from \"BankInt_Waitapp\" where \"auto_id\"='$auto_id' and \"statusApp\"='2'");
list($BID)=pg_fetch_array($qrychk);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>อนุมัติแก้ไขบัญชีธนาคารบริษัท</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script language=javascript>
function app(frm,app)
{		
	if(app == 'noapp'){ 
		document.getElementById("statusapp").value = '0';
	}
	
	if(app == 'app'){
		if(confirm("ยืนยันการอนุมัติ")==true){
			frm.action="process_AddAccount.php";
			frm.submit();
		}
	}else{
		if(confirm("ยืนยันไม่อนุมัติ")==true){
			frm.action="process_AddAccount.php";
			frm.submit();
		}	
	}			
}
</script>
</head>
<body>
<form method="post" name="frm">
<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
    <td>      
		<div class="header"><h2>อนุมัติแก้ไขบัญชีธนาคารบริษัท</h2></div>
		
		<div class="wrapper">
		<div style="width:1200px;">
			<?php
			for($i=0;$i<2;$i++){
				if($i==0){
					$float="left";
					$txt="ข้อมูลเก่า";
					$color="#A9A9A9";
					$color2="#E8E8E8";
					$color3="#CDC0B0";
					$color4="#FFEFDB";
					
					$qry=pg_query("select * from \"BankInt\" where \"BID\"='$BID'");
					$numrows=pg_num_rows($qry);
					$res=pg_fetch_array($qry);
				}else{
					$float="right";
					$txt="ข้อมูลใหม่";
					$color="#097AB0";
					$color2="#DBF2FD";
					$color3="#FFCCCC";
					$color4="#FFECEC";
					
					$qry=pg_query("select * from \"BankInt_Waitapp\" where \"auto_id\"='$auto_id' and \"statusApp\"='2'");
					$numrows=pg_num_rows($qry);
					$res=pg_fetch_array($qry);
				}
			?>
			<div style="float:<?php echo $float;?>;width:600px;">
			<fieldset><legend><B><?php echo $txt;?></B></legend>	
				<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
				<tr align="left">
					<td align="right"><b>รหัสช่องทาง</b></td>
					<td width="10" align="center">:</td>
					<td class="text_gray"><input type="text" name="BChannel" value="<?php echo $res['BChannel'];?>" size="30" readonly></td>
				</tr>
				<tr align="left">
					<td align="right" valign="top"><b>รายละเอียดช่องทาง</b></td>
					<td width="10" align="center" valign="top">:</td>
					<td class="text_gray"><textarea cols="50" rows="3" name="desc"><?php echo $res['desc'];?></textarea></td>
				</tr>
				<tr align="left">
					<td align="right"><b>เลขที่บัญชี</b></td>
					<td width="10" align="center">:</td>
					<td class="text_gray"><input type="text" name="BAccount" value="<?php echo $res['BAccount'];?>" size="30"></td>
				</tr>
				<tr align="left">
					<td align="right"><b>ชื่อธนาคาร</b></td>
					<td width="10" align="center">:</td>
					<td class="text_gray"><input type="text" name="BName" value="<?php echo $res['BName'];?>" size="60"></td>
				</tr>
				<tr align="left">
					<td align="right"><b>สาขา</b></td>
					<td width="10" align="center">:</td>
					<td class="text_gray"><input type="text" name="BBranch" value="<?php echo $res['BBranch'];?>" size="60"></td>
				</tr>
				<tr align="left">
					<td align="right" valign="top"><b>ชื่อเจ้าของบัญชี</b></td>
					<td width="10" align="center"valign="top">:</td>
					<td class="text_gray">
					<?php 
					$qryname=pg_query("select * from \"VSearchCusCorp\" where \"CusID\"='$res[BCompany]'");
					$resname=pg_fetch_array($qryname);
					?>
					<input type="text" name="BCompany" id="BCompany" value="<?php echo $resname["full_name"]; ?>" size="60">
					</td>
				</tr>
				<tr align="left">
					<td align="right"><b>ประเภทบัญชี</b></td>
					<td width="10" align="center">:</td>
					<td class="text_gray">
						<?php
						if($res['BChannel'] == '1'){
							$BTypetxt = 'กระแสรายวัน';
						}else if($res['BChannel'] == '2'){
							$BTypetxt = 'ออมทรัพย์';
						}else{
							$BTypetxt = 'ไม่ระบุ';
						}
						?>
						<input type="text" value="<?php echo $BTypetxt; ?>" readonly>
					</td>
				</tr>
				<tr align="left">
					<td align="right"><b>ช่องทางการรับชำระ</b></td>
					<td width="10" align="center">:</td>
					<td class="text_gray">
						<?php
						if($res['isChannel'] == '0'){
							$istxt = 'ไม่เป็นช่องทางการรับชำระ';
						}else if($res['isChannel'] == '1'){
							$istxt = 'เป็นช่องทางการรับชำระ';
						}else{
							$istxt = 'ไม่ระบุ';
						}
						?>
						<input type="text" value="<?php echo $istxt; ?>" size="30" readonly>
					</td>
				</tr>
				<tr align="left">
					<td align="right"><b>ใช่บัญชีเงินโอนหรือไ่ม่</b></td>
					<td width="10" align="center">:</td>
					<td class="text_gray">
						<?php
						if($res['isTranPay'] == '0'){
							$trantxt = 'ไม่ใช่';
						}else if($res['isTranPay'] == '1'){
							$trantxt = 'ใช่';
						}else{
							$trantxt = 'ไม่ระบุ';
						}
						?>
						<input type="text" value="<?php echo $trantxt; ?>" size="30" readonly>
					</td>
				</tr>
				<tr align="left">
					<td align="right"><b>สถานะ</b></td>
					<td width="10" align="center">:</td>
					<td class="text_gray"><input type="text" name="BActive" value="<?php echo $res['BActive'];?>" disabled="disabled" size="20"></td>
				</tr>
				</table>
			</fieldset> 
			</div>
			<?php
			}
			?>
		</div>
		</div>
    </td>
</tr>
<tr align="center">
	 <td height="50"><input type="hidden" name="statusapp" id="statusapp" ><input type="hidden" name="auto_id" value="<?php echo $auto_id;?>"><input type="hidden" name="method" value="approveedit"><input name="button" type="button" value="อนุมัติ" onclick="app(this.form,'app');"><input name="button" type="button" value=" ไม่อนุมัติ " onclick="app(this.form,'noapp');"/></td>
</tr>
</table> 

</form>        
</body>
</html>
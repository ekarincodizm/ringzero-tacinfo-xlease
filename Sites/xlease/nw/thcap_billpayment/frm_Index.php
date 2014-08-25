<?php
session_start();
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
include("../../config/config.php");
$iduser = $_SESSION['uid'];
$c_code=$_SESSION["session_company_code"];
//$c_code="THA";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="file:///C|/wamp/www/av/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<!-- InstanceBeginEditable name="doctitle" -->
<title>(THCAP) Load Bill Payment</title>
<!-- InstanceEndEditable -->
<style type="text/css">
<!--
.style1 {
	font-family: Tahoma;
	font-size: medium;
}
.style3 {
    font-family: Tahoma;
	color: #ffffff;
	font-weight: bold;
	font-size: medium;
}
.style4 {
    font-family: Tahoma;
	color: #000000;
  }
  .style5 {
    font-family: Tahoma;
	color: #000000;
	font-size: medium;
  }

-->
</style>
<!-- InstanceBeginEditable name="head" -->
<script type="text/JavaScript">
$(document).ready(function(){
	$("#dateadd").datepicker({
		showOn: 'button',
		buttonImage: './images/calendar.gif',
		buttonImageOnly: true,
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd'
	});
});
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
  window.location.reload();
}
//-->
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}

function chklist(){
	if(document.upfile.bankint.value == ""){
		alert('กรุณาเลือกช่องทาง');
		return false;
	}else if(document.upfile.file.value == ""){
		alert('กรุณาเลือกไฟล์ Bill Payment');
		return false;
	}else{
		return true;
	}	

}
</script>
<!-- InstanceEndEditable -->
</head>

<body style="background-color:#ffffff; margin-top:0px;">
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
	<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
		<h1 class="style4"><?php echo $_SESSION["session_company_thainame_thcap"]; ?></h1>
	</div>
	
	<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
		<div class="style3" style="background-color:#333333; width:auto; height:20px; padding-left:10px;"><?php echo $_SESSION["session_company_thainame_thcap"]; ?> </div>
		<div class="style3" style="background-color:#000000; width:auto; height:20px; padding-left:10px;"></div>
		<div class="style5" style="width:auto; height:50px; padding-left:10px;">
			Load Bank data tranfers
			<button onclick="window.close()">CLOSE</button>
		</div>
		<div>
			<form name="upfile" action="uploadfile.php" method="post" enctype="multipart/form-data">
				ช่องทาง : <select name="bankint">
				<?php 	
						$sql_bank = pg_query("select * from \"BankInt\" where \"isBillPayment\" = '1'");
							echo "<option value=\"\">- เลือกช่องทาง-</option>";
						while($re_bank = pg_fetch_array($sql_bank)){
							echo "<option value=\"".$re_bank["BID"]."\">".$re_bank["BName"].",".$re_bank["BAccount"].",".$re_bank["BBranch"]."</option>";
						} 
				?>
				</select><br>
				วันที่ของข้อมูลที่จะ UPLOAD : <input type="text" name="dateadd" id="dateadd" size="10" value="<?php echo nowDate(); ?>" style="text-align:center;" readonly><br>
				Upload Tranfer data <input type="file" name="file" id="file"  />
				<input type="submit" value="Upload" onclick="return chklist();" />
			</form>
		</div>
		<div>
		
		<?php
		
		$dir = 'upload'."/".$c_code."/";
	
		//$files = scandir($dir); //แสดงชื่อไฟล์ถ้ากำหนด scandir($dir,1) แสดงว่าให้เรียงไฟล์จากน้อยไปมาก
		?>	
		<br>
		<div style="padding:0 0 2px;"><b>หมายเหตุ</b> <span style="background-color:#FFE4E1;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> <font color=red> คือ รายการที่พบในฐานข้อมูล แต่ไม่พบใน folder upload</font></div>
		<table  border="0" cellSpacing="2" cellPadding="1" style="background-color:#EDF1DA;">
			<tr style="background-color:#FAFDEC;"><td colspan="8"><div align="center">file in folder upload </div></td></tr>
			<tr style="background-color:#FAFDEC;text-align:center">
				<td width="36">No.</td>
				<td width="">fileName</td>
				<td>ช่องทาง</td>
				<td>วันที่ของข้อมูล</td>
				<td>ผู้อัพโหลด</td>
				<td>วันเวลาที่อัพโหลด</td>
				<td>จำนวนรายการ</td>
				<td width="65">detail</td>
				<!--<td width="72">delete</td>-->
			</tr>
		  
			<?php	
			$n=0;
			//ดึงข้อมูลจากฐานข้อมูลมาแสดง
			$qry_sel = pg_query("	SELECT 	pg_catalog.concat(COALESCE(c.\"BName\", ''::character varying),',',COALESCE(c.\"BAccount\", ''::character varying),',',COALESCE(c.\"BBranch\", ''::character varying)) as channel,
											b.\"fullname\", a.\"doerStamp\",a.lfb_date ,a.lfb_filename
									FROM finance.thcap_load_file_billpayment a
									LEFT JOIN \"Vfuser\" b ON a.\"doerID\" = b.\"id_user\"
									LEFT JOIN \"BankInt\" c ON a.\"lfb_channel\"::integer = c.\"BID\"
									ORDER BY a.lfb_date DESC");
			while($ressel=pg_fetch_array($qry_sel)){
				$n++;
				list($lfb_channel,$fullname,$time,$lfb_date,$lfb_filename) = $ressel;
				
				//ตรวจสอบว่ามีไฟล์ชื่อนี้ในเครื่องหรือไม่
				if (file_exists($dir."/".$lfb_filename)) {
				   $color="#FFFFFF";
				}else{
				   $color="#FFE4E1";
				}
				
				//หาว่าแต่ละไฟล์มีทั้งหมดกี่รายการ
				$qrynub=pg_query("select * from finance.\"Vthcap_receive_billpayment\" WHERE filename = '$lfb_filename'");
				$numrows=pg_num_rows($qrynub);
			?>
				<tr style="background-color:<?php echo $color;?>">
					<td align="center"><?php echo $n; ?></td>
					<td><?php echo $lfb_filename.'<br>'; ?></td>
					<td><?php echo $lfb_channel; ?></td>
					<td align="center"><?php echo $lfb_date; ?></td>
					<td><?php echo $fullname; ?></td>
					<td><?php echo $time; ?></td>
					<td align="center"><?php echo $numrows; ?></td>
					<td align="center"><img src="images/detail.gif" width="19" height="19" style="cursor:pointer;" onclick="javascript:popU('frm_Detail.php?filename=<?php echo $lfb_filename;?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1150,height=800')"></td>										
				</tr>
				<?php
			}
			?>
			<tr>
				<td colspan="8">&nbsp;</td>
			</tr>
		</table>
		</div>
	</div>
</div>
</body>
</html>

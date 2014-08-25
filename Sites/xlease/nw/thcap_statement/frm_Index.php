<?php
session_start();
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
include("../../config/config.php");
$iduser = $_SESSION['uid'];
$c_code=$_SESSION["session_company_code"];

//หาวันที่สามารถ uplode ได้
$qrydate=pg_query("select current_date-2");
list($dateload)=pg_fetch_array($qrydate);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="file:///C|/wamp/www/av/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<!-- InstanceBeginEditable name="doctitle" -->
<title>(THCAP) LOAD STATEMENT BANK</title>
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
function checkdate(){
	if($('#dateadd').val() > $('#dateload').val()){
		alert("กรุณาเลือกวันที่ก่อนหน้านี้");
		$('#dateadd').val($('#dateload').val());
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
			(THCAP) LOAD STATEMENT BANK
			<button onclick="window.close()">CLOSE</button>
		</div>
		<div>
			<form name="upfile" action="uploadfile.php" method="post" enctype="multipart/form-data">
				ช่องทาง : <select name="bankint">
				<?php 	
						$sql_bank = pg_query("select * from \"BankInt\" where \"isLoadStatementAble\" = '1'");
							echo "<option value=\"\">- เลือกช่องทาง-</option>";
						while($re_bank = pg_fetch_array($sql_bank)){
							if($re_bank["BBranch"]!=""){
								$branch=", $re_bank[BBranch]";
							}
							echo "<option value=\"".$re_bank["BID"]."\">".$re_bank["BName"].",".$re_bank["BAccount"]."$branch</option>";
						} 
				?>
				</select><br>
				วันที่ของข้อมูลที่จะ UPLOAD : <input type="hidden" id="dateload" value="<?php echo $dateload; ?>"><input type="text" name="dateadd" id="dateadd" size="10" value="<?php echo $dateload; ?>" style="text-align:center;" onchange="return checkdate();" readonly><br>
				Upload Tranfer data <input type="file" name="file" id="file"  />
				<input type="submit" value="Upload" onclick="return chklist();" />
			</form>
		</div>
		<div>
		
		<?php	
		$dir = 'upload/';
		?>	
		<br>
		<div style="padding:0 0 2px;"><b>หมายเหตุ</b> <span style="background-color:#FFE4E1;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> <font color=red> คือ รายการที่พบในฐานข้อมูล แต่ไม่พบใน folder upload</font></div>
		<table  border="0" cellSpacing="2" cellPadding="1" style="background-color:#EDF1DA;">
			<tr style="background-color:#FAFDEC;"><td colspan="8"><div align="center">file in folder upload </div></td></tr>
			<tr style="background-color:#FAFDEC;text-align:center">
				<td width="36">No.</td>
				<td width="">fileName</td>
				<td>วันที่ของข้อมูล</td>
				<td>ผู้อัพโหลด</td>
				<td>วันเวลาที่อัพโหลด</td>
				<td>จำนวนรายการ</td>
				<td width="65">detail</td>
			</tr>
		  
			<?php	
			//หาธนาคารทั้งหมดที่จะให้แสดง
			$qrybank=pg_query("SELECT 	distinct sbj_channel ,pg_catalog.concat(COALESCE(b.\"BName\", ''::character varying),',',COALESCE(b.\"BAccount\", ''::character varying)),b.\"BBranch\"
			FROM finance.thcap_statement_bank_job a
			LEFT JOIN \"BankInt\" b ON a.\"sbj_channel\"::integer = b.\"BID\"
			order by sbj_channel");
			$sumnub=0;
			while($resbank=pg_fetch_array($qrybank)){
				$bankname="";
				list($BID,$bankname,$bankbranch)=$resbank;
				if($bankbranch!=""){
					$bankname=$bankname.",$bankbranch";
				}
				
				echo "<tr><td colspan=\"7\" bgcolor=#CDC8B1>ช่องทาง : $bankname</td></tr>";
				
				//ดึงข้อมูลจากฐานข้อมูลมาแสดง
				$qry_sel = pg_query("	SELECT 	sbj_serial,b.\"fullname\", a.\"doerStamp\",a.sbj_date ,a.sbj_filename
										FROM finance.thcap_statement_bank_job a
										LEFT JOIN \"Vfuser\" b ON a.\"doerID\" = b.\"id_user\"
										WHERE a.\"sbj_channel\"='$BID'
										ORDER BY a.sbj_date DESC");
				$n=0;
				while($ressel=pg_fetch_array($qry_sel)){
					$n++;
					$sumnub++;
					list($sbj_serial,$fullname,$time,$lfb_date,$lfb_filename) = $ressel;
					
					//ตรวจสอบว่ามีไฟล์ชื่อนี้ในเครื่องหรือไม่
					if (file_exists($dir."/".$lfb_filename)) {
					   $color="#FFFFFF";
					}else{
					   $color="#FFE4E1";
					}
					
					//หาว่าแต่ละไฟล์มีทั้งหมดกี่รายการ
					$qrynub=pg_query("select * from finance.thcap_statement_bank_raw WHERE sbr_refjob = '$sbj_serial'");
					$numrows=pg_num_rows($qrynub);
				?>
					<tr style="background-color:<?php echo $color;?>">
						<td align="center"><?php echo $n; ?></td>
						<td><?php echo $lfb_filename.'<br>'; ?></td>
						<td align="center"><?php echo $lfb_date; ?></td>
						<td><?php echo $fullname; ?></td>
						<td><?php echo $time; ?></td>
						<td align="center"><?php echo $numrows; ?></td>
						<td align="center"><img src="images/detail.gif" width="19" height="19" style="cursor:pointer;" onclick="javascript:popU('frm_Detail.php?sbj_serial=<?php echo $sbj_serial;?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1150,height=800')"></td>										
					</tr>
					<?php
				}
			}
			if($sumnub>0){
				echo "<tr><td colspan=\"7\" bgcolor=#CDC8B1>รวมทั้งหมด <b>$sumnub</b> รายการ</td></tr>";
			}
			?>
		</table>
		</div>
	</div>
</div>
</body>
</html>

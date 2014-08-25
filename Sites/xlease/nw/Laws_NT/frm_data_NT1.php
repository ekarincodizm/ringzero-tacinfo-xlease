<?php
include("../../config/config.php");
$contractID = trim($_GET['contractID']);
$date = nowDate();


	$sql1 = pg_query("SELECT * FROM \"vthcap_ContactCus_detail\" where \"contractID\" = '$contractID' and \"CusState\" = '0'");
	$re1 = pg_fetch_array($sql1);
	
	$sql2 = pg_query("SELECT * FROM \"thcap_NT1_temp\" where \"contractID\" = '$contractID' order by \"NT_tempID\" DESC limit 1");
	$row2 = pg_num_rows($sql2);
	$re2 = pg_fetch_array($sql2);
						
					
$i = 0;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>- NT1 -</title>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
};
</script>
<style type="text/css">
    #warppage
	{
	width:800px;
	margin-left:auto;
	margin-right:auto;
	
	min-height: 5em;
	background: rgb(240, 240, 240);
	padding: 5px;
	border: rgb(128, 128, 128) solid 0.5px;
	border-radius: .625em;
	-moz-border-radius: .625em;
	-webkit-border-radius: .625em;
	}
.style1 {
	font-size: small;
	font-weight: bold;
}
.style2 {
	font-size: medium;
	font-weight: bold;
}
</style>
</head>

<body>
<form name="frm" action="process_NT1.php" method="POST">
<table width="650" frame="border" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td align="center">
		<h2>ออก NT เตือน 1</h2>				
		</td>
	</tr>
</table>	
<table width="650" border="0" cellspacing="1" cellpadding="3" align="center">
	<tr>
		<td colspan="2">
			เลขที่สัญญา : <?php echo $re2['contractID']; ?>
		</td>
		<td align="right">
			วันที่ :  <?php echo $re2['NT_1_Date']; ?>
		</td>
	</tr>
	<tr>
		<td align="right">
			รหัส NT : 
		</td>
		<td>
			<?php echo $re2['NT_1_ID']; ?>
		</td>
	</tr>
	<tr>
		<td align="right">
			เรื่อง : 
		</td>
		<td>
			<?php echo $re2['NT_1_Header']; ?>
		</td>
	</tr>
	<tr>
		<td align="right">
			เรียน : 
		</td>
		<td>
			<?php echo $re1['thcap_fullname'];?> ผู้กู้ / จำนอง
		</td>		
	</tr>
<?php
			$sql3 = pg_query("SELECT * FROM \"vthcap_ContactCus_detail\" where \"contractID\" = '$contractID' and \"CusState\" = '1'");
			while($re3 = pg_fetch_array($sql3)){
			$i++;
?>
	<tr>
		<td align="right">
			 
		</td>
		<td>
			<?php echo $re3['thcap_fullname'];?> ผู้กู้
		</td>		
	</tr>
<?php 		} ?>

	<tr>
	<tr>
		<td align="right">
			ทนายผู้รับมอบอำนาจ :
		</td>
		<td>
			<?php echo $re2['NT_1_Lawyer_Name']; ?>
		</td>
	</tr>
		<td align="right">
			กำหนดให้ชำระภายใน :
		</td>
		<td>
			 <?php echo $re2['NT_1_Payin']; ?> วัน
		</td>
	</tr>
	<tr>
		<td align="right">
			ค่าเสียหายจากการติดตาม :
		</td>
		<td>
			 <?php echo $re2['NT_1_Track']; ?> บาท
		</td>
	</tr>
	<tr>
		<td align="right">
			ค่าทนายความ :
		</td>
		<td>
			 <?php echo $re2['NT_1_Proctor']; ?> บาท
		</td>
	</tr>
	<tr>
		<td align="center" colspan="3">
			<input type="button" value=" ปิด " style="height:50px; width:70px;" onclick="window.close();">
		</td>		
	</tr>
</table>
</form>		
</body>	
<?php
include("../../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../../index.php");
    exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>(THCAP)ตรวจสอบที่อยู่สัญญา</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="../act.css"></link>
<link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>   
<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
<style type="text/css">
    #warppage
	{
	width:800px;
	margin-left:auto;
	margin-right:auto;
	
	min-height: 5em;
	background: rgb(255, 255, 255);
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
<div style="margin-top:1px" ></div>
<body>

<form name="frm" action="frm_data.php" method="post">
<table width="650" border="0" cellspacing="0" cellpadding="0"  align="center" bgcolor="#99CCFF">
		<tr>
			<td  align="center" height="25px" colspan="2" bgcolor="#B9D3EE">
				<h1><b>(THCAP)ตรวจสอบที่อยู่สัญญา</b><h1>
				
			</td>
		</tr>
		<tr>
			<td  width="70px">		
			</td>
			<td>
				<div style="padding-top:10px;"></div>
				รายการสัญญาที่ไม่มีที่อยู่ มีดังนี้    <font color="#8B4513">(*กดเลขที่สัญญาเพื่อแก้ไข)</font>
			</td>
		</tr>
		<tr>
			<td  align="center" colspan="2">
				<div id="warppage" style="width:500px;">										
							<div style="height:100%; width:100%; text-align:center; margin-top:17px; margin-right:auto;">
									<?php $qry_query = pg_query(" SELECT \"contractID\" FROM thcap_contract 
																  WHERE ( \"contractID\" NOT IN (SELECT \"contractID\" FROM \"thcap_addrContractID\" where \"addsType\" = '3'))
																  OR  ( \"contractID\" IN ( SELECT \"contractID\" FROM \"thcap_addrContractID\" 
																						    WHERE  \"addsType\" = '3' AND \"A_NO\" IS NULL AND \"A_SUBNO\" IS NULL AND \"A_BUILDING\" IS NULL 
																							AND \"A_ROOM\" IS NULL AND \"A_FLOOR\" IS NULL AND \"A_VILLAGE\" IS NULL AND \"A_SOI\" IS NULL 
																							AND \"A_RD\" IS NULL AND \"A_TUM\" IS NULL AND \"A_AUM\" IS NULL AND \"A_PRO\" IS NULL AND \"A_POST\" IS NULL))
																  AND (\"contractID\" NOT IN (SELECT \"contractID\" FROM \"thcap_addrContractID_temp\" where \"statusApp\" = '2'))  
																  ORDER BY \"contractID\"");
										  $rows_num = pg_num_rows($qry_query);
										  $i=1;
										  IF($rows_num > 0){
											  while($re_query = pg_fetch_array($qry_query)){										  
													$contractID = $re_query["contractID"];
													if($i%3==0){
														echo "<span onclick=\"javascript:popU('../frm_EditAddress.php?conid=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"  >
															  <font color=\"red\"><u>$contractID</u></font></span> <p>";
													}else if($i == $rows_num){
														echo "<span onclick=\"javascript:popU('../frm_EditAddress.php?conid=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"  >
															  <font color=\"red\"><u>$contractID</u></font></span>";
													}else{
														echo "<span onclick=\"javascript:popU('../frm_EditAddress.php?conid=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"  >
															  <font color=\"red\"><u>$contractID</u></font></span> |";
													}
												$i += 1;	
											 }
										}else{
											echo "ไม่มีเลขที่สัญญาใดที่ไม่มีที่อยู่";
										}
									 ?>		 
							</div>						
				</div>
			</td>
		</tr>
		<tr>
			
			<td align="center" colspan="2">
				<div style="padding-top:15px;"></div>
				<?php echo "จำนวนสัญญาที่ไม่มีที่อยู่ : <font color=\"red\" size=\"3px\"><b>$rows_num</b></font>"; ?>
				<div style="padding-top:15px;"></div>
				<input type="button" onclick="window.close();" value="ปิด" style="height:40px;width:80px" >
			</td>
		</tr>
</table>
</form>
</body>
</html>
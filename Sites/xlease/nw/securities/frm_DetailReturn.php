<?php
session_start();
include("../../config/config.php");		
$numid2=$_POST["numid"]; 
$numid=explode("#",$numid2);

if($numid2==""){
	echo "<div align=center><h2>กรุณาเลือกรายการที่ต้องการให้แสดง!!</h2></div>";
	echo "<meta http-equiv='refresh' content='3; URL=frm_IndexShowLink.php'>";
}else{
$qry_linksecur=pg_query("select * from \"nw_linksecur\" where numid='$numid[0]'");
$res_linksecur=pg_fetch_array($qry_linksecur);
$note=$res_linksecur["note"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<title><?php echo $_SESSION["session_company_name"]; ?></title>
<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body style="background-color:#ffffff; margin-top:0px;" onload="document.getElementById('number_running').focus();">
<form>
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
	<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
		<h1 class="style4">+ คืนหลักทรัพย์ค้ำประกัน +</h1>
	</div>
	<div id="warppage"  style="width:800px; text-align:left; margin-left:auto; margin-right:auto;padding:10px;">
	<!--<form name="frm_edit" method="post" action="#">-->
		<table width="785" border="0" cellpadding="1" cellspacing="1" bgcolor="#CECECE">
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" width="210" style="font-weight:bold;">รหัสเชื่อมโยง : </td>
			<td bgcolor="#FFFFFF"><?php echo $numid[0];?></td>
		</tr>
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" valign="top"style="font-weight:bold;">หลักทรัพย์  : </td>
			<td colspan="3" bgcolor="#FFFFFF">
				<table width="100%" border="0" cellpadding="3" cellspacing="0" border="0">
					<?php
						$qry_sec=pg_query("select * from \"nw_linknumsecur\" a
						left join \"nw_securities\" b on a.\"securID\"=b.\"securID\"
						where a.numid='$numid[0]'");
						$num_sec=pg_num_rows($qry_sec);
						
						$i=1;
						while($res_sec=pg_fetch_array($qry_sec)){
							$cancel=$res_sec["cancel"];
							$securID=$res_sec["securID"];
							if($cancel=="t"){
								$txtcancel="<font color=red><b>(คืนหลักทรัพย์ให้ลูกค้าแล้ว)</b></font>";
							}else{
								//ตรวจสอบว่ารออนุมัติคืนอยู่หรือไม่
								$qrycheck=pg_query("SELECT * FROM temp_securities_reqreturns WHERE \"securID\"='$securID' and \"statusApp\"='2'");
								$numcheck=pg_num_rows($qrycheck);
								if($numcheck>0){
									$txtcancel="<font color=red><b>(อยู่ในระหว่างรออนุมัติคืนหลักทรัพย์)</b></font>";
								}else{
									$txtcancel="";
								}
							}
					?>
						<tr>
							<td valign="top"> เลขที่โฉนด
							<span onclick="javascript:popU('showdetail2.php?securID=<?php echo $securID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด"><u><b><?php echo $res_sec["numDeed"];?></b></u></span> <?php echo $txtcancel?>
							<?php 
							if($numcheck==0 and $cancel=="f"){
								echo "<input type=button value=\"ขอคืนเอกสารหลักทรัพย์\" onclick=\"javascript:popU('frm_ReceiveReturn.php?securID=$securID&numid=$numid[0]','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=300')\">";
							}
							?>
							</td>
						</tr>
						<?php $i++;}?>
				</table>
			</td>
		</tr>
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" valign="top" width="210" style="font-weight:bold;">เลขที่สัญญา  : </td>
			<td colspan="3" bgcolor="#FFFFFF">
				<table width="100%" border="0" cellpadding="3" cellspacing="0" border="0">
					<?php
						$qry_idno=pg_query("select * from \"nw_linkIDNO\" where numid='$numid[0]'");
						$num_idno=pg_num_rows($qry_idno);
						
						$i=1;
						while($res_idno=pg_fetch_array($qry_idno)){
							$IDNO=$res_idno["IDNO"];
							$qry_fp=pg_query("select \"P_ACCLOSE\" from \"Fp\" where \"IDNO\"='$IDNO'");
							$res_fp=pg_fetch_array($qry_fp);
							$P_ACCLOSE=$res_fp["P_ACCLOSE"];
							if($P_ACCLOSE=="t"){
								$txtclose="<font color=red><b>(ปิดบัญชีแล้ว)</b></font>";
							}else{
								$txtclose="";
							}
					?>
						<tr>
							<td>
								เลขที่สัญญา : <span onclick="javascript:popU('../../post/frm_viewcuspayment.php?idno_names=<?php echo $IDNO; ?>&type=outstanding','<?php echo "$IDNO_outstanding"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" style="cursor: pointer;" title="แสดงรายละเอียด"><u><b><?php echo $IDNO;?></b></u></span> วันที่ค้ำประกัน : <?php echo $res_idno["guaranteeDate"]." ".$txtclose;?> 
							</td>
						</tr>
						<?php $i++;}?>
				</table>
			</td>
		</tr>
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" valign="top" width="210" style="font-weight:bold;">หมายเหตุ : </td>
			<td colspan="3" bgcolor="#FFFFFF"><div style="padding:0px 0px 20px;"><textarea name="note" id="note" cols="40" rows="5" readonly="true"><?php echo $note?></textarea></div></td>
		</tr>
		<tr>
			<td colspan="4" height="40" align="center" bgcolor="#FFFFFF"><input type="button" value="BACK" onclick="window.location='frm_IndexReturnSecur.php'"><input type="button" value="CLOSE" onclick="window.close();"></td>
		</tr>
		</table>
	</div>
</div>
</body>
</html>
<?php }?>

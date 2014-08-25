<?php
session_start();
include("../config/config.php");	
$IDNO=pg_escape_string($_GET["idno"]);



$qry_data_guan=pg_query("select distinct(a.\"numid\") as numid2 from \"nw_linksecur\" a left join \"nw_linkIDNO\" c on a.\"numid\"=c.\"numid\" where c.\"IDNO\" = '$IDNO'");
$rowschk = pg_num_rows($qry_data_guan);

			
if($rowschk == 0){
	echo "<div align=center><h1>สัญญา $IDNO ไม่มีการใช้หลักทรัพย์ค้ำประกัน</h1></div>";
}else{	
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
<div id="5g" style="margin-top:10px;" align="center"><h2>สัญญา <?php echo $IDNO; ?> มีหลักทรัพย์ค้ำประกัน <?php echo $rowschk; ?> หลักทรัพย์</h2></div>
<body style="background-color:#ffffff; margin-top:0px;">
<?php

while($qry_data_re=pg_fetch_array($qry_data_guan)){	
	$numid=$qry_data_re["numid2"]; 


	$qry_linksecur=pg_query("select \"note\" from \"nw_linksecur\" where numid='$numid'");

?>

<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
	<div id="warppage"  style="width:800px; text-align:left; margin-left:auto; margin-right:auto;padding:10px;">

<?php 
		while($res_linksecur=pg_fetch_array($qry_linksecur)){
			$note=$res_linksecur["note"];
?>
		<table width="785" border="0" cellpadding="1" cellspacing="1" bgcolor="#CECECE">
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" width="210" style="font-weight:bold;">รหัสเชื่อมโยง : </td>
			<td bgcolor="#FFFFFF"><?php echo $numid;?></td>
		</tr>
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" valign="top"style="font-weight:bold;">หลักทรัพย์  : </td>
			<td colspan="3" bgcolor="#FFFFFF">
				<table width="100%" border="0" cellpadding="3" cellspacing="0" border="0">
					<?php
						$qry_sec=pg_query("select \"cancel\",a.\"securID\",\"proName\",\"district\",\"area_acre\",\"area_ngan\",\"area_sqyard\",\"numDeed\" from \"nw_linknumsecur\" a
						left join \"nw_securities\" b on a.\"securID\"=b.\"securID\"
						left join nw_province c on b.\"proID\" = c.\"proID\"
						where a.numid='$numid'");
						$num_sec=pg_num_rows($qry_sec);
						
						$i=1;
						while($res_sec=pg_fetch_array($qry_sec)){
							$cancel=$res_sec["cancel"];
							$securID=$res_sec["securID"];
							$proName=$res_sec["proName"];
							list($tum,$aum)=explode("/",$res_sec["district"]);
							$area_acre=number_format($res_sec["area_acre"]);
							$area_ngan=number_format($res_sec["area_ngan"]);
							$area_sqyard=number_format($res_sec["area_sqyard"]);
							if($area_acre != ""){
								$area_acre = $area_acre." ไร่";
							}
							if($area_ngan != ""){
								$area_ngan = $area_ngan." งาน";
							}
							if($area_sqyard != ""){
								$area_sqyard = $area_sqyard." ตารางวา";
							}
							if($tum != ""){
								$tum = "ต.".$tum;
							}
							if($aum != ""){
								$aum = "อ.".$aum;
							}
							if($proName != ""){
								$proName = "จ.".$proName;
							}
							
							
							if($cancel=="t"){
								$txtcancel="<font color=red><b>(คืนหลักทรัพย์ให้ลูกค้าแล้ว)</b></font>";
							}else{
								$txtcancel="";
							}
					?>
						<tr>
							<td valign="top"> เลขที่โฉนด
							<span onclick="javascript:popU('../nw/securities/showdetail2.php?securID=<?php echo $securID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด"><u><b><?php echo $res_sec["numDeed"];?></b></u></span> <?php echo $txtcancel?>
							<?php echo $tum." ".$aum." ".$proName." ( ".$area_acre." ".$area_ngan." ".$area_sqyard." )"; ?>
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
						$qry_idno=pg_query("select \"IDNO\",\"guaranteeDate\" from \"nw_linkIDNO\" where numid='$numid'");
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
								เลขที่สัญญา : <span onclick="javascript:popU('frm_viewcuspayment.php?idno_names=<?php echo $IDNO; ?>&type=outstanding','<?php echo "$IDNO_outstanding"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" style="cursor: pointer;" title="แสดงรายละเอียด"><u><b><?php echo $IDNO;?></b></u></span> วันที่ค้ำประกัน : <?php echo $res_idno["guaranteeDate"]." ".$txtclose;?> 
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
		</table>
<?php } ?>		
	</div>
</div>
<?php } ?>	
</body>
</html>
<?php }?>

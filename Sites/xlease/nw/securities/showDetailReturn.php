<?php
session_start();
include("../../config/config.php");		
 
$auto_id=$_GET["auto_id"]; 

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
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
function confirmappv(no){
	if(no=='1'){
		if(confirm('ยืนยันการอนุมัติ')==true){
			return true;
		}else{return false;}
	}
	else if(no=='0'){
		if(confirm('ยืนยันการไม่อนุมัติ!!')==true){
			return true;
		}else{return false;}
	}else{	
		return false;
	}
} 
</script>
</head>
<body style="background-color:#ffffff; margin-top:0px;" onload="document.getElementById('number_running').focus();">
<?php
//ตรวจสอบรายการว่าอนุมัติไปก่อนหน้านี้หรือยัง
$qrycheck=pg_query("select a.\"securID\",\"fullname\" as \"userRequest\",\"full_name\" as \"cusReceive\",\"dateRequest\",a.\"returnDate\",a.\"numid\"
			from \"temp_securities_reqreturns\" a
			left join \"Vfuser\" b on a.\"userRequest\"=b.\"id_user\"
			left join \"VSearchCusCorp\" c on a.\"CusIDReceiveReturn\"=c.\"CusID\"
			left join \"nw_securities\" d on a.\"securID\"=d.\"securID\"
			WHERE auto_id='$auto_id' and \"statusApp\"='2'");
$numrowchk = pg_num_rows($qrycheck);

if($numrowchk==0){ //อนุมัติแล้ว
	echo "<div align=center><h2>รายการนี้ได้รับการอนุมัติไปก่อนหน้านี้แล้ว กรุณาตรวจสอบ!!</h2></div>";
	echo "<input type=\"submit\" value=\"  ปิด  \" onclick=\"javascript:RefreshMe();\" />";
}else{  //ยังไม่อนุมัติ
	$res=pg_fetch_array($qrycheck);
	$numid=$res["numid"];
	$securID_new=$res["securID"];
	$userRequest = $res["userRequest"]; 
	$cusReceive = $res["cusReceive"];
	$dateRequest = $res["dateRequest"];
	$returnDate = $res["returnDate"];
?>

<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
	<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
		<h2>รายละเอียดการใช้หลักทรัพย์</h2>
	</div>
	<div id="warppage"  style="width:800px; text-align:left; margin-left:auto; margin-right:auto;padding:10px;">
		<div>
			<div style="float:left;"><b>ผู้ขอรับคืน :</b> <?php echo $cusReceive;?> <b>วันที่รับคืน : <?php echo $returnDate;?></div>
			<div style="float:right"><span onclick="window.close();" style="cursor:pointer"><u>X ปิดหน้านี้</u></span></div>
		</div>
		<div style="clear:both;"></div>
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
						$qry_sec=pg_query("select * from \"nw_linknumsecur\" a
						left join \"nw_securities\" b on a.\"securID\"=b.\"securID\"
						where a.numid='$numid'");
						$num_sec=pg_num_rows($qry_sec);
						
						$i=1;
						while($res_sec=pg_fetch_array($qry_sec)){
							$cancel=$res_sec["cancel"];
							$securID=$res_sec["securID"];
							if($cancel=="t"){
								$txtcancel="<b>(คืนหลักทรัพย์ให้ลูกค้าแล้ว)</b>";
							}else{
								$txtcancel="";
							}
					?>
						<tr>
							<td valign="top"> เลขที่โฉนด
							<span onclick="javascript:popU('showdetail2.php?securID=<?php echo $securID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด"><u><b><?php echo $res_sec["numDeed"];?></b></u></span>
							<?php
							if($securID_new==$securID){
								echo "<font color=red size=3> (หลักทรัพย์ที่ต้องการคืน)</font>";
							}
							echo $txtcancel;
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
						$qry_idno=pg_query("select * from \"nw_linkIDNO\" where numid='$numid'");
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
		<tr>
			<td colspan="4" height="40" align="center" bgcolor="#FFFFFF">
				<!--input type="button" value="อนุมัติ" onclick="if(confirm('ยืนยันการอนุมัติ!!')){location.href='process_approve_return.php?auto_id=<?php echo $auto_id; ?>&stsapp=1'}">&nbsp;
				<input type="button" value="ไม่อนุมัติ" onclick="if(confirm('ยืนยันการไม่อนุมัติ!!')){location.href='process_approve_return.php?auto_id=<?php echo $auto_id; ?>&stsapp=0'}"-->
			<form method="post" action="process_approve_return.php">	
				<input type="hidden" name="auto_id" id="auto_id" value="<?php echo $auto_id; ?>">			
				<input name="appv" type="submit" value="อนุมัติ" onclick="return confirmappv('1');"/>
				<input name="unappv" type="submit" value="ไม่อนุมัติ"  onclick="return confirmappv('0');"/>	
			</form>
					
			</td>
		</tr>
		</table>
	</div>
</div>
</body>
</html>
<?php }?>

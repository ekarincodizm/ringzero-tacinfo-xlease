<?php
session_start();
include("../../config/config.php");	
$securID=$_GET["securID"];

$qry_sec=pg_query("select \"guaranID\",\"numDeed\",\"numBook\",\"numPage\",\"numLand\",\"pageSurvey\",
\"district\",\"proID\",\"area_acre\",\"area_ngan\",\"area_sqyard\",
\"note",\"condoroomnum\",\"condofloor\",\"condobuildingnum\",\"condobuildingname\",
\"condoregisnum\",\"area_smeter\"	 from \"nw_securities\" where \"securID\" ='$securID' ");
$qry_sec_addr=pg_query("select  \"S_NO\", \"S_SUBNO\", \"S_BUILDING\", \"S_ROOM\", \"S_FLOOR\", 
            \"S_VILLAGE\", \"S_SOI\", \"S_RD\", \"S_TUM\", \"S_AUM\", \"S_PRO\", \"S_POST\" from \"nw_securities_address\" where \"securID\" ='$securID' "); // ดึงที่อยู่มาด้วยนะ


$res_sec=pg_fetch_array($qry_sec);
$guaranID=trim($res_sec["guaranID"]);
$numDeed=trim($res_sec["numDeed"]);
$numBook=trim($res_sec["numBook"]);
$numPage=trim($res_sec["numPage"]);
$numLand=trim($res_sec["numLand"]);
$pageSurvey=trim($res_sec["pageSurvey"]);
$district=trim($res_sec["district"]);
$proID=trim($res_sec["proID"]);	
$area_acre=trim($res_sec["area_acre"]);	
$area_ngan=trim($res_sec["area_ngan"]);
$area_sqyard=trim($res_sec["area_sqyard"]);	
//$edittime=trim($res_sec["edittime"]);
$note=$res_sec["note"];

$condoroomnum=$res_sec["condoroomnum"];
$condofloor = $res_sec["condofloor"]; 
$condobuildingnum = $res_sec["condobuildingnum"];
$condobuildingname = $res_sec["condobuildingname"];
$condoregisnum = $res_sec["condoregisnum"];
$area_smeter=trim($res_sec["area_smeter"]);	

$res_sec_addr=pg_fetch_array($qry_sec_addr);
			
			$S_BUILDING=trim($res_sec_addr["S_BUILDING"]);
			$S_ROOM=trim($res_sec_addr["S_ROOM"]);
			$S_FLOOR=trim($res_sec_addr["S_FLOOR"]);
			$S_SOI=trim($res_sec_addr["S_SOI"]);
			$S_RD=trim($res_sec_addr["S_RD"]);
			$S_TUM=trim($res_sec_addr["S_TUM"]);	
			$S_AUM=trim($res_sec_addr["S_AUM"]);	
			$S_PRO=trim($res_sec_addr["S_PRO"]);	
			$S_POST=trim($res_sec_addr["S_POST"]);
			$S_NO=trim($res_sec_addr["S_NO"]);	
			$S_SUBNO=trim($res_sec_addr["S_SUBNO"]);	
			$S_VILLAGE=trim($res_sec_addr["S_VILLAGE"]);			
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" rel="stylesheet" href="../../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="../../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script type="text/javascript" language="JavaScript1.2" src="stm31.js"></script>
<title><?php echo $_SESSION["session_company_name"]; ?></title>

<style type="text/css">
/*
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

*/
</style>

<style type="text/css">
/*
.style6 {
	color: #FF0000;
	font-weight: bold;
}


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

*/
</style>
</head>
<body style="background-color:#ffffff; margin-top:0px;">
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
	<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
		<h1 class="style4">+ แสดงรายละเอียด +</h1>
	</div>

	<div id="warppage"  style="width:800px; text-align:left; margin-left:auto; margin-right:auto;padding:10px;">
	<div align="right" style="padding:5px"></div>
		<table width="785" border="0" cellpadding="1" cellspacing="1" bgcolor="#CECECE" style="font-weight:bold;">
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" width="200">ประเภทหลักประกัน : </td>
			<td bgcolor="#FFFFFF">
				<?php
				if($guaranID=="1"){
					echo "ที่ดิน";
				}else if($guaranID=="3"){
					echo "ที่ดินพร้อมสิ่งปลูกสร้าง";
				}else{
					echo "ห้องชุด";
				}
				?>
			</td>
			<td align="right">โฉนดที่ดินเลขที่ : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="numDeed" id="numDeed" value="<?php echo $numDeed?>" readonly="true" <?php if($numDeed1 != $numDeed2){ echo "style=\"background-color:#FFCCCC\"";}?>/></td>
		</tr>
		<?php
		if($guaranID=="1" || $guaranID=="3"){
		?>
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right">เล่มที่ : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="numBook" id="numBook" value="<?php echo $numBook?>" readonly="true" <?php if($numBook1 != $numBook2){ echo "style=\"background-color:#FFCCCC\"";}?>/></td>
			<td align="right">หน้าที่ : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="numPage" id="numPage" value="<?php echo $numPage?>" readonly="true" <?php if($numPage1 != $numPage2){ echo "style=\"background-color:#FFCCCC\"";}?>/></td>
		</tr>
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right">เลขที่ดิน : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="numLand" id="numLand" value="<?php echo $numLand?>" readonly="true" <?php if($numLand1 != $numLand2){ echo "style=\"background-color:#FFCCCC\"";}?>/></td>
			<td align="right">หน้าสำรวจ : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="pageSurvey" id="pageSurvey" value="<?php echo $pageSurvey?>" readonly="true" <?php if($pageSurvey1 != $pageSurvey2){ echo "style=\"background-color:#FFCCCC\"";}?>/></td>
		</tr>
		<?php }else{?>
		<!--รายละเอียดทีห้องชุด -->
		<tr height="30" bgcolor="#E8E8E8" id="condo1">
			<td align="right">ห้องชุดเลขที่ : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="condoroomnum" id="condoroomnum"  value="<?php echo $S_ROOM?>" readonly="true"/></td>
			<td align="right">ชั้นที่ : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="condofloor" id="condofloor"  value="<?php echo $S_FLOOR?>" readonly="true"/></td>
		</tr>
		<tr height="30" bgcolor="#E8E8E8" id="condo2">
			<td align="right">อาคารเลขที่ : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="condobuildingnum" id="condobuildingnum"  value="<?php echo $condobuildingnum?>" readonly="true"/></td>
			<td align="right">ทะเบียนอาคารชุด : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="condoregisnum" id="condoregisnum"  value="<?php echo $condoregisnum?>" readonly="true"/></td>
		</tr>
		<tr height="30" bgcolor="#E8E8E8" id="condo3">
			<td align="right">ชื่ออาคารชุด : </td>
			<td bgcolor="#FFFFFF" colspan="3"><input type="text" name="condobuildingname" id="condobuildingname" size="50"  value="<?php echo $S_BUILDING?>" readonly="true"/></td>
		</tr>
		<?php }?>		
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right">เนื้อที่ : </td>
			<?php if($guaranID=="1" || $guaranID=="3"){?>
			<td colspan="3" bgcolor="#FFFFFF">
				<input type="text" name="area_acre" id="area_acre" size="10" value="<?php echo $area_acre?>" readonly="true" <?php if($area_acre1 != $area_acre2){ echo "style=\"background-color:#FFCCCC\"";}?>/> ไร่
				<input type="text" name="area_ngan" id="area_ngan" size="10" value="<?php echo $area_ngan?>" readonly="true" <?php if($area_ngan1 != $area_ngan2){ echo "style=\"background-color:#FFCCCC\"";}?>> งาน
				<input type="text" name="area_sqyard" id="area_sqyard" size="10" value="<?php echo $area_sqyard?>" readonly="true" <?php if($area_sqyard1 != $area_sqyard2){ echo "style=\"background-color:#FFCCCC\"";}?>> ตารางวา
			</td>
			<?php }else{?>
			<td colspan="3" bgcolor="#FFFFFF" id="condo4">
				<input type="text" name="area_smeter" id="area_smeter" size="10" value="<?php echo $area_smeter?>" readonly="true"/> ตารางเมตร
			</td>
			<?php }?>
		</tr>
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" valign="top">เจ้าของกรรมสิทธิ์ : </td>
			<td colspan="3" bgcolor="#FFFFFF">
				<table width="100%" border="0" cellpadding="3" cellspacing="0" border="0" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">
					<?php
						$qry_cus=pg_query("select \"full_name\" from \"nw_securities_customer\" a
						left join \"VSearchCus\" b on a.\"CusID\"=b.\"CusID\"
						where \"securID\" ='$securID' ");
						$i=1;
						while($res_cus=pg_fetch_array($qry_cus)){
					?>
						<tr bgcolor="<?php echo $color;?>">
							<td>
								<?php echo $i.". ".$res_cus["full_name"];?>
							</td>
						</tr>
						<?php $i++;}?>
				</table>
			</td>
		</tr>
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" valign="top">ภาพโฉนด : </td>
			<td colspan="3" bgcolor="#FFFFFF">
				<table cellpadding="3" cellspacing="0" border="0" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">
					<?php
						$qry_up=pg_query("select \"upload\" from \"nw_securities_upload\" where \"securID\" ='$securID' ");
						$i=1;
						while($res_up=pg_fetch_array($qry_up)){
						$upload=$res_up["upload"];
					?>
						<tr bgcolor="#E8E8E8">
							<td>
								<a href="<?php echo "upload/$upload";?>" target="_blank">โฉนดที่ <?php echo $i;?> (<?php echo $upload;?>)</a>
							</td>
						</tr>
					<?php
						$i++;
					}
					?>
				</table>
			</td>
		</tr>
		
		<tr bgcolor="#E8E8E8">
			<td colspan="4">
				<table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#CECECE" style="font-weight:bold;">
					<tr bgcolor="#E8E8E8"><td colspan="4" align="center" ><h2> ที่อยู่หลักทรัพย์ </h2></td></tr>
					<?php if($guaranID=="1" || $guaranID=="3"){?>
					<tr height="30" bgcolor="#E8E8E8" id="land4">
						<td align="right" width="200">บ้านเลขที่ : </td>
						<td bgcolor="#FFFFFF" width="200"><input type="text" name="s_no" id="s_no" readonly="true" value="<?php echo $S_NO; ?>"  /></td>
						<td align="right" width="200">หมู่ : </td>					
						<td bgcolor="#FFFFFF"><input type="text" name="s_subno" id="s_subno" readonly="true" value="<?php echo $S_SUBNO; ?>"/></td>
					</tr>
					<tr height="30" bgcolor="#E8E8E8"  id="land5">
						<td align="right" width="200">หมู่บ้าน : </td>
						<td bgcolor="#FFFFFF" width="200" colspan="3"><input type="text" readonly="true" name="s_village" id="s_village" size="35" value="<?php echo $S_VILLAGE; ?>"  /></td>
					
					</tr>
					<?php } ?>
					<tr height="20" bgcolor="#E8E8E8">
					
						<td align="right" width="200">ซอย : </td>
						<td bgcolor="#FFFFFF"  ><input type="text" name="soi" id="soi" readonly="true" value="<?php echo $S_SOI ?>"  /></td>
						<td align="right" >ถนน : </td>					
						<td bgcolor="#FFFFFF"><input type="text" name="rd" id="rd" readonly="true" value="<?php echo $S_RD ?>" /></td>
					</tr>
					
					<tr height="30" bgcolor="#E8E8E8">
						<td align="right">จังหวัด : </td>
						<td bgcolor="<?php if($proID1==$proID2){ echo "#FFFFFF";}else{ echo "#FFCCCC";}?>">
				<?php
					$qry_pro=pg_query("select \"proName\" from \"nw_province\" where \"proID\"='$S_PRO'");
					$res_pro=pg_fetch_array($qry_pro);
					$proName=$res_pro["proName"];
					echo "$proName";
				?>
						</td>							
						<td align="right">อำเภอ : </td>					
						<td bgcolor="#FFFFFF"><input type="text" readonly="true" name="amp" id="amp" value="<?php echo $S_AUM ?>" /></td>
					</tr>
					<tr height="30" bgcolor="#E8E8E8">	
						<td align="right">ตำบล : </td>
						<td bgcolor="#FFFFFF"><input type="text" readonly="true" name="dis" id="dis" value="<?php echo $S_TUM ?>" /></td>
						</td>
						<td align="right">รหัสไปรษณีย์ : </td>
						<td bgcolor="#FFFFFF"><input type="text" readonly="true" name="post" id="post" value="<?php echo $S_POST ?>" /></td>
					</tr>
				</table>	
			</td>
		</tr>
		
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" valign="top" width="198">หมายเหตุ : </td>
			<td colspan="3" bgcolor="#FFFFFF"><textarea name="note" id="note" cols="40" rows="5" readonly="true" <?php if($note1 != $note2){ echo "style=\"background-color:#FFCCCC\"";}?>><?php echo $note;?></textarea></td>
		</tr>
        <!--
		<tr>
			<td colspan="4" height="40" bgcolor="#FFFFFF" align="center"><input type="button" value="CLOSE" onclick="window.close()"></td>
		</tr>
        -->
	</table>
	<!--</form>-->
	</div>
    <table border="0" cellpadding="1" cellspacing="1" width="785">
    	<tr>
        	<td align="center" height="40" bgcolor="#FFFFFF"><input type="button" value="CLOSE" onclick="window.close()"></td>
        </tr>
    </table>
</div>
</body>
</html>

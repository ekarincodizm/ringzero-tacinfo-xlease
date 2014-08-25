<?php
session_start();
include("../../config/config.php");	
$securID=pg_escape_string($_REQUEST["securID"]);
$auto_id=pg_escape_string($_REQUEST["auto_id"]);
$chk_show=pg_escape_string($_REQUEST["chk_show"]);
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
<script language="JavaScript" type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
function confirmappv(no){
	if(no=='1'){
		if(confirm('ยืนยันการอนุมัติ!!')){return true;}
		else{ return false;}
	}
	else{
		if(confirm('ยืนยันการไม่อนุมัติ!!')){return true;}
		else{ return false;}
	}
}
</script> 
</head>
<body style="background-color:#ffffff; margin-top:0px;">
<?php
//อันดับแรกต้องตรวจสอบข้อมูลก่อนว่าข้อมูลนี้ได้ถูกอนุมัติไปก่อนหน้านี้หรือยัง (กรณีมีผู้ใช้งานพร้อมกัน)
$qry_check=pg_query("select * from \"temp_securities\" where \"auto_id\"='$auto_id' and \"statusApp\" in('0','1')");
$num_check=pg_num_rows($qry_check);
if($num_check > 0){
	$rescheck=pg_fetch_array($qry_check);
	$check_status=trim($rescheck["statusApp"]);
	if($check_status =="1"){
		echo "<div style=\"text-align:center\"><h2>รายการนี้ได้รับการอนุมัติไปแล้ว</h2>";
		echo "<input type=\"submit\" value=\"  ตกลง  \" onclick=\"javascript:RefreshMe();\" /></div>";
	}else if($check_status =="0"){
		echo "<div style=\"text-align:center\"><h2>รายการนี้ไม่ได้รับการอนุมัติ</h2>";
		echo "<input type=\"submit\" value=\"  ตกลง  \" onclick=\"javascript:RefreshMe();\" /></div>";
	}
}else{
?>
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
	<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
		<h1 class="style4">+ เปรียบเทียบข้อมูลหลักทรัพย์ +</h1>
	</div>
<table width="100%" border="0" cellpadding="1" cellspacing="1">
<tr>	
	<?php
	for($i=0;$i<2;$i++){
		if($i==0){
			$qry_sec=pg_query("select * from \"nw_securities\" where \"securID\" ='$securID' ");
			$qry_sec_addr=pg_query("select * from \"nw_securities_address\" where \"securID\" ='$securID' ");
			$txt="ข้อมูลเก่า";
		}else{
			$qry_sec=pg_query("select * from \"temp_securities\" where \"auto_id\" ='$auto_id' ");
			$qry_sec_addr=pg_query("select * from \"temp_securities_address\" where \"temp_secid\" ='$auto_id' ");
			$txt="ข้อมูลใหม่";
		}
		$numsec=pg_num_rows($qry_sec);
		$res_sec=pg_fetch_array($qry_sec);
			$guaranID=trim($res_sec["guaranID"]);
			$numDeed=trim($res_sec["numDeed"]);
			$condoroomnum=$res_sec["condoroomnum"];
			$condofloor = $res_sec["condofloor"]; 
			$condobuildingnum = $res_sec["condobuildingnum"];
			$condobuildingname = $res_sec["condobuildingname"];
			$condoregisnum = $res_sec["condoregisnum"];
			$district=trim($res_sec["district"]);
			$proID=trim($res_sec["proID"]);	
			$area_smeter=trim($res_sec["area_smeter"]);	
			$edittime=trim($res_sec["edittime"]);
			$note=$res_sec["note"];
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

		//ดึงข้อมูลขึ้นมาอีกรอบเพื่อเปรียบเทียบ
		//ข้อมูลปัจจุบัน
		$qry_sec1=pg_query("select * from \"nw_securities\" where \"securID\" ='$securID' ");
		$res_sec1=pg_fetch_array($qry_sec1);
		$guaranID1=trim($res_sec1["guaranID"]);
		$numDeed1=trim($res_sec1["numDeed"]);
		$condoroomnum1=$res_sec1["condoroomnum"];
		$condofloor1 = $res_sec1["condofloor"]; 
		$condobuildingnum1 = $res_sec1["condobuildingnum"];
		$condobuildingname1 = $res_sec1["condobuildingname"];
		$condoregisnum1 = $res_sec1["condoregisnum"];
		$district1=trim($res_sec1["district"]);
		$proID1=trim($res_sec1["proID"]);	
		$area_smeter1=trim($res_sec1["area_smeter"]);	
		$edittime1=trim($res_sec1["edittime"]);
		$note1=$res_sec1["note"];

		//ข้อมูลที่แก้ไข
		$qry_sec2=pg_query("select * from \"temp_securities\" where \"auto_id\" ='$auto_id' ");
		$res_sec2=pg_fetch_array($qry_sec2);
		$guaranID2=trim($res_sec2["guaranID"]);
		$numDeed2=trim($res_sec2["numDeed"]);
		$condoroomnum2=$res_sec2["condoroomnum"];
		$condofloor2 = $res_sec2["condofloor"]; 
		$condobuildingnum2 = $res_sec2["condobuildingnum"];
		$condobuildingname2 = $res_sec2["condobuildingname"];
		$condoregisnum2 = $res_sec2["condoregisnum"];
		$district2=trim($res_sec2["district"]);
		$proID2=trim($res_sec2["proID"]);	
		$area_smeter2=trim($res_sec2["area_smeter"]);	
		$edittime2=trim($res_sec2["edittime"]);
		$note2=$res_sec2["note"];
	?>
	<td valign="top">
		<table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#CECECE" style="font-weight:bold;">
		<tr><td colspan="4"><b>(<?php echo $txt;?>)</b></td></tr>
		<?php
		if($numsec>0){
		?>
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right">ประเภทหลักประกัน : </td>
			<td bgcolor="#FFFFFF">
				<?php
				if($guaranID=="1"){
					$txtguaran="ที่ดิน";
				}else{
					$txtguaran="ห้องชุด";
				}
				?>
				<input type="text" name="guaranID" value="<?php echo $guaranID?>" readonly="true" <?php if($guaranID1 != $guaranID2){ echo "style=\"background-color:#FFCCCC\"";}?>>
			</td>
			<td align="right">โฉนดที่ดินเลขที่ : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="numDeed" id="numDeed" value="<?php echo $numDeed?>" readonly="true" <?php if($numDeed1 != $numDeed2){ echo "style=\"background-color:#FFCCCC\"";}?>/></td>
		</tr>
		<tr height="30" bgcolor="#E8E8E8" id="condo1">
			<td align="right">ห้องชุดเลขที่ : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="condoroomnum" id="condoroomnum" value="<?php echo $S_ROOM?>" readonly="true" <?php if($condoroomnum1 != $condoroomnum2){ echo "style=\"background-color:#FFCCCC\"";}?>/></td>
			<td align="right">ชั้นที่ : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="condofloor" id="condofloor" value="<?php echo $S_FLOOR?>" readonly="true" <?php if($condofloor1 != $condofloor2){ echo "style=\"background-color:#FFCCCC\"";}?>/></td>
		</tr>
		<tr height="30" bgcolor="#E8E8E8" id="condo2">
			<td align="right">อาคารเลขที่ : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="condobuildingnum" id="condobuildingnum" value="<?php echo $condobuildingnum?>" readonly="true" <?php if($condobuildingnum1 != $condobuildingnum2){ echo "style=\"background-color:#FFCCCC\"";}?>/></td>
			<td align="right">ทะเบียนอาคารชุด : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="condoregisnum" id="condoregisnum" value="<?php echo $condoregisnum?>" readonly="true" <?php if($condoregisnum1 != $condoregisnum2){ echo "style=\"background-color:#FFCCCC\"";}?>/></td>
		</tr>
		<tr height="30" bgcolor="#E8E8E8" id="condo3">
			<td align="right">ชื่ออาคารชุด : </td>
			<td bgcolor="#FFFFFF" colspan="3"><input type="text" name="condobuildingname" id="condobuildingname" value="<?php echo $S_BUILDING?>" readonly="true" size="50" <?php if($condobuildingname1 != $condobuildingname2){ echo "style=\"background-color:#FFCCCC\"";}?>/></td>
		</tr>	
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right">เนื้อที่ : </td>
			<td colspan="3" bgcolor="#FFFFFF" id="condo4">
				<input type="text" name="area_smeter" id="area_smeter" size="10" value="<?php echo $area_smeter?>" readonly="true" readonly="true" <?php if($area_smeter1 != $area_smeter2){ echo "style=\"background-color:#FFCCCC\"";}?>/> ตารางเมตร
			</td>
		</tr>
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" valign="top">เจ้าของกรรมสิทธิ์ : </td>
			<td colspan="3" bgcolor="#FFFFFF">
				<table width="100%" border="0" cellpadding="3" cellspacing="0" border="0" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">
					<?php
						//เปรียบเทียบค่าระหว่างอันเก่ากับอันใหม่
						$qry_old=pg_query("select * from \"nw_securities_customer\" a
							left join \"VSearchCus\" b on a.\"CusID\"=b.\"CusID\"
							where \"securID\" ='$securID' ");
						$numold=pg_num_rows($qry_old);
						
						$qry_new=pg_query("select * from \"temp_securities_customer\" a
							left join \"VSearchCus\" b on a.\"CusID\"=b.\"CusID\"
							where \"auto_id\" ='$auto_id' ");
						$numnew=pg_num_rows($qry_new);
						
						if($numold != $numnew){
							$color="#FFCCCC";
						}else{ //กรณีเท่ากัน
							$x=0;
							while($res_old=pg_fetch_array($qry_old)){
								$CusOld=trim($res_old["CusID"]);
								while($res_new=pg_fetch_array($qry_new)){
									$CusNew=trim($res_new["CusID"]);
									if($CusOld==$CusNew){
										$x=0;
										break;
									}else{
										$x++;
									}
								}	
							}
							if($x>0){
								$color="#FFCCCC";
							}else{
								$color="#E8E8E8";
							}
						}
						
						if($i==0){
							$qry_cus=pg_query("select * from \"nw_securities_customer\" a
							left join \"VSearchCus\" b on a.\"CusID\"=b.\"CusID\"
							where \"securID\" ='$securID' ");
						}else{
							$qry_cus=pg_query("select * from \"temp_securities_customer\" a
							left join \"VSearchCus\" b on a.\"CusID\"=b.\"CusID\"
							where \"auto_id\" ='$auto_id' ");
						}
						$j=1;
						while($res_cus=pg_fetch_array($qry_cus)){
					?>
						<tr bgcolor="<?php echo $color;?>">
							<td>
								<?php echo $j.". ".$res_cus["full_name"];?>
							</td>
						</tr>
						<?php $j++;}?>
				</table>
			</td>
		</tr>
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" valign="top">ภาพโฉนด : </td>
			<td colspan="3" bgcolor="#FFFFFF">
				<table cellpadding="3" cellspacing="0" border="0" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px" width="100%">
					<?php
						//เปรียบเทียบค่าระหว่างอันเก่ากับอันใหม่
						$qryup_old=pg_query("select * from \"nw_securities_upload\" where \"securID\" ='$securID'");
						$numupold=pg_num_rows($qryup_old);
						
						$qryup_new=pg_query("select * from \"temp_securities_upload\" where \"auto_id\" ='$auto_id'");
						$numupnew=pg_num_rows($qryup_new);
						
						if($numupold != $numupnew){
							$color="#FFCCCC";
						}else{ //กรณีเท่ากัน
							$y=0;
							while($resup_old=pg_fetch_array($qryup_old)){
								$upOld=trim($resup_old["upload"]);
								while($resup_new=pg_fetch_array($qryup_new)){
									$upNew=trim($resup_new["upload"]);
									if($upOld==$upNew){
										$y=0;
										break;
									}else{
										$y++;
									}
								}	
							}
							if($y>0){
								$color="#FFCCCC";
							}else{
								$color="#E8E8E8";
							}
						}
						
						if($i==0){
							$qry_up=pg_query("select * from \"nw_securities_upload\" where \"securID\" ='$securID' ");	
						}else{
							$qry_up=pg_query("select * from \"temp_securities_upload\" where \"auto_id\" ='$auto_id' ");
						}
						$p=1;
						while($res_up=pg_fetch_array($qry_up)){
						$upload=$res_up["upload"];
					?>
						<tr bgcolor="<?php echo $color;?>">
							<td>
								<a href="<?php echo "upload/$upload";?>" target="_blank">โฉนดที่ <?php echo $p;?> (<?php echo $upload;?>)</a>
							</td>
						</tr>
					<?php
						$p++;
					}
					?>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="4">
				<table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#CECECE" style="font-weight:bold;">
					<tr><td colspan="4" align="center"><h2> ที่อยู่หลักทรัพย์ </h2></td></tr>
					<tr height="20" bgcolor="#E8E8E8" >
						<td align="right" width="130">ซอย : </td>
						<td bgcolor="#FFFFFF" ><input type="text" name="soi" id="soi" readonly="true" value="<?php echo $S_SOI ?>"  <?php if($S_SOI1 != $S_SOI2){ echo "style=\"background-color:#FFCCCC\"";}?>/></td>
						<td align="right">ถนน : </td>					
						<td bgcolor="#FFFFFF"><input type="text" name="rd" id="rd" readonly="true" value="<?php echo $S_RD ?>" <?php if($S_RD1 != $S_RD2){ echo "style=\"background-color:#FFCCCC\"";}?>/></td>
					</tr>
					
					<tr height="30" bgcolor="#E8E8E8">
						<td align="right">จังหวัด : </td>
						<td bgcolor="<?php if($proID1==$proID2){ echo "#FFFFFF";}else{ echo "#FFCCCC";}?>">
				<?php
					$qry_pro=pg_query("select * from \"nw_province\" where \"proID\"='$S_PRO'");
					$res_pro=pg_fetch_array($qry_pro);
					$proName=$res_pro["proName"];
					echo "$proName";
				?>
						</td>							
						<td align="right">อำเภอ : </td>					
						<td bgcolor="#FFFFFF"><input type="text" readonly="true" name="amp" id="amp" value="<?php echo $S_AUM ?>" <?php if($S_AUM1 != $S_AUM2){ echo "style=\"background-color:#FFCCCC\"";}?>/></td>
					</tr>
					<tr height="30" bgcolor="#E8E8E8">	
						<td align="right">ตำบล : </td>
						<td bgcolor="#FFFFFF"><input type="text" readonly="true" name="dis" id="dis" value="<?php echo $S_TUM ?>" <?php if($S_TUM1 != $S_TUM2){ echo "style=\"background-color:#FFCCCC\"";}?>/></td>
						</td>
						<td align="right">รหัสไปรษณีย์ : </td>
						<td bgcolor="#FFFFFF"><input type="text" readonly="true" name="post" id="post" value="<?php echo $S_POST ?>" <?php if($S_POST1 != $S_POST2){ echo "style=\"background-color:#FFCCCC\"";}?>/></td>
					</tr>
				</table>	
			</td>
		</tr>
		
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" valign="top">หมายเหตุ : </td>
			<td colspan="3" bgcolor="#FFFFFF"><textarea name="note" id="note" cols="40" rows="5" readonly="true" <?php if($note1 != $note2){ echo "style=\"background-color:#FFCCCC\"";}?>><?php echo $note;?></textarea></td>
		</tr>
		<?php
		//กรณีไม่พบข้อมูลเก่าแสดงว่าเป็นการเพิ่มข้อมูล
		}else{
			echo "<tr colspan=\"2\"><td bgcolor=\"#FFFFFF\" width=\"400\" height=250 align=center><h2>ไม่พบข้อมูล</h2></td></tr>";
		}
		?>
	</table>
	</td>
	<?php 
	}
	?>
</tr>
<tr>
	<form method="post" action="process_approve.php">
		<td align="center" height="50" colspan="2">
		<?php if($chk_show !="true"){?>	
			<input type="hidden" name="auto_id" id="auto_id" value="<?php echo $auto_id; ?>" >
			<input type="submit" name="appv_condo" value="อนุมัติ" onclick="return confirmappv('1');">&nbsp;
			<input type="submit" name="unappv_condo" value="ไม่อนุมัติ" onclick="return confirmappv('0');">&nbsp;
		<?php }?>
			<input type="button" value="ปิดหน้านี้" onclick="window.close();"></td>		
	</form>
	</tr>
</table>
<?php
}
?>
</body>
</html>

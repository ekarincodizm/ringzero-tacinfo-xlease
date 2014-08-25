<?php
session_start();
include("../../config/config.php");

$tempID = pg_escape_string($_GET["tempID"]);
$show = pg_escape_string($_GET["show"]);

// หาเลขที่สัญญา
$qry_contractID = pg_query("select \"contractID\" from \"thcap_addrContractID_temp\" where \"tempID\"='$tempID' ");
$contractID = pg_fetch_result($qry_contractID,0);
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

function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script> 
</head>
<body style="background-color:#ffffff; margin-top:0px;">
<?php
//อันดับแรกต้องตรวจสอบข้อมูลก่อนว่าข้อมูลนี้ได้ถูกอนุมัติไปก่อนหน้านี้หรือยัง (กรณีมีผู้ใช้งานพร้อมกัน)
$qry_check=pg_query("select * from \"thcap_addrContractID_temp\" where \"tempID\"='$tempID' and \"statusApp\" ='2' and \"addsType\"='3'");
$num_check=pg_num_rows($qry_check); //ถ้าพบว่าไม่มีค่าแล้ว แสดงว่าได้ถูกอนุมัติไปแล้ว

if($num_check == 0){
	$rescheck=pg_fetch_array($qry_check);
	echo "<div style=\"text-align:center\"><h2>รายการนี้ได้รับการอนุมัติไปก่อนหน้านี้แล้ว</h2>";
	echo "<input type=\"submit\" value=\"  ตกลง  \" onclick=\"javascript:RefreshMe();\" /></div>";
}else{
?>
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
	<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
		<h1 class="style4">+ เปรียบเทียบข้อมูลการแก้ไขที่อยู่สัญญา (THCAP)+</h1>
	</div>
<table width="100%" border="0" cellpadding="1" cellspacing="1">
<tr>	
	<?php
	for($i=0;$i<2;$i++){
		if($i==0){
			$qry_sec=pg_query("select * from \"thcap_addrContractID\" 
			where \"contractID\" ='$contractID' and \"addsType\"='3'");
			$txt="ข้อมูลเก่า";
			
			$color_table="#DFDFDF";
			$color_head="#F0F0F0";
			$color1="#EAEAEA";
			$color2="#FFFFFF";
			
		}else{
			$qry_sec=pg_query("select * from \"thcap_addrContractID_temp\" 
			where \"tempID\" ='$tempID' and \"statusApp\" ='2' and \"addsType\"='3'");
			$txt="ข้อมูลใหม่";
			
			$color_table="#82C0FF";
			$color_head="#D9ECFF";
			$color1="#B5E8FB";
			$color2="#FFFFFF";
		}
		$numsec=pg_num_rows($qry_sec);
		if($resaddr=pg_fetch_array($qry_sec));
			$A_NO=$resaddr["A_NO"];
			$A_SUBNO=$resaddr["A_SUBNO"];
			$A_BUILDING=$resaddr["A_BUILDING"];
			$A_ROOM=$resaddr["A_ROOM"];
			$A_FLOOR=$resaddr["A_FLOOR"];
			$A_VILLAGE=$resaddr["A_VILLAGE"];
			$A_SOI=$resaddr["A_SOI"];
			$A_RD=$resaddr["A_RD"];
			$A_TUM=$resaddr["A_TUM"];
			$A_AUM=$resaddr["A_AUM"];
			$A_PRO=$resaddr["A_PRO"];
			$A_POST=$resaddr["A_POST"];
			$filerequest=$resaddr["filerequest"];
			$effectiveDate=$resaddr["effectiveDate"];
			
			if($i==0){
				$A_NO_OLD=$resaddr["A_NO"];
				$A_SUBNO_OLD=$resaddr["A_SUBNO"];
				$A_BUILDING_OLD=$resaddr["A_BUILDING"];
				$A_ROOM_OLD=$resaddr["A_ROOM"];
				$A_FLOOR_OLD=$resaddr["A_FLOOR"];
				$A_VILLAGE_OLD=$resaddr["A_VILLAGE"];
				$A_SOI_OLD=$resaddr["A_SOI"];
				$A_RD_OLD=$resaddr["A_RD"];
				$A_TUM_OLD=$resaddr["A_TUM"];
				$A_AUM_OLD=$resaddr["A_AUM"];
				$A_PRO_OLD=$resaddr["A_PRO"];
				$A_POST_OLD=$resaddr["A_POST"];
				$filerequest_OLD=$resaddr["filerequest"];
			}
	?>
	<td valign="top">
		<table width="500" border="0" cellpadding="1" cellspacing="1" bgcolor="<?php echo $color_table;?>" align="center">	
		<tr>
			<td bgcolor="<?php echo $color_head;?>" colspan="2"><b><?php echo "$txt เลขที่สัญญา : $contractID";?></b></td>
		</tr>
		<tr>
			<td align="right" bgcolor="<?php echo $color1;?>">ห้อง :</td>
			<td bgcolor="<?php echo $color2;?>"><input type="text" name="f_room" value="<?php echo $A_ROOM; ?>" readonly="true" <?php if($A_ROOM_OLD!=$A_ROOM){?>style="background-color:#FFDFDF"<?php } ?>/></td>
		</tr>
		<tr>
			<td align="right" bgcolor="<?php echo $color1;?>">ชั้น :</td>
			<td bgcolor="<?php echo $color2;?>"><input type="text" name="f_floor" value="<?php echo $A_FLOOR; ?>" readonly="true" <?php if($A_FLOOR_OLD!=$A_FLOOR){?>style="background-color:#FFDFDF"<?php } ?>/></td>
		</tr>
		<tr>
			<td align="right" bgcolor="<?php echo $color1;?>">เลขที่ :</td>
			<td bgcolor="<?php echo $color2;?>"><input type="text" name="f_no" value="<?php echo $A_NO; ?>" readonly="true" <?php if($A_NO_OLD!=$A_NO){?>style="background-color:#FFDFDF"<?php } ?>/></td>
		</tr>
		<tr>
			<td align="right" bgcolor="<?php echo $color1;?>">หมู่ที่ :</td>
			<td bgcolor="<?php echo $color2;?>"><input type="text" name="f_subno" value="<?php echo $A_SUBNO; ?>" readonly="true" <?php if($A_SUBNO_OLD!=$A_SUBNO){?>style="background-color:#FFDFDF"<?php } ?> /></td>
		</tr>
		<tr>
			<td align="right" bgcolor="<?php echo $color1;?>">หมู่บ้าน :</td>
			<td bgcolor="<?php echo $color2;?>"><input type="text" name="f_ban" value="<?php echo $A_VILLAGE; ?>" size="50" readonly="true" <?php if($A_VILLAGE_OLD!=$A_VILLAGE){?>style="background-color:#FFDFDF"<?php } ?>/></td>
		</tr>
		<tr>
			<td align="right" bgcolor="<?php echo $color1;?>">อาคาร/สถานที่ :</td>
			<td bgcolor="<?php echo $color2;?>"><input type="text" name="f_building" value="<?php echo $A_BUILDING; ?>" size="50" readonly="true" <?php if($A_BUILDING_OLD!=$A_BUILDING){?>style="background-color:#FFDFDF"<?php } ?> /></td>
		</tr>
		<tr>
			<td align="right" bgcolor="<?php echo $color1;?>">ซอย :</td>
			<td bgcolor="<?php echo $color2;?>"><input type="text" name="f_soi" value="<?php echo $A_SOI; ?>" readonly="true" <?php if($A_SOI_OLD!=$A_SOI){?>style="background-color:#FFDFDF"<?php } ?> /></td>
		</tr>
		<tr>
			<td align="right" bgcolor="<?php echo $color1;?>">ถนน :</td>
			<td bgcolor="<?php echo $color2;?>"><input type="text" name="f_rd" value="<?php echo $A_RD; ?>" readonly="true" <?php if($A_RD_OLD!=$A_RD){?>style="background-color:#FFDFDF"<?php } ?>/></td>
		</tr>
		<tr>
			<td align="right" bgcolor="<?php echo $color1;?>">แขวง/ตำบล :</td>
			<td bgcolor="<?php echo $color2;?>"><input type="text" name="f_tum" value="<?php echo $A_TUM; ?>"readonly="true" <?php if($A_TUM_OLD!=$A_TUM){?>style="background-color:#FFDFDF"<?php } ?>/></td>
		</tr>
		<tr>
			<td align="right" bgcolor="<?php echo $color1;?>">เขต/อำเภอ :</td>
			<td bgcolor="<?php echo $color2;?>"><input type="text" name="f_aum" value="<?php echo $A_AUM; ?>" readonly="true" <?php if($A_AUM_OLD!=$A_AUM){?>style="background-color:#FFDFDF"<?php } ?>/></td>
		</tr>
		<tr>
			<td align="right" bgcolor="<?php echo $color1;?>">จังหวัด :</td>
			<td bgcolor="<?php echo $color2;?>"><input type="text" value="<?php echo $A_PRO;?>" readonly="true" <?php if($A_PRO_OLD!=$A_PRO){?>style="background-color:#FFDFDF"<?php } ?>></td>
		</tr>
		<tr>
			<td align="right" bgcolor="<?php echo $color1;?>">รหัสไปรษณีย์ :</td>
			<td bgcolor="<?php echo $color2;?>"><input type="text" name="f_post" value="<?php echo $A_POST; ?>" maxlength="5" readonly="true" <?php if($A_POST_OLD!=$A_POST){?>style="background-color:#FFDFDF"<?php } ?>/></td>
		</tr>
		<tr>
			<td  align="right" bgcolor="<?php echo $color1;?>">ใบคำขอแก้ไขที่อยู่สัญญา :</td>
			<td bgcolor="<?php echo $color2;?>">
			<?php 
			if($filerequest!=""){
				echo "<a href=\"upload_chgcontractadds/$filerequest\" target=\"_blank\"><img src=\"images/open.png\" width=\"20\" height=\"20\" title=\"แสดงใบคำขอแก้ไข\"></a>";
			}else{
				echo "<img src=\"images/noimage.gif\" width=\"20\" height=\"20\" title=\"ไม่มีข้อมูล\">";
			}
			?>
			</td>
		</tr>
		<?php
		// ถ้าเป็นข้อมูลใหม่
		if($i==1)
		{
			// ตรวจสอบสัญญาอื่นๆที่จะถูกแก้ไขไปพร้อมกันด้วย
			$qry_contractEditToo = pg_query("select \"contractID\" from \"thcap_addrContractID_temp\" where \"withContractEdit\" = '$tempID' ");
			/*if(pg_num_rows($qry_contractEditToo) > 0)
			{*/
		?>
			<tr>
				<td align="right" bgcolor="<?php echo $color1;?>">วันที่ที่มีผลบังคับใช้ :</td>
				<td bgcolor="<?php echo $color2;?>"><input type="text" name="f_effectiveDate" value="<?php echo $effectiveDate; ?>" maxlength="5" readonly="true" /></td>
			</tr>
			<tr>
				<td  align="right" bgcolor="<?php echo $color1;?>">สัญญาที่เกี่ยวข้อง ที่จะถูกแก้ไขพร้อมกัน :</td>
				<td bgcolor="<?php echo $color2;?>">
		<?php
					// list รายการสัญญาที่เกี่ยวข้อง ที่จะถูกแก้ไขพร้อมกัน
					//$qry_list_contractEditToo = pg_query("SELECT \"contractID\" ('$contractEditToo', '0', ta_array1d_count('$contractEditToo'))");
					$otherC = 0;
					while($res_list_contractEditToo = pg_fetch_array($qry_contractEditToo))
					{
						$otherC++;
						$list_contractEditToo = $res_list_contractEditToo["contractID"];
						
						if($otherC > 1)
						{
							echo " | ";
						}
		?>
						<span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $list_contractEditToo;?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><font color="red"><u><?php echo $list_contractEditToo;?></u></font></span>
		<?php
					}
		?>
				</td>
			</tr>
		<?php
			//}
		}
		?>
		</table>
	</td>
	<?php 
		$A_NO_OLD=$A_NO;
		$A_SUBNO_OLD=$A_SUBNO;
		$A_BUILDING_OLD=$A_BUILDING;
		$A_ROOM_OLD=$A_ROOM;
		$A_FLOOR_OLD=$A_FLOOR;
		$A_VILLAGE_OLD=$A_VILLAGE;
		$A_SOI_OLD=$A_SOI;
		$A_RD_OLD=$A_RD;
		$A_TUM_OLD=$A_TUM;
		$A_AUM_OLD=$A_AUM;
		$A_PRO_OLD=$A_PRO;
		$A_POST_OLD=$A_POST;
		$filerequest_OLD=$filerequest;
	}
	?>
</tr>
<tr><td colspan="2" align="center"><font color="red"><b>* หากไม่พบสีแก้ไขที่แตกต่าง นั่นอาจจะหมายถึง "แก้ไขใบคำขอ"</b></font></td></tr>
<tr><td align="center" height="50" colspan="2">
<?php if($show!="1"){?>

		<?php 
		//จำกัดสิทธิ์ ไม่ให้ผู้ที่ข้อแก้ไขข้อมูลเป็นคนอนุมัติเอง นอกเสียจากว่ามีสิทธิ์ (emplevel) น้อยกว่าหรือเท่ากับ 1
			//หารหัสผู้บันทึกข้อมูล
				$qry_check=pg_query("select \"addUser\" from \"thcap_addrContractID_temp\" where \"tempID\"='$tempID' and \"statusApp\" ='2' and \"addsType\"='3'");
				list($addUser)=pg_fetch_array($qry_check);
			//หารหัสผู้ใช้งานปัจจุบัน	
				$Uid = $_SESSION["av_iduser"];
			//หาสิทธิ์ของผู้ใช้งานปัจจุบัน	
				$qry_user=pg_query("select * from \"Vfuser\" WHERE id_user='$Uid' ");
				$res_user=pg_fetch_array($qry_user);
				$emplevel=$res_user["emplevel"];
			
		if($addUser != $Uid OR ($addUser == $Uid AND $emplevel <= 1)){ //หากไม่ใช่คนเดียวกันให้สามารถอนุมัติได้ 
		?>
			<!--input type="button" value="อนุมัติ" onclick="if(confirm('ยืนยันการอนุมัติ!!')){location.href='process_EditAddress.php?contractID=<?php echo $contractID; ?>&stsapp=1&method=approve'}">&nbsp;
			<input type="button" value="ไม่อนุมัติ" onclick="if(confirm('ยืนยันการไม่อนุมัติ!!')){location.href='process_EditAddress.php?contractID=<?php echo $contractID; ?>&stsapp=0&method=approve'}"-->&nbsp;
			<form method="post" action="process_EditAddress.php">
				<input type="hidden" name="tempID" id="tempID" value="<?php echo $tempID; ?>">
				<input type="hidden" name="contractID" id="contractID" value="<?php echo $contractID; ?>">
				<input type="hidden" name="method" id="method" value="approve">
				<input name="appv" type="submit" value="อนุมัติ" onclick="return confirmappv('1');"/>
				<input name="unappv" type="submit" value="ไม่อนุมัติ"  onclick="return confirmappv('0');"/>	
			</form>&nbsp;
		<?php } ?>
			
		
		
<?php } ?>
<input type="button" value="ปิดหน้านี้" onclick="window.close();"></td></tr>
</table>
<?php
}
?>
</body>
</html>

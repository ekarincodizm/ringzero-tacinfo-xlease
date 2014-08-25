<?php
session_start();
include("../../config/config.php");	
$tempID=$_GET["tempID"];
$contractID=$_GET["contractID"];
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

function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script> 
</head>
<body style="background-color:#ffffff; margin-top:0px;">
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
	<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
		<h1 class="style4">+ เปรียบเทียบข้อมูลการแก้ไขที่อยู่สัญญา (THCAP)+</h1>
	</div>
<table width="100%" border="0" cellpadding="1" cellspacing="1">
<tr>	
	<?php
	for($i=0;$i<2;$i++){
		if($i==0){
			$qry_sec=pg_query("SELECT *
			  FROM \"thcap_addrContractID_temp\" a
			  inner join (select max(\"tempID\") as tempid from \"thcap_addrContractID_temp\" where \"tempID\"<'$tempID' and \"contractID\"='$contractID' and \"statusApp\" IN ('1','4') and \"addsType\"='3') b on a.\"tempID\"=b.tempid");
			$numchk=pg_num_rows($qry_sec);
			if($numchk==0){
				$statuschk=1;
			}
			$txt="ข้อมูลเก่าก่อนขอแก้ไข";
			
			$color_table="#DFDFDF";
			$color_head="#F0F0F0";
			$color1="#EAEAEA";
			$color2="#FFFFFF";
			
		}else{
			$qry_sec=pg_query("select * from \"thcap_addrContractID_temp\" 
			where \"tempID\" ='$tempID'");
			$txt="ข้อมูลใหม่ที่ขอแก้ไข";
			
			$color_table="#82C0FF";
			$color_head="#D9ECFF";
			$color1="#B5E8FB";
			$color2="#FFFFFF";
		}
		if($resaddr=pg_fetch_array($qry_sec));
			if($statuschk==1 and $i==0){
				$A_NO="";
				$A_SUBNO="";
				$A_BUILDING="";
				$A_ROOM="";
				$A_FLOOR="";
				$A_VILLAGE="";
				$A_SOI="";
				$A_RD="";
				$A_TUM="";
				$A_AUM="";
				$A_PRO="";
				$A_POST="";
				$effectiveDate="";
				$filerequest="";
				$contractEditToo = "";
			}else{
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
				$effectiveDate=$resaddr["effectiveDate"]; // วันที่ที่มีผลบังคับใช้
				$filerequest=$resaddr["filerequest"];
				$contractEditToo = $resaddr["contractEditToo"]; // สัญญาที่จะถูกแก้ไขไปพร้อมกัน
			}
			
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
				$effectiveDate_OLD=$resaddr["effectiveDate"];
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
			<td align="right" bgcolor="<?php echo $color1;?>">วันที่ที่มีผลบังคับใช้ :</td>
			<td bgcolor="<?php echo $color2;?>"><input type="text" name="f_effectiveDate" value="<?php echo $effectiveDate; ?>" maxlength="5" readonly="true" <?php if($effectiveDate_OLD!=$effectiveDate){?>style="background-color:#FFDFDF"<?php } ?>/></td>
		</tr>
		<tr>
			<td  align="right" bgcolor="<?php echo $color1;?>">ใบคำขอแก้ไขที่อยู่สัญญา</td>
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
		// ถ้าเป็นข้อมูลใหม่ ให้ตรวจสอบสัญญาอื่นๆที่จะถูกแก้ไขไปพร้อมกันด้วย
		if($i==1 && $contractEditToo != "")
		{
		?>
			<tr>
				<td  align="right" bgcolor="<?php echo $color1;?>">สัญญาที่เกี่ยวข้อง ที่จะถูกแก้ไขพร้อมกัน :</td>
				<td bgcolor="<?php echo $color2;?>">
		<?php
					// list รายการสัญญาที่เกี่ยวข้อง ที่จะถูกแก้ไขพร้อมกัน
					$qry_list_contractEditToo = pg_query("SELECT ta_array1d_get('$contractEditToo', '0', ta_array1d_count('$contractEditToo'))");
					$otherC = 0;
					while($res_list_contractEditToo = pg_fetch_array($qry_list_contractEditToo))
					{
						$otherC++;
						$list_contractEditToo = $res_list_contractEditToo["ta_array1d_get"];
						
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
<input type="button" value="ปิดหน้านี้" onclick="window.close();"></td></tr>
</table>
</body>
</html>

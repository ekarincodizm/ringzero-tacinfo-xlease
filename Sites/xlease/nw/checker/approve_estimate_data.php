<?php
session_start();
$_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
if($_SESSION["session_company_code"]=="AVL")
{
 $file_namepic="logo_av.jpg";
}
else
{
 $file_namepic="logo_thaiace.jpg";
}
include("../../config/config.php");
$check = $_GET['check'];

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<body>
<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
		
<?php 
if($check == "admin"){
		$id = $_GET['securdeID'];
		$sql = pg_query("select * from \"temp_securities_detail\" where \"securdeID\" = '$id'");
		$re = pg_fetch_array($sql);
		$id_user = $re['id_user'];
		$CusID = $re['CusID'];
		$auditorID = $re['id_auditor'];
		$showdeed = $re['securID'];	
			$ssql = pg_query("select * from \"nw_securities\" where \"securID\" = '$showdeed'");
			$showdeed1 = pg_fetch_array($ssql);
			$deed = "โฉนดเลขที่ ".$showdeed1['numDeed'];
			?>
			
		<div style="float:left">&nbsp;</div>
		<div class="style2" id="super_head" style="padding-left:10px; height:90px; width:800px;"><span class="style2" style="padding-left:10px; height:60px; width:800px; "><div style="width:90px; float:left;"><img src="../../images/<?php echo $file_namepic; ?>" width="80" height="80" /></div><div style="padding-top:20px;">
		<span><?php echo $_SESSION["session_company_name"]; ?></span><br /><?php echo $_SESSION["session_company_thainame"]; ?></div></div>
		<div style="clear:both;"></div>
			
			<?php

			
			
}else if($check == "user"){
	
			$in_id = $_GET['sname'];
			list($id,$deed,$land) = explode("@",$in_id);
			

			if($id == ""){
			
				echo "<hr width=850>";
				echo "<center><h1> ไม่มีข้อมูล ...</h1></center>";
				exit();
			}
			
				$sql6 = @pg_query("select * from \"nw_securities\" where \"securID\" = '$id'");
				$row6 = @pg_num_rows($sql6);
				if($row6 == 0){
						echo "<hr width=850>";
						echo "<center><h1> ไม่พบข้อมูล... </h1></center>";
						exit();
				}
					$sql = pg_query("select * from \"nw_securities_detail\" where \"securID\" = '$id'");
					$row = pg_num_rows($sql);
					
					
					
					if($row == 0){
					
					
						$sql1 = pg_query("select * from \"approve_securities_detail\" where \"securID\" = '$id' and (\"status\" = '0' OR \"status\" = '2')");
						$row1 = pg_num_rows($sql1);
							if($row1 == 0){
				
								echo "<hr width=850>";
								echo "<center><h1> ยังไม่มีการเพิ่มการประเมิน </h1></center>";
								?> 
								<center><input type="button" value="เพิ่มการประเมินหลักทรัพย์" onclick="parent.location.href='estimate.php?deed=<?php echo $deed ?>&securID=<?php echo $id ?>'"></center>						
								<?php
								exit();
							}else{
							
								echo "<hr width=850>";
								echo "<center><h1> มีการประเมินหลักทรัพย์นี้ไปแล้ว กำลังอยู่ในระหว่างการรออนุมัติ </h1></center>";
								exit();
							}
					}else{
					
							$re = pg_fetch_array($sql);
							$id_user = $re['id_user'];
							$CusID = $re['CusID'];
							$auditorID = $re['id_auditor'];
							

					}
}else{
	
		echo "<hr width=850>";
		echo "<center><h1> ไม่มีข้อมูล ...</h1></center>";
		exit();
}			

		//พนักงานคีย์ข้อมูล
			$strSQL3 = "SELECT * FROM \"fuser\" where \"id_user\" = '$id_user'";
			$objQuery3 = pg_query($strSQL3);
			$results3 = pg_fetch_array($objQuery3);
				
		//ลูกค้า
			$strSQL4 = "SELECT * FROM \"Fa1\" where \"CusID\" = '$CusID'";
			$objQuery4 = pg_query($strSQL4);
			$results4 = pg_fetch_array($objQuery4);
			
		//พนักงานคีย์ข้อมูล
			$strSQL5 = "SELECT * FROM \"fuser\" where \"id_user\" = '$auditorID'";
			$objQuery5 = pg_query($strSQL5);
			$results5 = pg_fetch_array($objQuery5);
?>



<center><legend><h2>... Checker Department ...</h2></legend></center>
<center><legend><h3> บันทึกการตรวจสอบและการประเมินราคาหลักทรัพย์ </h3></legend></center>

<?php if($check == "user"){ ?>
<div align="center"><input type="button" name="bt_edit" id="bt_edit" style="width:100px;height:30px;" value="แก้ไขข้อมูล" onclick="parent.location.href='estimate_edit.php?securID=<?php echo $id;?>'"></div>
<?php } ?>
<hr width="850">
	<table width="850" cellSpacing="1" cellPadding="2" border="1"  align="center">
			<tr>
				<td align="center" colspan="2"><h2><b><?php echo $deed; ?></b></h2></td>
			</tr>
			<tr>
				<td align="left" >ชื่อลูกค้า : <?php echo $results4["A_NAME"]." ".$results4["A_SIRNAME"];?></td>
				<td align="left" >ผู้ตรวจสอบ : <?php echo $results5["fname"]." ".$results5["lname"];?></td>
			</tr>
			<tr>
				<td align="left" >พนักงานเพิ่มข้อมูล: <?php echo $results3["fname"]." ".$results3["lname"];?></td>
				
				<?php   $datesame=$re['date'];
						list($year,$month,$day)=explode("-",$datesame);
						$year1 = $year + 543;
						$date = $day."-".$month."-".$year1;
				
				?>
				<td align="left" >วันที่สำรวจ : <?php echo $date;?></td>
			</tr>
	</table>
	<table width="850" cellSpacing="0" cellPadding="0" border="0" align="center">
			<tr>
				<td><legend><h4> ลักษณะของสิ่งปลูกสร้าง/อาคาร/โครงสร้างตัวอาคาร </h4></legend></td>
			</tr>
	</table>
	<table width="850" cellSpacing="1" cellPadding="2" frame="BORDER" align="center">
			<tr>			
				<td width="50" align="left">ลักษณะ : </td>		
				<td width="200"><input type="radio" disabled  name="feature" id="feature1" value="1" <?php if($re['feature'] == 1){ echo "checked"; } ?> >ตึกแถวสูง <input type="text" readonly="true" size="7" name="height1" value=<?php if($re['feature'] == 1){ echo $re['height']; } ?>> ชั้น</td>
				<td width="200"><input type="radio" disabled  name="feature" id="feature2" value="2" <?php if($re['feature'] == 2){ echo "checked"; } ?> >ทาวน์เฮ้าส์ สูง <input type="text" readonly="true" size="7" name="height2" value=<?php if($re['feature'] == 2){ echo $re['height']; } ?>> ชั้น</td>
				<td width="200"><input type="radio" disabled  name="feature" id="feature3" value="3" <?php if($re['feature'] == 3){ echo "checked"; } ?> >บ้านเดี่ยวตึก สูง  <input type="text" readonly="true" size="7" name="height3" value=<?php if($re['feature'] == 3){ echo $re['height']; } ?>> ชั้น</td>			
			</tr>
			<tr>
			
				<td></td>
				<td><input type="radio" disabled  name="feature" id="feature4" value="4" <?php if($re['feature'] == 4){ echo "checked"; } ?> >บ้านแฝด สูง<input type="text" readonly="true" size="7" name="height4" value=<?php if($re['feature'] == 4){ echo $re['height']; } ?>> ชั้น</td>
				<td><input type="radio" disabled  name="feature" id="feature5" value="5" <?php if($re['feature'] == 5){ echo "checked"; } ?> >อาคารพาณิชย์ สูง<input type="text" readonly="true" size="7" name="height5"value=<?php if($re['feature'] == 5){ echo $re['height']; } ?>> ชั้น</td>
				<td colspan="2"><input type="radio" disabled  name="feature" id="feature6" value="6" <?php if($re['feature'] == 6){ echo "checked"; } ?> >อื่นๆ <input type="text" readonly="true" size="7" name="feature_other" value=<?php if($re['feature'] == 6){ echo $re['feature_other']; } ?>> สูง <input type="text" readonly="true" size="7" name="height6" value=<?php if($re['feature'] == 6){ echo $re['height']; } ?>> ชั้น</td>			
			</tr>
			<tr>
								
				<td colspan="2">ขนาดอาคาร <input type="text" readonly="true" size="12" name="size_build" value=<?php echo $re['size_build']; ?> > เมตร</td>
				<td colspan="2">พื้นที่ใช้สอยรวม <input type="text" readonly="true" size="12" name="size_area" value=<?php echo $re['size_area']; ?> > ตารางเมตร</td>		
			</tr>
			<tr>
							
				<td colspan="4"> โครงสร้างหลักของอาคาร <input type="text" readonly="true" size="20" name="struncture_build" value=<?php echo $re['structure_build']; ?>></td>
					
			</tr>
	</table>
	
	<table width="850" cellSpacing="1" cellPadding="2" frame="BORDER" align="center">
			
			<tr bgcolor="#DFE6EF">
				<td width="150" align="center">ฝาผนัง</td>
				<td width="150" align="center">พื้นชั้นบน</td>
				<td width="150" align="center">พื้นชั้นล่าง</td>
				<td width="150" align="center">โครงหลังคา</td>
				<td width="150" align="center">วัสดุมุงหลังคา</td>
				
			</tr>
			<tr>
			</tr>
			<tr>
				<td><input type="checkbox" disabled  name="wall_brick" id="wall_brick" <?php if($re['wall_brick'] == 1){ echo "checked"; } ?>>ก่ออิฐ</td>
				<td><input type="checkbox" disabled  name="ground_top_con" id="ground_top_con" <?php if($re['ground_top_con'] == 1){ echo "checked"; } ?>>คอนกรีตปูด้วย</td>
				<td><input type="checkbox" disabled  name="ground_bot_con" id="ground_bot_con" <?php if($re['ground_bot_con'] == 1){ echo "checked"; } ?>>คอนกรีตปูด้วย</td>
				<td><input type="checkbox" disabled  name="roof_frame_iron" id="roof_frame_iron" <?php if($re['roof_frame_iron'] == 1){ echo "checked"; } ?>>เหล็ก</td>
				<td><input type="checkbox" disabled  name="roof_zine" id="roof_zine" <?php if($re['roof_zine'] == 1){ echo "checked"; } ?>>สังกะสี</td>
				
			</tr>
			<tr>
				<td><input type="checkbox" disabled  name="wall_wood_brick" id="wall_wood_brick" <?php if($re['wall_wood_brick'] == 1){ echo "checked"; } ?>>ก่ออิฐ/ไม้</td>
				<td><input type="checkbox" disabled  name="ground_top_wood" id="ground_top_wood" <?php if($re['ground_top_wood'] == 1){ echo "checked"; } ?>>ไม้</td>
				<td><input type="checkbox" disabled  name="ground_bot_wood" id="ground_bot_wood" <?php if($re['ground_bot_wood'] == 1){ echo "checked"; } ?>>ไม้</td>
				<td><input type="checkbox" disabled  name="roof_frame_con"  id="roof_frame_con" <?php if($re['roof_frame_con'] == 1){ echo "checked"; } ?>>คอนกรีต</td>
				<td><input type="checkbox" disabled  name="roof_deck" id="roof_deck" <?php if($re['roof_deck'] == 1){ echo "checked"; } ?>>ดาดฟ้า</td>
				
			</tr>
			<tr>
				<td><input type="checkbox" disabled  name="wall_wood" id="wall_wood" <?php if($re['wall_wood'] == 1){ echo "checked"; } ?>>ไม้</td>				
				<td><input type="checkbox" disabled  name="ground_top_parquet" id="ground_top_parquet" <?php if($re['ground_top_parquet'] == 1){ echo "checked"; } ?>>ปาร์เก้</td>
				<td><input type="checkbox" disabled  name="ground_bot_parquet" id="ground_bot_parquet" <?php if($re['ground_bot_parquet'] == 1){ echo "checked"; } ?>>ปาร์เก้</td>
				<td><input type="checkbox" disabled  name="roof_frame_wood" id="roof_frame_wood" <?php if($re['roof_frame_wood'] == 1){ echo "checked"; } ?>>ไม้</td>
				<td><input type="checkbox" disabled  name="roof_tile_duo" id="roof_tile_duo" <?php if($re['roof_tile_duo'] == 1){ echo "checked"; } ?>>กระเบื้องลอนคู่</td>
				
			</tr>
			<tr>
				<td><input type="checkbox" disabled  name="wall_other" id="wall_other" <?php if($re['wall_other'] == 1){ echo "checked"; } ?>>อื่นๆ <input type="text" readonly="true" size="10" name="wall_other_detail" <?php if($re['wall_other'] == 1){ ?> value=<?php echo $re['wall_other_detail']; } ?>></td>
				<td><input type="checkbox" disabled  name="ground_top_ceramic"  id="ground_top_ceramic" <?php if($re['ground_top_ceramic'] == 1){ echo "checked"; } ?>>เซรามิค</td>
				<td><input type="checkbox" disabled  name="ground_bot_ceramic" id="ground_bot_ceramic" <?php if($re['ground_bot_ceramic'] == 1){ echo "checked"; } ?>>เซรามิค</td>
				<td><input type="checkbox" disabled  name="roof_frame_unknow" id="roof_frame_unknow" <?php if($re['roof_frame_unknow'] == 1){ echo "checked"; } ?>>ตรวจสอบไม่ได้</td>
				<td><input type="checkbox" disabled  name="roof_tile_monern" id="roof_tile_monern" <?php if($re['roof_tile_monern'] == 1){ echo "checked"; } ?>>กระเบื้องโมเนียร์</td>
			</tr>
			<tr>
				<td></td>
				<td><input type="checkbox" disabled  name="ground_top_other" id="ground_top_other" <?php if($re['ground_top_other'] == 1){ echo "checked"; } ?>>อื่นๆ <input type="text" readonly="true" size="10" name="ground_top_other_detail" <?php if($re['ground_top_other'] == 1){ ?> value=<?php echo $re['ground_top_other_detail']; } ?>></td>
				<td><input type="checkbox" disabled  name="ground_bot_other" id="ground_bot_other" <?php if($re['ground_bot_other'] == 1){ echo "checked"; } ?>>อื่นๆ <input type="text" readonly="true" size="10" name="ground_bot_other_detail" <?php if($re['ground_bot_other'] == 1){ ?> value=<?php echo $re['ground_bot_other_detail']; } ?>></td>
				<td><input type="checkbox" disabled  name="roof_frame_other" id="roof_frame_other" <?php if($re['roof_frame_other'] == 1){ echo "checked"; } ?>>อื่นๆ<input type="text" readonly="true" size="10" name="roof_frame_other_detail" <?php if($re['roof_frame_other'] == 1){ ?> value=<?php echo $re['roof_frame_other_detail']; } ?>></td>
				<td><input type="checkbox" disabled  name="roof_other" id="roof_other" <?php if($re['roof_other'] == 1){ echo "checked"; } ?>>อื่นๆ<input type="text" readonly="true" size="10" name="roof_other_detail" <?php if($re['roof_other'] == 1){ ?> value=<?php echo $re['roof_other_detail']; } ?>></td>
			</tr>
			<tr bgcolor="#DFE6EF"> 
				<td align="center">ฝ้าเพดาน</td>
				<td align="center">ประตู</td>
				<td align="center">หน้าต่าง</td>
				<td align="center">ห้องน้ำ และสุขภัณฑ์</td>
				<td align="center">จำนวนคูหา/หลัง</td>
			</tr>
			<tr>
				<td><input type="checkbox" disabled  name="ceiling_gypsum" <?php if($re['ceiling_gypsum'] == 1){ echo "checked"; } ?>>ยิปซั่มบอร์ดฉาบเรียบ</td>
				<td><input type="checkbox" disabled  name="door_wood" <?php if($re['door_wood'] == 1){ echo "checked"; } ?>>บานเปิดไม้</td>
				<td><input type="checkbox" disabled  name="window_open_glass" <?php if($re['window_open_glass'] == 1){ echo "checked"; } ?>>บานเปิดกระจก</td>
				<td><input type="checkbox" disabled  name="rest_wc" <?php if($re['rest_wc'] == 1){ echo "checked"; } ?>>โถชักโครก</td>
				<td align="left"><input type="text" readonly="true" size="15" name="quan_cave" value=<?php echo $re['quan_cave']; ?>> คูหา</td>
			</tr>
			<tr>
				<td><input type="checkbox" disabled  name="ceiling_tile" <?php if($re['ceiling_tile'] == 1){ echo "checked"; } ?>>กระเบื้องแผ่นเรียบ</td>
				<td><input type="checkbox" disabled  name="door_glass" <?php if($re['door_glass'] == 1){ echo "checked"; } ?>>บานเปิดกระจก</td>
				<td><input type="checkbox" disabled  name="window_slide_glass" <?php if($re['window_slide_glass'] == 1){ echo "checked"; } ?>>บานเลื่อนกระจก</td>
				<td><input type="checkbox" disabled  name="rest_basin" <?php if($re['rest_basin'] == 1){ echo "checked"; } ?>>อ่างล้างหน้า</td>
				<td align="left">(**2)<input type="text" readonly="true" size="10" name="quan_units" value=<?php echo $re['quan_unit']; ?>> หลัง</td>
			</tr>
			<tr>
				<td><input type="checkbox" disabled  name="ceiling_structure" <?php if($re['ceiling_structure'] == 1){ echo "checked"; } ?>>คสล.</td>
				<td><input type="checkbox" disabled  name="door_plywood" <?php if($re['door_plywood'] == 1){ echo "checked"; } ?>>ไม้อัด</td>
				<td><input type="checkbox" disabled  name="window_scale_glass" <?php if($re['window_scale_glass'] == 1){ echo "checked"; } ?>>บานเกล็ดกระจก</td>
				<td><input type="checkbox" disabled  name="rest_tub" <?php if($re['rest_tub'] == 1){ echo "checked"; } ?>>อ่างอาบน้ำ</td>
				<td align="left"><input type="text" readonly="true" size="15" name="quan_room" value=<?php echo $re['quan_room']; ?>> ห้อง</td>
			</tr>
			<tr>
				<td><input type="checkbox" disabled  name="ceiling_nothing" <?php if($re['ceiling_nothing'] == 1){ echo "checked"; } ?>>ไม่มี</td>
				<td><input type="checkbox" disabled  name="door_iron" <?php if($re['door_iron'] == 1){ echo "checked"; } ?>>เหล็ก</td>
				<td><input type="checkbox" disabled  name="window_wood" <?php if($re['window_wood'] == 1){ echo "checked"; } ?>>กระจกกรอบไม้</td>
				<td><input type="checkbox" disabled  name="rest_other" <?php if($re['rest_other'] == 1){ echo "checked"; } ?>>อื่นๆ<input type="text" readonly="true" size="10" name="rest_other_detail" <?php if($re['rest_other'] == 1){ ?> value=<?php echo $re['rest_other_detail']; } ?>></td>
				<td align="left"></td>
			</tr>
			<tr>
				<td><input type="checkbox" disabled  name="ceiling_other" <?php if($re['ceiling_other'] == 1){ echo "checked"; } ?>>อื่นๆ <input type="text" readonly="true" size="10" name="ceiling_other_detail" <?php if($re['ceiling_other'] == 1){ ?> value=<?php echo $re['ceiling_other_detail']; } ?>></td>
				<td><input type="checkbox" disabled  name="door_other" <?php if($re['door_other'] == 1){ echo "checked"; } ?>>อื่นๆ <input type="text" readonly="true" size="10" name="door_other_detail" <?php if($re['door_other'] == 1){ ?> value=<?php echo $re['door_other_detail']; } ?>></td>
				<td><input type="checkbox" disabled  name="window_other" <?php if($re['window_other'] == 1){ echo "checked"; } ?>>อื่นๆ<input type="text" readonly="true" size="10" name="window_other_detail" <?php if($re['window_other'] == 1){ ?> value=<?php echo $re['window_other_detail']; } ?>></td>
				<td></td>
				<td align="left">อยู่ชั้นที่ <input type="text" readonly="true" size="10" name="floor_number" value=<?php echo $re['floor_number']; ?>></td>
			</tr>
			<tr >
				<td colspan="5">
					<table width="850" cellSpacing="1" cellPadding="2"  align="center">
						<tr bgcolor="#DFE6EF">
							<td width="150" align="left">อุปกรณ์ดับเพลิง</td>
							<td width="150" align="left">ความสูงภายในห้อง</td>
							<td width="150" align="left">พื้นที่ภายในอาคาร</td>
							<td width="150" align="center">ระยะห่างของหลังคา</td>
						</tr>
						<tr>
							<td  align="left">
								<input type="radio" disabled  name="fire" <?php if($re['fire'] == 1){ echo "checked"; } ?>> มี 
								<input type="radio" disabled  name="fire" <?php if($re['fire'] == 2){ echo "checked"; } ?>> ไม่มี
							</td>
							<td  align="left"><input type="text" readonly="true" size="10" name="room_height" value=<?php echo $re['room_height']; ?>> เมตร</td>
							<td  align="left"><input type="text" readonly="true" size="10" name="build_inside_area" value=<?php echo $re['build_inside_area']; ?>> ตรม.</td>
							<td  align="center">(**1)<input type="text" readonly="true" size="10" name="roof_interval" value=<?php echo $re['roof_interval']; ?>> เมตร</td>
						</tr>
						<tr>
							<td align="left" colspan="3">
								<div style="float:right;">จำนวนโฉนดที่ </div>
							</td>						
							<td align="center">(**3)<input type="text" readonly="true" size="10" name="deed_quantity" value=<?php echo $re['Deed_quantity']; ?> > ฉบับ</td>							
						</tr>
						<tr>
							<td align="left" colspan="2"><u>ราคาประเมินข้างเคียง</u>
								<input type="text" readonly="true" size="20" name="cost_near" value=<?php echo $re['cost_near']; ?>> บาท
							<td align="left" colspan="2"><u>ราคาประเมินของเช็คเกอร์</u>
								<input type="text" readonly="true" size="20" name="cost_checker" value=<?php echo $re['cost_checker']; ?>> บาท
							</td>							
						</tr>
					</table>
					<table width="850" cellSpacing="1" cellPadding="2"  align="center">
						<tr>			
							<td width="70" align="left"><u>หมายเหตุ </u></td>
							<td align="left" colspan="3">
								**1. กรณีเป็นบ้านเดี่ยวต้องแจ้งว่า หลังคาบ้านมีระยะห่างกับบ้านที่อยู่ใกล้เคียง 4 ทิศ เป็นระยะห่างกี่เมตร
							</td>							
						</tr>
						<tr>
							<td></td>
							<td align="left" colspan="3">
								**2. กรณีบ้านหลายหลังตั้งอยู่ในโฉนดเดียวกัน ต้องแจ้งรายละเอียดของบ้านแต่ละหลังที่อยู่ในบริเวณนั้นๆด้วย
							</td>							
						</tr>
						<tr>
							<td></td>
							<td align="left" colspan="3">
								**3. กรณีโฉนดหลายฉบับ และมีบ้านตั้งอยู่บนโฉนดเหล่านั้น ต้องแจ้งรายละเอียดเพิ่มเติมด้วย
							</td>	
						</tr>
					</table>
				</td>
			</tr>
	</table>
	<table width="850" cellSpacing="1" cellPadding="2"  align="center">
		<tr>
			<td width="20"></td>
			<td width="200"></td>
			<td width="200"></td>
			<td width="200"></td>
			<td width="200"></td>
		</tr>
		<tr>
			<td>1.</td>
			<td colspan="5">ประเภทเอกสารสิทธิ์</td>
		</tr>
		<tr>
			<td></td>
			<td><input type="radio" disabled  name="typedocument" <?php if($re['typedocument'] == 1){ echo "checked"; } ?>> ที่ดินว่างเปล่า</td>
			<td><input type="radio" disabled  name="typedocument" <?php if($re['typedocument'] == 2){ echo "checked"; } ?>> ที่ดิน+บ้าน</td>
			<td><input type="radio" disabled  name="typedocument" <?php if($re['typedocument'] == 3){ echo "checked"; } ?>> คอนโด</td>
			<td></td>			
		</tr>
		<tr>
			<td></td>
			<td>ประเภทอาคารบ้าน <input type="text" readonly="true" size="3" name="typebuild_floor" value=<?php echo $re['typebuild_floor']; ?>> ชั้น</td>
			<td><input type="radio" disabled  name="typebuild"  <?php if($re['typebuild'] == 3){ echo "checked"; } ?>> ปูน+ไม้</td>
			<td><input type="radio" disabled  name="typebuild"  <?php if($re['typebuild'] == 2){ echo "checked"; } ?>> ไม้</td>
			<td><input type="radio" disabled  name="typebuild"  <?php if($re['typebuild'] == 1){ echo "checked"; } ?>> ปูน</td>			
		</tr>
		<tr>
			<td></td>
			<td colspan="4">
				ขนาดสิ่งปลูกสร้าง กว้าง <input type="text" readonly="true" size="5" name="size_build_width" value=<?php echo $re['size_build_width']; ?>> เมตร	
				ยาว <input type="text" readonly="true" size="5" name="size_build_long" value=<?php echo $re['size_build_long']; ?>> เมตร
			</td>			
		</tr>
		<tr>
			<td>2.</td>
			<td colspan="5">ชื่อผู้ถือกรรมสิทธิ์  <input type="text" readonly="true" size="20" name="deed_owner" value=<?php echo $re['deed_owner']; ?>></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="radio" disabled  name="deed_owner_area" <?php if($re['deed_owner_area'] == 1){ echo "checked"; } ?>> ทั้งแปลง</td>
			<td colspan="3">
				<input type="radio" disabled  name="deed_owner_area" <?php if($re['deed_owner_area'] == 2){ echo "checked"; } ?>> เฉพาะส่วน
				ในอัตราส่วน <input type="text" readonly="true" size="10" name="deed_owner_area_size1" <?php if($re['deed_owner_area'] == 2){ ?> value=<?php echo $re['deed_owner_area_size1']; }?>> ใน <input type="text" readonly="true" size="10"name="deed_owner_area_size2" <?php if($re['deed_owner_area'] == 2){ ?> value=<?php echo $re['deed_owner_area_size2']; } ?>> ส่วน
			</td>			
		</tr>
		<tr>
			<td>3.</td>
			<td colspan="5">สถานที่ตั้ง </td>
		</tr>
		<tr>
			<td></td>
			<td colspan="5"><textarea readonly rows="5" cols="80" name="address"><?php echo $re['address']; ?></textarea></td>
		</tr>
		<tr>
			<td>4.</td>
			<td colspan="5">ตำแหน่งที่ตั้งที่ดิน </td>			
		</tr>
		<tr>
			<td></td>
			<td colspan="2"><input type="checkbox" disabled  name="position_address_right_map"  <?php if($re['position_add_right_map'] == 1){ echo "checked"; } ?>> ถูกต้องตรงกับรูปแผนที่ในหนังสือแสดงสิทธิ์</td>
			<td colspan="2"><input type="checkbox" disabled  name="position_address_fail_map"  <?php if($re['position_add_fail_map'] == 1){ echo "checked"; } ?>> ไม่ตรงกับรูปแผนที่ในหนังสือแสดงสิทธิ์</td>			
		</tr>
		<tr>
			<td></td>
			<td colspan="2"><input type="checkbox" disabled  name="position_address_success"  <?php if($re['position_add_success'] == 1){ echo "checked"; } ?>> พบหลักเขตที่ดิน</td>
			<td colspan="2"><input type="checkbox" disabled  name="position_address_fail"  <?php if($re['position_add_fail'] == 1){ echo "checked"; } ?>> ไม่พบหลักเขตที่ดิน</td>			
		</tr>
		<tr>
			<td></td>
			<td colspan="4"><input type="checkbox" disabled  name="position_address_navigation"  <?php if($re['position_add_navigation'] == 1){ echo "checked"; } ?>> ผู้นำชี้ คือ <input type="text" readonly="true" size="20" name="position_address_navigation_name" <?php if($re['position_add_navigation'] == 1){ ?> value=<?php echo $re['position_add_navigation_name']; } ?>></td>		
		</tr>
		<tr>
			<td>5.</td>
			<td colspan="5">รูปร่างของที่ดิน และสภาพของที่ดิน </td>			
		</tr>
		<tr>
			<td></td>
			<td>รูปร่างของที่ดิน</td>
			<td><input type="checkbox" disabled  name="land_shape_rectangle" <?php if($re['land_shape_rectangle'] == 1){ echo "checked"; } ?>> สี่เหลี่ยมผืนผ้า</td>
			<td><input type="checkbox" disabled  name="land_shape_square" <?php if($re['land_shape_square'] == 1){ echo "checked"; } ?>> สี่เหลี่ยมจัตุรัส</td>
			<td><input type="checkbox" disabled  name="land_shape_trapezuid" <?php if($re['land_shape_trapezuid'] == 1){ echo "checked"; } ?>> สี่เหลี่ยมคางหมู</td>	
					
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td><input type="checkbox" disabled  name="land_shape_triangle" <?php if($re['land_shape_triangle'] == 1){ echo "checked"; } ?>> สามเหลี่ยม</td>	
			<td colspan="2"><input type="checkbox" disabled  name="land_shape_polygon" <?php if($re['land_shape_polygon'] == 1){ echo "checked"; } ?>> หลายเหลี่ยม</td>						
		</tr>
		<tr>
			<td></td>
			<td>สภาพของที่ดิน</td>
			<td><input type="checkbox" disabled  name="land_state_coverall" <?php if($re['land_state_coverall'] == 1){ echo "checked"; } ?>> ถมแล้วทั้งแปลง</td>
			<td colspan="2"><input type="checkbox" disabled  name="land_state_cover" <?php if($re['land_state_cover'] == 1){ echo "checked"; } ?>> ถมบางส่วน ประมาณ 
			<?php list($a,$b)=explode("/",$re['land_state_cover_about']); ?>
			<input type="text" readonly="true" size="5" name="land_state_cover_about1" <?php if($re['land_state_cover'] == 1){ ?> value=<?php echo $a; } ?>> ใน 
			<input type="text" readonly="true" size="5" name="land_state_cover_about2" value=<?php echo $b; ?>> ส่วนของพื้นที่</td>				
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td colspan="3">
				<input type="checkbox" disabled  name="land_state_hole" <?php if($re['land_state_hole'] == 1){ echo "checked"; } ?>> เป็นบ่อลึกประมาณ <input type="text" readonly="true" size="3" name="land_state_hole_about1" <?php if($re['land_state_hole'] == 1){ ?> value=<?php echo $re['land_state_hole_about1']; } ?>> เมตร  คิดเป็นเนื้อที่ประมาณ <input type="text" readonly="true" size="5" name="land_state_hole_about2" <?php if($re['land_state_hole'] == 1){ ?> value=<?php echo $re['land_state_hole_about2']; } ?>> ไร่
			</td>				
		</tr>
		<tr>
			<td></td>
			<td>ระดับของดิน</td>
			<td><input type="checkbox" disabled  name="land_level_match" <?php if($re['land_level_match'] == 1){ echo "checked"; } ?>> สูงเสมอถนน</td>
			<td colspan="2"><input type="checkbox" disabled  name="land_level_height" <?php if($re['land_level_height'] == 1){ echo "checked"; } ?>> สูงกว่าถนน ประมาณ <input type="text" readonly="true" size="10" name="land_level_height_about" <?php if($re['land_level_height'] == 1){ ?> value=<?php echo $re['land_level_height_about']; } ?>> เมตร</td>				
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td colspan="3">
				<input type="checkbox" disabled  name="land_level_low" <?php if($re['land_level_low'] == 1){ echo "checked"; } ?>> ต่ำกว่าถนนประมาณ  <input type="text" readonly="true" size="3" name="land_level_low_about" <?php if($re['land_level_low'] == 1){ ?> value=<?php echo $re['land_level_low_about']; } ?>> เมตร
			</td>				
		</tr>
		<tr>
			<td>6.</td>
			<td colspan="5">การคมนาคมผ่านด้านหน้าทรัพย์สิน </td>			
		</tr>
		<tr>
			<td></td>
			<td><input type="radio" disabled  name="communication" <?php if($re['communication'] == 1){ echo "checked"; } ?>>อยู่ริมถนนหลัก</td>
			<td><input type="radio" disabled  name="communication" <?php if($re['communication'] == 2){ echo "checked"; } ?>>อยู่ริมถนนย่อย</td>
			<td><input type="radio" disabled  name="communication" <?php if($re['communication'] == 3){ echo "checked"; } ?>>ที่ตาบอด</td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td>ประเภทถนน</td>
			<td><input type="radio" disabled  name="road_type" <?php if($re['road_type'] == 1){ echo "checked"; } ?>> สาธารณะ</td>
			<td><input type="radio" disabled  name="road_type" <?php if($re['road_type'] == 2){ echo "checked"; } ?>> ของโครงการ </td>	
			<td><input type="radio" disabled  name="road_type" <?php if($re['road_type'] == 3){ echo "checked"; } ?>> ส่วนบุคคล </td>
			<td></td>	
		</tr>
		<tr>
			<td></td>
			<td>ลักษณะถนน</td>
			<td><input type="radio" disabled  name="road_state" <?php if($re['road_state'] == 1){ echo "checked"; } ?>> คอนกรีตเสริมเหล็ก</td>
			<td><input type="radio" disabled  name="road_state" <?php if($re['road_state'] == 2){ echo "checked"; } ?>> ลาดยาง</td>	
			<td><input type="radio" disabled  name="road_state" <?php if($re['road_state'] == 3){ echo "checked"; } ?>> ลูกรัง</td>	
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td><input type="radio" disabled  name="road_state" <?php if($re['road_state'] == 4){ echo "checked"; } ?>> หินคลุก</td>
			<td><input type="radio" disabled  name="road_state" <?php if($re['road_state'] == 5){ echo "checked"; } ?>> ดิน</td>
			<td><input type="radio" disabled  name="road_state" <?php if($re['road_state'] == 6){ echo "checked"; } ?>> อื่นๆ <input type="text" readonly="true" size="15" name="road_state_detail" <?php if($re['road_state'] == 6){ ?> value=<?php echo $re['road_state_detail']; } ?>></td>				
		</tr>
		<tr>
			<td></td>
			<td colspan="4">ระยะทางจากหน้าถนนถึงหน้าทรัพย์สินประมาณ <input type="text" readonly="true" size="10" name="roadtobuild" value=<?php echo $re['road_to_build']; ?> > เมตร</td>					
		</tr>
		<tr>
			<td></td>
			<td>สภาพของถนน</td>	
			<td><input type="radio" disabled  name="road_status" <?php if($re['road_status'] == 1){ echo "checked"; } ?>> ดี</td>
			<td><input type="radio" disabled  name="road_status" <?php if($re['road_status'] == 2){ echo "checked"; } ?>"> ปานกลาง</td>	
			<td><input type="radio" disabled  name="road_status" <?php if($re['road_status'] == 3){ echo "checked"; } ?>> ชำรุด</td>				
		</tr>
		<tr>
			<td></td>
			<td>รถยนต์สามารถเข้าถึงทรัพย์สิน</td>
			<td colspan="3">
				<input type="radio" disabled  name="road_vehicles" <?php if($re['road_vehicles'] == 1){ echo "checked"; } ?>> ได้
				<input type="radio" disabled  name="road_vehicles" <?php if($re['road_vehicles'] == 2){ echo "checked"; } ?>>ไม่ได้  เนื่องจากเป็นทางแคบรถยนต์ไม่สามารถเข้า-ออกได้
			</td>							
		</tr>
		<tr>
			<td>7.</td>
			<td colspan="5">การใช้ประโยชน์ปัจจุบัน </td>			
		</tr>
		<tr>
			<td></td>
			<td><input type="checkbox" disabled  name="useful_home" <?php if($re['useful_home'] == 1){ echo "checked"; } ?>> ที่อยู่อาศัย</td>	
			<td><input type="checkbox" disabled  name="useful_commerce" <?php if($re['useful_commerce'] == 1){ echo "checked"; } ?>> พาณิชยกรรม</td>
			<td><input type="checkbox" disabled  name="useful_rent" <?php if($re['useful_rent'] == 1){ echo "checked"; } ?>> ให้เช่า</td>	
			<td><input type="checkbox" disabled  name="useful_stored" <?php if($re['useful_stored'] == 1){ echo "checked"; } ?>> เก็บไว้เฉยๆ</td>				
		</tr>
		<tr>
			<td></td>
			<td><input type="checkbox" disabled  name="useful_industry" <?php if($re['useful_industry'] == 1){ echo "checked"; } ?>> อุตสาหกรรม</td>	
			<td><input type="checkbox" disabled  name="useful_agriculture" <?php if($re['useful_agriculture'] == 1){ echo "checked"; } ?>> เกษตรกรรม</td>
			<td colspan="2"><input type="checkbox" disabled  name="useful_other" <?php if($re['useful_other'] == 1){ echo "checked"; } ?>> อื่นๆ <input type="text" readonly="true" size="10" name="useful_other_detail" <?php if($re['useful_other'] == 1){ ?> value=<?php echo $re['useful_other_detail']; } ?>></td>							
		</tr>
		<tr>
			<td>8.</td>
			<td>สาธารณูปโภค</td>	
			<td><input type="radio" disabled  name="utilities" <?php if($re['utilities'] == 1){ echo "checked"; } ?>> มี</td>	
			<td><input type="radio" disabled  name="utilities" <?php if($re['utilities'] == 2){ echo "checked"; } ?>> ไม่มี</td>			
		</tr>
		<tr>
			<td></td>
			<td><input type="checkbox" disabled  name="utilities_electricity" <?php if($re['utilities_electricity'] == 1){ echo "checked"; } ?>> ไฟฟ้า</td>	
			<td><input type="checkbox" disabled  name="utilities_plumbing" <?php if($re['utilities_plumbing'] == 1){ echo "checked"; } ?>> ประปา</td>
			<td><input type="checkbox" disabled  name="utilities_phone" <?php if($re['utilities_phone'] == 1){ echo "checked"; } ?>> โทรศัพท์</td>	
			<td><input type="checkbox" disabled  name="utilities_drain" <?php if($re['utilities_drain'] == 1){ echo "checked"; } ?>> ท่อระบายน้ำ</td>				
		</tr>
		<tr>
			<td></td>
			<td><input type="checkbox" disabled  name="utilities_groundwater" <?php if($re['utilities_groundwater'] == 1){ echo "checked"; } ?>> น้ำบาดาล</td>	
			<td><input type="checkbox" disabled  name="utilities_electricroad" <?php if($re['utilities_electri_road'] == 1){ echo "checked"; } ?>> ไฟฟ้าถนน</td>						
		</tr>
		<tr>
			<td>9.</td>
			<td>สภาพแวดล้อม </td>
			<td><input type="radio" disabled  name="environment" <?php if($re['environment'] == 3){ echo "checked"; } ?>> แย่</td>	
			<td><input type="radio" disabled  name="environment" <?php if($re['environment'] == 2){ echo "checked"; } ?>> ปานกลาง</td>
			<td><input type="radio" disabled  name="environment" <?php if($re['environment'] == 1){ echo "checked"; } ?>> ดี</td>					
		</tr>
		<tr>
			<td></td>
			<td><input type="checkbox" disabled  name="environment_trade"  <?php if($re['environment_trade'] == 1){ echo "checked"; } ?>> ย่านการค้า</td>	
			<td><input type="checkbox" disabled  name="environment_home"  <?php if($re['environment_home'] == 1){ echo "checked"; } ?>> ย่านที่อยู่อาศัย</td>
			<td><input type="checkbox" disabled  name="environment_factory"  <?php if($re['environment_factory'] == 1){ echo "checked"; } ?>> ย่านโรงงาน</td>	
			<td><input type="checkbox" disabled  name="environment_slum"  <?php if($re['environment_slum'] == 1){ echo "checked"; } ?>> ย่านสลัม</td>				
		</tr>
		<tr>
			<td></td>
			<td><input type="checkbox" disabled  name="environment_military" <?php if($re['environment_military'] == 1){ echo "checked"; } ?>> ใกล้ เขตทหาร</td>
			<td><input type="checkbox" disabled  name="environment_tomb" <?php if($re['environment_tomb'] == 1){ echo "checked"; } ?>> ใกล้ สุสาน</td>	
			<td><input type="checkbox" disabled  name="environment_shrine" <?php if($re['environment_shrine'] == 1){ echo "checked"; } ?>> ใกล้ ศาลเจ้า</td>	
			<td><input type="checkbox" disabled  name="environment_temple" <?php if($re['environment_temple'] == 1){ echo "checked"; } ?>> ใกล้ วัด/โบสถ์/มัสยิด</td>	
		</tr>
		<tr>
			<td></td>
			<td><input type="checkbox" disabled  name="environment_highvoltage" <?php if($re['environment_highvoltage'] == 1){ echo "checked"; } ?>> ใกล้ รัสมีสายไฟฟ้าแรงสูง</td>	
			<td><input type="checkbox" disabled  name="environment_dirt" <?php if($re['environment_dirt'] == 1){ echo "checked"; } ?>> ใกล้สิงปฎิกูล/เขตอันตราย</td>	
		</tr>
		<tr>
			<td></td>
			<td colspan="3">สถานที่สำคัญบริเวณใกล้เคียงได้แก่  <input type="text" readonly="true" size="25" name="environment_closeplace" value=<?php echo $re['environment_closeplace']; ?>></td>	
		</tr>
		<tr>
			<td>10.</td>
			<td>ภาระผูกพัน </td>
		</tr>
		<tr>
			<td></td>
			<td colspan="2"><input type="checkbox" disabled  name="bind_rent" <?php if($re['bind_rent'] == 1){ echo "checked"; } ?>> ภาระการเช่า คงเหลือ <input type="text" readonly="true" size="20" name="bind_rent_about" <?php if($re['bind_rent'] == 1){ ?> value=<?php echo $re['bind_rent_about']; } ?>></td>	
			<td colspan="2"><input type="checkbox" disabled  name="bind_pawn" <?php if($re['bind_pawn'] == 1){ echo "checked"; } ?>> ภาระจำนองกับ <input type="text" readonly="true" size="20"name="bind_pawn_about" <?php if($re['bind_pawn'] == 1){ ?> value=<?php echo $re['bind_pawn_about']; } ?>></td>			
		</tr>
		<tr>
			<td></td>
			<td colspan="3">
				<input type="checkbox" disabled  name="bind_all" <?php if($re['bind_all'] == 1){ echo "checked"; } ?>> ภาระจำยอมทั้งแปลง	
				<input type="checkbox" disabled  name="bind_rentbuy" <?php if($re['bind_rentbuy'] == 1){ echo "checked"; } ?>> อยู่ในระหว่างสัญญาเช่า-ซื้อ
				<input type="checkbox" disabled  name="bind_nothing" <?php if($re['bind_nothing'] == 1){ echo "checked"; } ?>> ไม่มีภาระผูกพันใดๆ
			</td>					
		</tr>
		<tr>
			<td>11.</td>
			<td>การเวนคืน </td>
		</tr>
		<tr>
			<td></td>
			<td><input type="radio" disabled  name="expropriate" <?php if($re['expropriate'] == 1){ echo "checked"; } ?>> อาจถูกเวนคืน</td>	
			<td><input type="radio" disabled  name="expropriate" <?php if($re['expropriate'] == 2){ echo "checked"; } ?>> ไม่มีการเวนคืน</td>				
		</tr>
		<tr>
			<td>12.</td>
			<td colspan="4">แนวโน้มความเจริญหรือการพัฒนา </td>
		</tr>
		<tr>
			<td></td>
			<td colspan="3">โครงการพัฒนาของรัฐ</td>				
		</tr>
		<tr>
			<td></td>
			<td colspan="4"> ที่มีอยู่แล้วคือ <input type="text" readonly="true" size="40" name="advancementnow" value=<?php echo $re['advancementnow']; ?>></td>				
		</tr>
		<tr>
			<td></td>
			<td colspan="4"> อยู่ระหว่างการดำเนินการ ทำอะไร <input type="text" readonly="true" size="40" name="advancementcontinue" value=<?php echo $re['advancementcontinue']; ?>></td>				
		</tr>
		<tr>
			<td></td>
			<td colspan="3">แนวโน้มความเจริญหรือการพัฒนา</td>				
		</tr>
		<tr>
			<td></td>
			<td><input type="radio" disabled  name="advancement" <?php if($re['advancement'] == 1){ echo "checked"; } ?>> น้อย</td>	
			<td><input type="radio" disabled  name="advancement" <?php if($re['advancement'] == 2){ echo "checked"; } ?>> ปานกลาง</td>
			<td><input type="radio" disabled  name="advancement" <?php if($re['advancement'] == 3){ echo "checked"; } ?>> มาก</td>				
		</tr>
		<tr>
			<td>13.</td>
			<td colspan="4">สภาพทั่วไปของทรัพย์สิน (ระบรายละเอียดส่วนที่ชำรุด) </td>
		</tr>
		<tr>
			<td></td>
			<td colspan="3"><input type="radio" disabled  name="generality" <?php if($re['generality'] == 1){ echo "checked"; } ?>> มี ถ้าชำรุดมีส่วนต่างๆ <input type="text" readonly="true" size="40" name="generality_detail" <?php if($re['generality'] == 1){ ?> value=<?php echo $re['generality_detail']; } ?>></td>			
		</tr>
		<tr>
			<td></td>
			<td><input type="radio" disabled  name="generality" <?php if($re['generality'] == 2){ echo "checked"; } ?>> ไม่มี</td>	
		</tr>
		<tr>
			<td>14.</td>
			<td colspan="4">ประกาศขายบ้าน(ข้างเคียง) </td>
		</tr>
		<tr>
			<td></td>
			<td colspan="4"> ลักษณะบ้านที่ขาย <input type="text" readonly="true" size="25" name="nearhomestatus" value=<?php echo $re['nearhome_status']; ?>> กี่ตารางวา <input type="text" readonly="true" size="25" name="nearhomesize" value=<?php echo $re['nearhomesize']; ?>></td>	
		</tr>
		<tr>
			<td></td>
			<td colspan="4"> เบอร์โทร <input type="text" readonly="true" size="25" name="nearhometel" value=<?php echo $re['nearhometel']; ?>> ราคาขาย <input type="text" readonly="true" size="25" name="nearhomeprice" value=<?php echo $re['nearhomeprice']; ?>></td>	
		</tr>
		<tr>
			<td>15.</td>
			<td colspan="4">สำนักงานกรมที่ดิน  <input type="text" readonly="true" size="25" name="landoffice" value=<?php echo $re['landoffice']; ?>> สาขาที่จด  <input type="text" readonly="true" size="25" name="landoffice_branch" value=<?php echo $re['landoffice_branch']; ?>></td>
		</tr>
		<tr>
			<td>16.</td>
			<td colspan="4">ความรู้สึกของฝ่ายตรวจสอบ </td>
		</tr>
		<tr>
			<td></td>
			<td colspan="5"><textarea readonly rows="5" cols="80" name="feel_checker"> <?php echo $re['feel_checker']; ?></textarea></td>
		</tr>
		<tr>
			<td colspan="5"><B> แนบไฟล์ </b></td>
			
		</tr>
		<tr>
			<td></td>
			<td colspan="4" bgcolor="#FFFFFF">
			
						<?php
						if($check == "admin"){
							$qry_name9 = pg_query("select \"file\",\"securID\" from \"temp_securities_detail\" where \"securdeID\" = '$id'");
						
						}else if($check == "user"){
							$qry_name9 = pg_query("select \"file\",\"securID\" from \"nw_securities_detail\" where \"securID\" = '$id'");
												
						}
							$result9=pg_fetch_array($qry_name9);						
							$ff = $result9["file"];
							$file=explode("!#",$ff);	
						for($i=1;$i<sizeof($file);$i++){
						?>							
						<a href="fileupload/<?php echo $result9["securID"];?>/<?php echo $file[$i];?>" target="_blank"><?php echo $file[$i];?>
						<br>
						<?php } ?>	
						
						
			</td>
		</tr>
		<tr>
				<td colspan="5" align="center"><input type="button" value="ปิด" style="width:100px; height:35px;" onclick="window.close();"></td>
		</tr>
		
	
	</table>
</form>

		</td>
	</tr>			
</table>
</body>
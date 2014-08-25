<?php
include("../../config/config.php");

$pollid = $_GET['pollid'];
$sql = pg_query("SELECT  gender, regis_car, age, phone, email, type_customer, 
       date_service, cusmore5year, emp_name, emp_nickname, identify_emp, 
       service_number, poll1, poll2, poll3, poll4, poll5, poll6, recommend, 
       id_user, datetime_record
  FROM \"Poll_service\" where \"PSID\" = '$pollid' ");
  $result = pg_fetch_array($sql); 


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>-- ดูข้อมูลแบบสำรวจ --</title>
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

<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td>				
				<table  width="800" frame="box"  cellspacing="4" cellpadding="3" align="center" bgcolor="#EEF2F7">
					<tr>
						<td colspan="5">
							<b><u>ส่วนที่.1</u> ข้อมูลทั่วไปของผู้ตอบแบบสอบถาม ( ข้อมูลของท่าจะถูกเก็บเป็นความลับ )</b>
						</td>						
					</tr>
					<tr>
						<td width="25%">
							เพศ : 
						
							<input type="radio" disabled <?php if($result['gender'] == 'Male'){ echo "checked"; } ?>> ชาย
						
							<input type="radio" disabled <?php if($result['gender'] == 'Female'){ echo "checked"; } ?>> หญิง
						</td>	
						<td align="right" width="20%">
							ทะเบียนรถ : 
						</td>
						<td width="20%">
							<input type="text" readonly value="<?php echo $result['regis_car'];?>" >
						</td>	
						<td width="30%">
						</td>		
					</tr>
					<tr>
						<td>
							อายุ : <input type="text" readonly value="<?php echo $result['age'];?>"> ปี
						</td>
						<td align="right">
							หมายเลขโทรศัพท์ : 						
						</td>
						<td>
							<input type="text" size="20" readonly value="<?php echo $result['phone'];?>">
						</td>
						<td colspan="2" align="left">
							อีเมลล์ : 
						
							<input type="text" readonly value="<?php echo $result['email'];?>" >
						</td>
					</tr>
					<tr>
						<td colspan="4">
							เรื่องที่มารับบริการ :							
						
							<input type="text" readonly value="<?php echo $result['type_customer'];?>">
						
						
						<?php list($date,$time) = explode(" ",$result['date_service']);?>
							วันที่ใช้บริการ :							
						
							<input type="text" readonly value="<?php echo $date;?>"/>
						<?php list($hh,$mm,$ss) = explode(":",$time);?>
							เวลาที่ใช้บริการ :							
								<input type="text" readonly value="<?php echo $hh;?>" size="3"/>	นาฬิกา
								<input type="text" readonly value="<?php echo $mm;?>" size="3"/>	นาที
						</td>
					</tr>
					<tr>					
						<td colspan="5">
							<input type="checkbox" disabled <?php if($result['cusmore5year'] == 't'){ echo "checked"; } ?>> ท่านเป็นลูกค้าของไทยเอซมานานเกิน 5 ปี
						</td>
					</tr>
					<tr>
						<td colspan="5"><br>
						</td>
					</tr>
					<tr>
						<td colspan="5">
							<b><u>ส่วนที่.2</u> ระดับความพึงพอใจของผู้รับบริการ ( เลือก คะแนน ที่ตรงกับความรู้สึกของท่าน )</b>
						</td>						
					</tr>
					<tr>
						<td colspan="5">
							กรุณากรอกข้อมูลของเจ้าหน้าที่ที่ให้บริการท่าน เท่าที่ท่านทราบ
						</td>						
					</tr>
					<tr>
						<td align="right">
							 เจ้าหน้าที่ ชื่อ-นามสกุล :
						</td>
						<td colspan="2">
							<input type="text" size="35" readonly value="<?php echo $result['emp_name'];?>">
						</td>
						<td colspan="2">
							ชื่อเล่น : 
						
							<input type="text" readonly value="<?php echo $result['emp_nickname'];?>">
						</td>
					</tr>	
					<tr>
						<td align="right">
							รหัสประจำตัวที่ : 
						</td>
						<td>
							<input type="text" readonly value="<?php echo $result['identify_emp'];?>">
						</td>
						<td align="left" colspan="3"> 
							ช่องบริการที่ : 
						
							<input type="text" readonly value="<?php echo $result['service_number'];?>" size="5" >
						</td>
								
					</tr>
					<tr>
						<td colspan="5"><br>
						</td>
					</tr>					
					<tr>
						<td colspan="5" align="center">
							ระดับความพึงพอใจ  5 = มากที่สุด  4 = มาก  3 = ปานกลาง  2 = น้อย  1 = น้อยที่สุด
						</td>
					</tr>
					<tr>
						<td colspan="5"><br>
						</td>
					</tr>
					<tr>
						<td colspan="5" align="center">
							<table  width="700" border="1" cellspacing="0" cellpadding="3" align="center">
								<tr>
									<td align="center" width="65%">
										ประเมินความพึงพอใจ
									</td>
									<td align="center" width="7%">
										5
									</td>
									<td align="center" width="7%">
										4
									</td>
									<td align="center" width="7%">
										3
									</td>
									<td align="center" width="7%">
										2
									</td>
									<td align="center" width="7%">
										1
									</td>
								</tr>
								<tr>
									<td>
										ด้านการให้บริการของเจ้าหน้าที่
									</td>
									<td align="center"><input type="radio" disabled <?php if($result['poll1'] == '5'){ echo "checked"; } ?>></td>
									<td align="center"><input type="radio" disabled <?php if($result['poll1'] == '4'){ echo "checked"; } ?>></td>
									<td align="center"><input type="radio" disabled <?php if($result['poll1'] == '3'){ echo "checked"; } ?>></td>
									<td align="center"><input type="radio" disabled <?php if($result['poll1'] == '2'){ echo "checked"; } ?>></td>
									<td align="center"><input type="radio" disabled <?php if($result['poll1'] == '1'){ echo "checked"; } ?>></td>									
								</tr>
								<tr>
									<td>
										ให้บริการด้วยความสุภาพ อ่อนน้อม ยิ้มแย้ม เป็นกันเอง
									</td>
									<td align="center"><input type="radio" disabled <?php if($result['poll2'] == '5'){ echo "checked"; } ?>></td>
									<td align="center"><input type="radio" disabled <?php if($result['poll2'] == '4'){ echo "checked"; } ?>></td>
									<td align="center"><input type="radio" disabled <?php if($result['poll2'] == '3'){ echo "checked"; } ?>></td>
									<td align="center"><input type="radio" disabled <?php if($result['poll2'] == '2'){ echo "checked"; } ?>></td>
									<td align="center"><input type="radio" disabled <?php if($result['poll2'] == '1'){ echo "checked"; } ?>></td>									
								</tr>
								<tr>
									<td>
										มีความเอาใจใส่  กระตือรือร้น เต็มใจให้บริการ
									</td>
									<td align="center"><input type="radio" disabled <?php if($result['poll3'] == '5'){ echo "checked"; } ?>></td>
									<td align="center"><input type="radio" disabled <?php if($result['poll3'] == '4'){ echo "checked"; } ?>></td>
									<td align="center"><input type="radio" disabled <?php if($result['poll3'] == '3'){ echo "checked"; } ?>></td>
									<td align="center"><input type="radio" disabled <?php if($result['poll3'] == '2'){ echo "checked"; } ?>></td>
									<td align="center"><input type="radio" disabled <?php if($result['poll3'] == '1'){ echo "checked"; } ?>></td>								
								</tr>
								<tr>
									<td>
										รับฟังปัญหาหรือข้อซักถามของผู้ใช้บริการอย่างเต็มใจ
									</td>
									<td align="center"><input type="radio" disabled <?php if($result['poll4'] == '5'){ echo "checked"; } ?>></td>
									<td align="center"><input type="radio" disabled <?php if($result['poll4'] == '4'){ echo "checked"; } ?>></td>
									<td align="center"><input type="radio" disabled <?php if($result['poll4'] == '3'){ echo "checked"; } ?>></td>
									<td align="center"><input type="radio" disabled <?php if($result['poll4'] == '2'){ echo "checked"; } ?>></td>
									<td align="center"><input type="radio" disabled <?php if($result['poll4'] == '1'){ echo "checked"; } ?>></td>								
								</tr>
								<tr>
									<td>
										ให้คำอธิบายและตอบข้อสงสัยได้ตรงประเด็น
									</td>
									<td align="center"><input type="radio" disabled <?php if($result['poll5'] == '5'){ echo "checked"; } ?>></td>
									<td align="center"><input type="radio" disabled <?php if($result['poll5'] == '4'){ echo "checked"; } ?>></td>
									<td align="center"><input type="radio" disabled <?php if($result['poll5'] == '3'){ echo "checked"; } ?>></td>
									<td align="center"><input type="radio" disabled <?php if($result['poll5'] == '2'){ echo "checked"; } ?>></td>
									<td align="center"><input type="radio" disabled <?php if($result['poll5'] == '1'){ echo "checked"; } ?>></td>								
								</tr>
								<tr>
									<td>
										มีความชัดเจนในการให้คำแนะนำที่เป็นประโยชน์
									</td>
									<td align="center"><input type="radio" disabled <?php if($result['poll6'] == '5'){ echo "checked"; } ?>></td>
									<td align="center"><input type="radio" disabled <?php if($result['poll6'] == '4'){ echo "checked"; } ?>></td>
									<td align="center"><input type="radio" disabled <?php if($result['poll6'] == '3'){ echo "checked"; } ?>></td>
									<td align="center"><input type="radio" disabled <?php if($result['poll6'] == '2'){ echo "checked"; } ?>></td>
									<td align="center"><input type="radio" disabled <?php if($result['poll6'] == '1'){ echo "checked"; } ?>></td>								
								</tr>
							</table>							
						</td>
					</tr>	
					<tr>
						<td colspan="5"><br>
						</td>
					</tr>
					<tr>
						<td colspan="5">
							<b><u>ตอนที่ 3</u> ข้อคิดเห็นและข้อเสนอแนะเพิ่มเติม</b>
						</td>
					</tr>
					<tr>
						<td colspan="5" align="center">
							<textarea cols="100" rows="5" readonly ><?php echo $result['recommend']; ?></textarea>
						</td>
					</tr>
					<tr>
						<td colspan="5">
							<br>
						</td>
					</tr>
					<tr>						
						
						<td colspan="4" align="center">
							<input type="button" value=" ปิด " onclick="window.close();" style="height:20px; width:120px;">
						</td>
					</tr>						
				</table>
					
        </td>
    </tr>
</table>

</body>
</html>

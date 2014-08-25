<?php
include("../../config/config.php");

$editID = $_GET['editID'];

$test_sql3=pg_query("select \"Fa1\".\"CusID\" , \"Fa1\".\"A_FIRNAME\" , \"Fa1\".\"A_NAME\" , \"Fa1\".\"A_SIRNAME\" , \"Fa1\".\"A_PAIR\" , \"Fa1\".\"A_NO\"
						, \"Fa1\".\"A_SUBNO\" , \"Fa1\".\"A_SOI\" , \"Fa1\".\"A_RD\" , \"Fa1\".\"A_TUM\" , \"Fa1\".\"A_AUM\" , \"Fa1\".\"A_PRO\" , \"Fa1\".\"A_POST\"
						, \"Fn\".\"N_AGE\" , \"Fn\".\"N_CARD\" , \"Fn\".\"N_IDCARD\" , \"Fn\".\"N_OT_DATE\" , \"Fn\".\"N_BY\" , \"Fn\".\"N_SAN\" , \"Fn\".\"N_OCC\" , \"Fn\".\"N_ContactAdd\"
						from public.\"Fa1\" , public.\"Fn\"
						where \"Fa1\".\"CusID\" = \"Fn\".\"CusID\"
							and \"Fa1\".\"CusID\" = '$editID'
							order by \"Fn\".\"N_AGE\" , \"Fa1\".\"CusID\" ");
	$rowtest=pg_num_rows($test_sql3);
	while($result=pg_fetch_array($test_sql3))
	{
		$CusID=$result["CusID"];
		$A_FIRNAME=$result["A_FIRNAME"];
		$A_NAME=$result["A_NAME"];
		$A_SIRNAME=$result["A_SIRNAME"];
		$A_PAIR=$result["A_PAIR"];
		$A_NO=$result["A_NO"];
		$A_SUBNO=$result["A_SUBNO"];
		$A_SOI=$result["A_SOI"];
		$A_RD=$result["A_RD"];
		$A_TUM=$result["A_TUM"];
		$A_AUM=$result["A_AUM"];
		$A_PRO=$result["A_PRO"];
		$A_POST=$result["A_POST"];
		
			$N_AGE=$result["N_AGE"];
			$N_CARD=$result["N_CARD"];
			$N_IDCARD=$result["N_IDCARD"];
			$N_OT_DATE=$result["N_OT_DATE"];
			$N_BY=$result["N_BY"];
			$N_SAN=$result["N_SAN"];
			$N_OCC=$result["N_OCC"];
			$N_ContactAdd=$result["N_ContactAdd"];
	}

?>

<title>แก้ไขข้อมูลลูกค้า</title>
<form method="post" name="form1" action="ProcessEditCus.php">
<center>
<table>
	<tr>
		<td align=right>รหัสลูกค้า</td>
		<td>:</td>
		<td><?php echo $CusID; ?></td>
		
		<td> </td>
		
		<td align=right>จังหวัด</td>
		<td>:</td>
		<!-- <td><input type="text" name="A_PRO" value="<?php //echo $A_PRO; ?>"></td> -->
		<td>
			<select name="A_PRO">
				<option value="<?php echo $A_PRO; ?>"><?php echo $A_PRO; ?></option>	
				<option value="กระบี่">กระบี่</option>
				<option value="กรุงเทพมหานคร">กรุงเทพมหานคร</option>
				<option value="กาญจนบุรี">กาญจนบุรี</option>
				<option value="กาฬสินธุ์">กาฬสินธุ์</option>
				<option value="กำแพงเพชร">กำแพงเพชร</option>
				<option value="ขอนแก่น">ขอนแก่น</option>
				<option value="จันทบุรี">จันทบุรี</option>
				<option value="ฉะเชิงเทรา">ฉะเชิงเทรา</option>
				<option value="ชลบุรี">ชลบุรี</option>
				<option value="ชัยนาท">ชัยนาท</option>
				<option value="ชัยภูมิ">ชัยภูมิ</option>
				<option value="ชุมพร">ชุมพร</option>
				<option value="ตรัง">ตรัง</option>
				<option value="ตราด">ตราด</option>
				<option value="ตาก">ตาก</option>
				<option value="นครนายก">นครนายก</option>
				<option value="นครปฐม">นครปฐม</option>
				<option value="นครพนม">นครพนม</option>
				<option value="นครราชสีมา">นครราชสีมา</option>
				<option value="นครศรีธรรมราช">นครศรีธรรมราช</option>
				<option value="นครสวรรค์">นครสวรรค์</option>
				<option value="นนทบุรี">นนทบุรี</option>
				<option value="นราธิวาส">นราธิวาส</option>
				<option value="น่าน">น่าน</option>
				<option value="บึงกาฬ">บึงกาฬ</option>
				<option value="บุรีรัมย์">บุรีรัมย์</option>
				<option value="ปทุมธานี">ปทุมธานี</option>
				<option value="ประจวบคีรีขันธ์">ประจวบคีรีขันธ์</option>
				<option value="ปราจีนบุรี">ปราจีนบุรี</option>
				<option value="ปัตตานี">ปัตตานี</option>
				<option value="พระนครศรีอยุธยา">พระนครศรีอยุธยา</option>
				<option value="พะเยา">พะเยา</option>
				<option value="พังงา">พังงา</option>
				<option value="พัทลุง">พัทลุง</option>
				<option value="พิจิตร">พิจิตร</option>
				<option value="พิษณุโลก">พิษณุโลก</option>
				<option value="ภูเก็ต">ภูเก็ต</option>
				<option value="มหาสารคาม">มหาสารคาม</option>
				<option value="มุกดาหาร">มุกดาหาร</option>
				<option value="ยะลา">ยะลา</option>
				<option value="ยโสธร">ยโสธร</option>
				<option value="ระนอง">ระนอง</option>
				<option value="ระยอง">ระยอง</option>
				<option value="ราชบุรี">ราชบุรี</option>
				<option value="ร้อยเอ็ด">ร้อยเอ็ด</option>
				<option value="ลพบุรี">ลพบุรี</option>
				<option value="ลำปาง">ลำปาง</option>
				<option value="ลำพูน">ลำพูน</option>
				<option value="ศรีสะเกษ">ศรีสะเกษ</option>
				<option value="สกลนคร">สกลนคร</option>
				<option value="สงขลา">สงขลา</option>
				<option value="สตูล">สตูล</option>
				<option value="สมุทรปราการ">สมุทรปราการ</option>
				<option value="สมุทรสงคราม">สมุทรสงคราม</option>
				<option value="สมุทรสาคร">สมุทรสาคร</option>
				<option value="สระบุรี">สระบุรี</option>
				<option value="สระแก้ว">สระแก้ว</option>
				<option value="สิงห์บุรี">สิงห์บุรี</option>
				<option value="สุพรรณบุรี">สุพรรณบุรี</option>
				<option value="สุราษฎร์ธานี">สุราษฎร์ธานี</option>
				<option value="สุรินทร์">สุรินทร์</option>
				<option value="สุโขทัย">สุโขทัย</option>
				<option value="หนองคาย">หนองคาย</option>
				<option value="หนองบัวลำภู">หนองบัวลำภู</option>
				<option value="อำนาจเจริญ">อำนาจเจริญ</option>
				<option value="อุดรธานี">อุดรธานี</option>
				<option value="อุตรดิตถ์">อุตรดิตถ์</option>
				<option value="อุทัยธานี">อุทัยธานี</option>
				<option value="อุบลราชธานี">อุบลราชธานี</option>
				<option value="อ่างทอง">อ่างทอง</option>
				<option value="เชียงราย">เชียงราย</option>
				<option value="เชียงใหม่">เชียงใหม่</option>
				<option value="เพชรบุรี">เพชรบุรี</option>
				<option value="เพชรบูรณ์">เพชรบูรณ์</option>
				<option value="เลย">เลย</option>
				<option value="แพร่">แพร่</option>
				<option value="แม่ฮ่องสอน">แม่ฮ่องสอน</option>
			</select>
		<td>
	</tr>
	<tr>
		<td align=right>คำนำหน้า</td>
		<td>:</td>
		<td><?php echo "$A_FIRNAME$A_NAME $A_SIRNAME"; ?></td>
		
		<td> </td>
		
		<td align=right>รหัสไปรษณีย์</td>
		<td>:</td>
		<td><input type="text" name="A_POST" value="<?php echo $A_POST; ?>"></td>
	</tr>
	<tr>
		<td align=right>อายุ</td>
		<td>:</td>
		<td><input type="text" name="N_AGE" value="<?php echo $N_AGE; ?>"></td>
		
		<td> </td>
		
		<td align=right>สัญชาติ</td>
		<td>:</td>
		<td><input type="text" name="N_SAN" value="<?php echo $N_SAN; ?>"></td>
	</tr>
	<tr>
		<td align=right>คู่สมรส</td>
		<td>:</td>
		<td><input type="text" name="A_PAIR" value="<?php echo $A_PAIR; ?>"></td>
		
		<td> </td>
		
		<td align=right>ประเภทบัตร</td>
		<td>:</td>
		<td><input type="text" name="N_CARD" value="<?php echo $N_CARD; ?>"></td>
	</tr>
	<tr>
		<td align=right>ที่อยู่เลขที่</td>
		<td>:</td>
		<td><input type="text" name="A_NO" value="<?php echo $A_NO; ?>"></td>
		
		<td> </td>
		
		<td align=right>เลขที่บัตร</td>
		<td>:</td>
		<td><input type="text" name="N_IDCARD" value="<?php echo $N_IDCARD; ?>"></td>
	</tr>
	<tr>
		<td align=right>หมู่</td>
		<td>:</td>
		<td><input type="text" name="A_SUBNO" value="<?php echo $A_SUBNO; ?>"></td>
		
		<td> </td>
		
		<td align=right>วันที่ออกบัตร</td>
		<td>:</td>
		<td><input type="text" name="N_OT_DATE" value="<?php echo $N_OT_DATE; ?>"></td>
	</tr>
	<tr>
		<td align=right>ซอย</td>
		<td>:</td>
		<td><input type="text" name="A_SOI" value="<?php echo $A_SOI; ?>"></td>
		
		<td> </td>
		
		<td align=right>ออกให้โดย</td>
		<td>:</td>
		<td><input type="text" name="N_BY" value="<?php echo $N_BY; ?>"></td>
	</tr>
	<tr>
		<td align=right>ถนน</td>
		<td>:</td>
		<td><input type="text" name="A_RD" value="<?php echo $A_RD; ?>"></td>
		
		<td> </td>
		
		<td align=right>อาชีพ</td>
		<td>:</td>
		<td><input type="text" name="N_OCC" value="<?php echo $N_OCC; ?>"></td>
	</tr>
	<tr>
		<td align=right>ตำบล</td>
		<td>:</td>
		<td><input type="text" name="A_TUM" value="<?php echo $A_TUM; ?>"></td>
		
		<td> </td>
		
		<td align=right>อื่นๆ</td>
		<td>:</td>
		<td rowspan="2"><textarea name="N_ContactAdd"><?php echo $N_ContactAdd; ?></textarea></td>
	</tr>
	<tr>
		<td align=right>อำเภอ</td>
		<td>:</td>
		<td><input type="text" name="A_AUM" value="<?php echo $A_AUM; ?>"></td>
	</tr>
</table>
<br>
<input type="hidden" name="editID" value="<?php echo $editID; ?>">
<input type="submit" value="SAVE">
</center>
</form>
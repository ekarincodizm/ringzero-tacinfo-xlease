<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
	session_start();
	include("../../config/config.php");
	$Ref_ID = pg_escape_string($_POST["revtran"]); // รับเลขรหัสการโอน
	$Cus_ID = split('#',pg_escape_string($_POST["Cs_Data"])); // รับรหัสลูกค้า,ชื่อลูกค้่า,เลขประจำตัวประฃาฃน
	$N_Contact_Date = pg_escape_string($_POST["Contact_Date"]); // รับวันที่ที่ติดต่อ
	$Time_Contact = pg_escape_string($_POST["Hour"]).':'.pg_escape_string($_POST["Minute"]); // รับเวลาที่ติดต่อ
	$Detail_Save = pg_escape_string($_POST["detail_Save"]); // รับรายละเอียดการติดต่อ
	$doerID_Save = pg_escape_string($_POST["doerID"]); // รับรหัสผู้บันทึกข้อมูล
	// echo "รหัสผู้บันทึกข้อมูล".$doerID_Save;  
	$Time_Result = pg_query("select \"nowDateTime\"()"); 
	$Time_Input = pg_fetch_result($Time_Result,0,0); // เวลาที่ใช้ในการบันทึกข้อมูล
	// Check รหัสลูกค้า ที่นำเข้า	
	$Str_Chk = "SELECT \"full_name\" "; 
    $Str_Chk = $Str_Chk . " FROM \"VSearchCusCorp\" ";
	$Str_Chk = $Str_Chk . " WHERE (\"CusID\" = '".$Cus_ID[0]."') ";
	
	$Result_Chk = pg_query($Str_Chk);
	$Num_Row = pg_num_rows($Result_Chk);
	if($Num_Row == 0){
		echo "<font color = #FF0000 >ไม่สามารถบันทึกข้อมูลเข้าระบบได้ เนื่องจากรหัสลูกค้าไม่มีในระบบ</font>";
		$Re_Chk = true;
	}else{
		// นำเข้าข้อมูลการติดต่อ
		$Str_Ins = "INSERT INTO finance.thcap_note_transfer";
		$Str_Ins = $Str_Ins."(\"revTranID\", \"CusID\", \"contactDate\", \"contactTime\",\"contactNote\", \"doerID\", \"doerStamp\")";
		$Str_Ins = $Str_Ins."VALUES( '".$Ref_ID."','".$Cus_ID[0]."','".$N_Contact_Date."','".$Time_Contact."','".$Detail_Save."','".$doerID_Save."','".$Time_Input."')";
		
		// pg_query($Str_Ins); 	
		if($sql_check_user = pg_query($Str_Ins)){
			echo "<font color = #0000FF >บันทึกสำเร็จ</font>";
			$Re_Chk = false;
		}else{
			echo "<font color = #FF0000 >ระบบไม่สามารถบันทึกข้อมูลได้</font>";
			$Re_Chk = true;
		}
	}	
?> 
<Form method = POST action = "Money_transfers_Note.php">
	<?php
		if($Re_Chk){
			?>
				
				<input type="hidden" size="12" style="text-align:center;" id="Contact_Date" name="Contact_Date" value="<?php echo $N_Contact_Date; ?>" />
				<input type="hidden" name="Comand" id = "Comand" size = "25" value = "New_Chk" >
				<input type="hidden" name="Cs_Data"  id = "Cs_Data"  size="40" value = "<?php echo pg_escape_string($_POST["Cs_Data"]); ?>" />  
	 			<input type="hidden" name="Hour" id = "Hour" size="3" value = "<?php echo pg_escape_string($_POST["Hour"]); ?>" >  
				<input type="hidden" name="Minute" id = "Minute" size="3" value = "<?php echo pg_escape_string($_POST["Minute"]); ?>">   
				<input type="hidden" name="detail_Input" id = "detail_Input" size="200" value ="<?php echo $Detail_Save;  ?>" >
			<?php
		}
	
	?>	
	<input type="hidden" name= "revtran" id = "revtran" value="<?php echo $Ref_ID; ?>"/> 
	<input type="submit" name = "submit" value = "ตกลง">
	
</Form>

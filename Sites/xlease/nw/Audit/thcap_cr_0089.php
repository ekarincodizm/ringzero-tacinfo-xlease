<?php
	include("../../config/config.php");
	include("document_function.php");
	$Login_Name = get_Login_Full_Name_By_Login_ID($_SESSION['av_iduser']);
	// print_r($_SESSION);  echo "test ".$Login_Name;
?>
<link type="text/css" rel="stylesheet" href="act_home_index2.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
	$(document).ready(function(){
		$("#Txt_Date").datepicker({
        	showOn: 'button',
        	buttonImage: 'images/calendar.gif',
        	buttonImageOnly: true,
        	changeMonth: true,
        	changeYear: true,
        	dateFormat: 'yy-mm-dd'
    	});
  
    	$("#Valuator_Name").autocomplete({
        source: "s_user.php",
        minLength:2
    	});	
    
    });
	
	

</script>
<HTML>
	<HEAD>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<TITLE>
			แบบประเมินการให้บริการ (สำหรับผู้จัดจำหน่าย)
		</TITLE>
	</HEAD> 
	<?php  
	   $Dealer_Name = pg_escape_string($_POST['Dealer_Name']);	
	?>
	<FORM Name = "frm1"  ID = "frm1">
 	<TABLE width = "98%"><!-- Start Table Table No 1 -->
		<TR><!-- Start Row No. 1  -->
			<TD align="center" style="font-size: 30px;">
				แบบประเมินการให้บริการ (สำหรับผู้จัดจำหน่าย)
			</TD>
		</TR>
		
		<TR><!-- Start Row No. 2  -->
			<TD align = "left">
				<BR>
				ตอนที่ 1 ผู้ประเมิน
			</TD>
		</TR>
		<TR><!-- Start Row No. 3  -->
			<TD ALIGN="LEFT">
				ชื่อร้าน / ชื่อผู้จัดจาหน่าย<INPUT name = "Cutomer_Name" id = "Cutomer_Name" TYPE = "TEXT" value = '<?php echo $Dealer_Name; ?>' ReadOnly >
				วันที่<INPUT TYPE = "TEXT" name = "Txt_Date" id = "Txt_Date"    >
			</TD>
		</TR>	
		<TR>
			<TD> 
				ชื่อ-สกุล ผู้ประเมิน<INPUT TYPE = "TEXT"  Name = "Valuator_Name"  ID = "Valuator_Name" size = 40> 
			</TD>
		</TR>
		<TR>
			<TD>
				ตำแหน่ง<INPUT type = "text" Name = "Rank" ID = "Rank" >
				 เบอร์ติดต่อกลับ <INPUT type = "text" Name = "Telephone" ID = "Telephone">
			</TD>
		</TR>
	
	
	</TABLE><!-- End Table Table No 1 --> 
	
	<TABLE><!-- Start Table No. 2 --> 
		<TR>
			<TD colspan="6" align="center">
				ตอนที่ 2 ความพึงพอใจของท่านต่อการ “ ให้บริการ ”
			</TD>
		</TR>
	</TABLE><!-- End Table No. 2 -->
	
	<TABLE BORDER = "1" width = "98%"><!-- Start Table No. 3 -->
		<TR><!-- Start Row 1 -->
			<TD colspan ="6" align="center">
				ประเด็นการพิจารณา
			</TD>
		</TR>
		<TR><!-- Start Row 2 -->
			<TD colspan = "6">
				ด้านกระบวนการ ขั้นตอนการให้บริการ
			</TD>
		</TR>
		<TR><!-- Start Row 3 -->
			<TD>
				1.สามารถติดต่อ ประสานได้ง่าย
			</TD>
			<TD>	
				<input name="Check_1"  id = "C1_Excellent" type = "radio" value="5#Excellent">ดีเยี่ยม
			</TD>
			<TD>
				<input name="Check_1"  id = "C1_Good" type = "radio" value="4#Good">ดี
			</TD>
			<TD>
				<input name="Check_1"  id = "C1_Middle" type = "radio" value="3#Middle">ปานกลาง
			</TD>
			<TD>
				<input name="Check_1"  id = "C1_ShouldImprove"type = "radio" value="2#ShouldImprove">ควรปรับปรุง
			</TD>
			<TD>
				<input name="Check_1"  id = "C1_MustImprove"type = "radio" value="1#MustImprove">ต้องปรับปรุง
			</TD>
		</TR>
		<TR><!-- Start Row 4 -->
			<TD>
				2. ระยะเวลาการให้บริการ รวดเร็ว เป็นไปตามเวลาที่ตกลง
			</TD>
			<TD>	
				<input name="Check_2" id = "C2_Excellent" type = "radio" value="5#Excellent">ดีเยี่ยม
			</TD>
			<TD>
				<input name="Check_2" id = "C2_Good" type = "radio" value="4#Good">ดี
			</TD>
			<TD>
				<input name="Check_2" id = "C2_Middle" type = "radio" value="3#Middle">ปานกลาง
			</TD>
			<TD>
				<input name="Check_2" id = "C2_ShouldImprove" type = "radio" value="2#ShouldImprove">ควรปรับปรุง
			</TD>
			<TD>
				<input name="Check_2" id = "C2_MustImprove"  type = "radio" value = "1#MustImprove">ต้องปรับปรุง
			</TD>
		</TR>
		<TR><!-- Start Row 5 -->
			<TD>
				3. ความสุภาพ ไมตรีจิต จิตบริการ
			</TD>
			<TD>	
				<input name="Check_3" id = "C3_Excellent" type = "radio" value="5#Excellent">ดีเยี่ยม
			</TD>
			<TD>
				<input name="Check_3" id = "C3_Good" type = "radio" value="4#Good">ดี
			</TD>
			<TD>
				<input name="Check_3" id = "C3_Middle"  type = "radio" value="3#Middle">ปานกลาง
			</TD>
			<TD>
				<input name="Check_3" id = "C3_ShouldImprove" type = "radio" value="2#ShouldImprove">ควรปรับปรุง
			</TD>
			<TD>
				<input name="Check_3" id = "C3_MustImprove" type = "radio" value="1#MustImprove">ต้องปรับปรุง
			</TD>
		</TR>
		<TR><!-- Start Row 5 -->
			<TD>
				4. ความกระตือรือร้น ช่วยติดตามงาน
			</TD>
			<TD>	
				<input name="Check_4" id = "C4_Excellent" type = "radio" value="5#Excellent">ดีเยี่ยม
			</TD>
			<TD>
				<input name="Check_4" id = "C4_Good"  type = "radio" value="4#Good">ดี
			</TD>
			<TD>
				<input name="Check_4" id = "C4_Middle" type = "radio" value="3#Middle">ปานกลาง
			</TD>
			<TD>
				<input name="Check_4" id = "C4_ShouldImprove"  type = "radio" value="2#ShouldImprove">ควรปรับปรุง
			</TD>
			<TD>
				<input name="Check_4" id = "C4_MustImprove" type = "radio" value="1#MustImprove">ต้องปรับปรุง
			</TD>
		</TR>
		<TR><!-- Start Row 6 -->
			<TD>
				5. ความรับผิดชอบ
			</TD>
			<TD>	
				<input name="Check_5" id = "C5_Excellent" type = "radio" value="5#Excellent" >ดีเยี่ยม
			</TD>
			<TD>
				<input name="Check_5" id = "C5_Good" type = "radio" value="4#Good">ดี
			</TD>
			<TD>
				<input name="Check_5" id = "C5_Middle"  type = "radio" value="3#Middle">ปานกลาง
			</TD>
			<TD>
				<input name="Check_5" id = "C5_ShouldImprove" type = "radio" value="2#ShouldImprove">ควรปรับปรุง
			</TD>
			<TD>
				<input name="Check_5" id = "C5_MustImprove"  type = "radio" value="1#MustImprove">ต้องปรับปรุง
			</TD>
		</TR>
	</TABLE><!-- End Table No. 3 -->
	<TABLE><!-- Start Table No. 4 --> 
		<TR>
			<TD colspan="6" ALIGN="LEFT">
				ข้อเสนอแนะอื่น ๆ ( ถ้ามี)  
			</TD>
		</TR>
	</TABLE><!-- End Table No. 4 -->
	<TEXTAREA id = "advice" name = "advice" COLS = 170  ROW = 5></TEXTAREA>
	<BR>
	<P ALIGN = "RIGHT"> 
		ชื่อ(ผู้บันทึก)<?php echo $Login_Name; ?>
	
	</P>
	<input type= "hidden" Name = "Save_By" ID = "Save_By" value="<?php echo $Login_Name; ?>" >
	<p align="center">
		<input name = "btn_save" id = "btn_save" type="button" VALUE = "บันทึก" onclick = "Chk_Input_Data_cr_0089_type_2()" />
	</p>
	</FORM>
	
	<CENTER>
		ขอขอบคุณในความร่วมมือตอบแบบประเมิน
	</CENTER>
	<P align="left" style="font-size: 9px;" width ="100%">
		THCAP-FORM-CR-0089 Rev.001 (20140724)
		
	</P>





</HTML>
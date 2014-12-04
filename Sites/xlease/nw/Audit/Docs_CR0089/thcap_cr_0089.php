<?php
	include("../../../config/config.php");
	include("../document_function.php");
	// $Login_Name = get_Login_Full_Name_By_Login_ID($_SESSION['av_iduser']);  
	if(pg_escape_string($_GET['Pur_Pose']) == "Show"){
		$Login_Name = get_cr0089_save_by();
		$Show_Status = "Hidden";
		$Score_Show = "คะแนน จากการประเมิน  ".pg_escape_string($_GET['Score']).'%';
		$Doc_No = "เลขที่เอกสาร  ".pg_escape_string($_GET['Docs_ID']);
	}else{
		$Login_Name = get_Login_Full_Name_By_Login_ID($_SESSION['av_iduser']);
		$Show_Status = "";
		$Score_Show = "";
		$Doc_No = "";
	}
	// print_r($_SESSION);  echo "test ".$Login_Name;
?>
<link type="text/css" rel="stylesheet" href="act_home_index2.css"></link>
<link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<?php
	if(pg_escape_string($_GET['Pur_Pose']) == "Show")
	{
		?>
		<script type="text/javascript">
			$(document).ready(function(){
				$("#Txt_Date").datepicker({
        			showOn: 'button',
        			buttonImage: 'images/calendar.gif',
        			buttonImageOnly: true,
	        		changeMonth: true,
		        	changeYear: true,
	    	    	dateFormat: 'yy-mm-dd'
	        		display:'none'
    			});
  
    			$("#Valuator_Name").autocomplete({
        			source: "s_user.php",
        			minLength:2
    			});	
    
    		});
		</script>
		<?php
	}else{
			?>
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
		<?php		
		
	}	
?>
<HTML>
<link type="text/css" rel="stylesheet" href="../css_for_doc.css"></link>
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
 	<TABLE width = "98%" ><!-- Start Table Table No 1 -->
		<TR><!-- Start Row No. 1  -->
			<TD align="center" style="font-size: 30px;">
				แบบประเมินการให้บริการ (สำหรับผู้จัดจำหน่าย)
			</TD>
		</TR>
		<?php
			if($Doc_No!="")
			{
				?>
					<TR><!-- แสดงแถวนี้ในกรณีที่ เปิด File แสดงข้อมูล -->
						<TD align="right" class = "class_tahoma_12_normal">
							<?php 
								echo $Doc_No; // แสดงเลขที่เอกสาร
							?>
						</TD>
					</TR>
				
				<?php	
			}
		?>
		<TR><!-- Start Row No. 2  -->
			<TD align = "left" class = "class_tahoma_12_bold" >
				<BR>
				ตอนที่ 1 ผู้ประเมิน
			</TD>
		</TR>
		<TR class = "class_tahoma_12_bold" ><!-- Start Row No. 3  -->
			<TD ALIGN="LEFT">
				ชื่อร้าน / ชื่อผู้จัดจำหน่าย <INPUT name = "Cutomer_Name" id = "Cutomer_Name" TYPE = "TEXT" value = '<?php echo $Dealer_Name; ?>' ReadOnly size = 55 >
				วันที่ <INPUT TYPE = "TEXT" name = "Txt_Date" id = "Txt_Date">
				   	 
			</TD>
		</TR>	
		<TR class = "class_tahoma_12_bold" >
			<TD> 
				ชื่อ-สกุล ผู้ประเมิน <INPUT TYPE = "TEXT"  Name = "Valuator_Name"  ID = "Valuator_Name" size = 40> 
			</TD>
		</TR>
		<TR class = "class_tahoma_12_bold" >
			<TD>
				ตำแหน่ง <INPUT type = "text" Name = "Rank" ID = "Rank" size= 72 >
				 เบอร์ติดต่อกลับ <INPUT type = "text" Name = "Telephone" ID = "Telephone">
			</TD>
		</TR>
	
	
	</TABLE><!-- End Table Table No 1 --> 
	<BR>
	<TABLE><!-- Start Table No. 2 --> 
		<TR>
			<TD colspan="6" align="center" class = "class_tahoma_12_bold" >
				ตอนที่ 2 ความพึงพอใจของท่านต่อการ “ ให้บริการ ”
			</TD>
		</TR>
	</TABLE><!-- End Table No. 2 -->
	
	<TABLE BORDER = "1" width = "98%"><!-- Start Table No. 3 -->
		<TR class = "class_tahoma_12_bold" ><!-- Start Row 1 -->
			<TD colspan ="6" align="center">
				ประเด็นการพิจารณา
			</TD>
		</TR>
		<TR class = "class_tahoma_12_normal"><!-- Start Row 2 -->
			<TD colspan = "6">
				ด้านกระบวนการ ขั้นตอนการให้บริการ
			</TD>
		</TR>
		<TR class = "class_tahoma_12_normal"><!-- Start Row 3 -->
			<TD>
				1.สามารถติดต่อ ประสานได้ง่าย
			</TD>
			<TD>	
				<input name="Check_1"  id = "C1_Excellent" type = "radio" value="5#Excellent">
					<span id = 'P_C1_Excellent'></span>ดีเยี่ยม
			</TD>
			<TD>
				<input name="Check_1"  id = "C1_Good" type = "radio" value="4#Good">
					<span id = 'P_C1_Good'></span>ดี
			</TD>
			<TD>
				<input name="Check_1"  id = "C1_Middle" type = "radio" value="3#Middle">
					<span id = 'P_C1_Middle'></span>ปานกลาง
			</TD>
			<TD>
				<input name="Check_1"  id = "C1_ShouldImprove"type = "radio" value="2#ShouldImprove">
					<span id = 'P_C1_ShouldImprove'></span>ควรปรับปรุง
			</TD>
			<TD>
				<input name="Check_1"  id = "C1_MustImprove"type = "radio" value="1#MustImprove">
					<span id = 'P_C1_MustImprove'></span>ต้องปรับปรุง
			</TD>
		</TR>
		<TR class = "class_tahoma_12_normal"><!-- Start Row 4 -->
			<TD>
				2. ระยะเวลาการให้บริการ รวดเร็ว เป็นไปตามเวลาที่ตกลง
			</TD>
			<TD>	
				<input name="Check_2" id = "C2_Excellent" type = "radio" value="5#Excellent">
					<span id = 'P_C2_Excellent'></span>ดีเยี่ยม
			</TD>
			<TD>
				<input name="Check_2" id = "C2_Good" type = "radio" value="4#Good">
					<span id = 'P_C2_Good'></span>ดี
			</TD>
			<TD>
				<input name="Check_2" id = "C2_Middle" type = "radio" value="3#Middle">
					<span id = 'P_C2_Middle'></span>ปานกลาง
			</TD>
			<TD>
				<input name="Check_2" id = "C2_ShouldImprove" type = "radio" value="2#ShouldImprove">
					<span id = 'P_C2_ShouldImprove'></span>ควรปรับปรุง
			</TD>
			<TD>
				<input name="Check_2" id = "C2_MustImprove"  type = "radio" value = "1#MustImprove">
					<span id = 'P_C2_MustImprove'></span>ต้องปรับปรุง
			</TD>
		</TR>
		<TR class = "class_tahoma_12_normal"><!-- Start Row 5 -->
			<TD>
				3. ความสุภาพ ไมตรีจิต จิตบริการ
			</TD>
			<TD>	
				<input name="Check_3" id = "C3_Excellent" type = "radio" value="5#Excellent">
					<span id = 'P_C3_Excellent'></span>ดีเยี่ยม
			</TD>
			<TD>
				<input name="Check_3" id = "C3_Good" type = "radio" value="4#Good">
					<span id = 'P_C3_Good'></span>ดี
			</TD>
			<TD>
				<input name="Check_3" id = "C3_Middle"  type = "radio" value="3#Middle">
					<span id = 'P_C3_Middle'></span>ปานกลาง
			</TD>
			<TD>
				<input name="Check_3" id = "C3_ShouldImprove" type = "radio" value="2#ShouldImprove">
					<span id = 'P_C3_ShouldImprove'></span>ควรปรับปรุง
			</TD>
			<TD>
				<input name="Check_3" id = "C3_MustImprove" type = "radio" value="1#MustImprove">
					<span id = 'P_C3_MustImprove'></span>ต้องปรับปรุง
			</TD>
		</TR>
		<TR class = "class_tahoma_12_normal"><!-- Start Row 5 -->
			<TD>
				4. ความกระตือรือร้น ช่วยติดตามงาน
			</TD>
			<TD>	
				<input name="Check_4" id = "C4_Excellent" type = "radio" value="5#Excellent">
					<span id = 'P_C4_Excellent'></span>ดีเยี่ยม
			</TD>
			<TD>
				<input name="Check_4" id = "C4_Good"  type = "radio" value="4#Good">
					<span id = 'P_C4_Good'></span>ดี
			</TD>
			<TD>
				<input name="Check_4" id = "C4_Middle" type = "radio" value="3#Middle">
					<span id = 'P_C4_Middle'></span>ปานกลาง
			</TD>
			<TD>
				<input name="Check_4" id = "C4_ShouldImprove"  type = "radio" value="2#ShouldImprove">
					<span id = 'P_C4_ShouldImprove'></span>ควรปรับปรุง
			</TD>
			<TD>
				<input name="Check_4" id = "C4_MustImprove" type = "radio" value="1#MustImprove">
					<span id = 'P_C4_MustImprove'></span>ต้องปรับปรุง
			</TD>
		</TR>
		<TR class = "class_tahoma_12_normal"><!-- Start Row 6 -->
			<TD>
				5. ความรับผิดชอบ
			</TD>
			<TD>	
				<input name="Check_5" id = "C5_Excellent" type = "radio" value="5#Excellent" >
					<span id = 'P_C5_Excellent'></span>ดีเยี่ยม
			</TD>
			<TD>
				<input name="Check_5" id = "C5_Good" type = "radio" value="4#Good">
					<span id = 'P_C5_Good'></span>ดี
			</TD>
			<TD>
				<input name="Check_5" id = "C5_Middle"  type = "radio" value="3#Middle">
					<span id = 'P_C5_Middle'></span>ปานกลาง
			</TD>
			<TD>
				<input name="Check_5" id = "C5_ShouldImprove" type = "radio" value="2#ShouldImprove">
					<span id = 'P_C5_ShouldImprove'></span>ควรปรับปรุง
			</TD>
			<TD>
				<input name="Check_5" id = "C5_MustImprove"  type = "radio" value="1#MustImprove">
					<span id = 'P_C5_MustImprove'></span>ต้องปรับปรุง
			</TD>
		</TR>
	</TABLE><!-- End Table No. 3 -->
	<BR>
	<TABLE class = "class_tahoma_12_bold"><!-- Start Table No. 4 --> 
		<TR>
			<TD colspan="6" ALIGN="LEFT">
				ข้อเสนอแนะอื่น ๆ ( ถ้ามี)  
			</TD>
		</TR>
	</TABLE><!-- End Table No. 4 -->
	<TEXTAREA id = "advice" name = "advice" COLS = 170  ROW = 5></TEXTAREA>
	<BR><BR>
	<P ALIGN = "RIGHT" id = 'Save_Show'> 
		ชื่อ(ผู้บันทึก)<?php echo $Login_Name; ?>
	</P>
	<input type= "hidden" Name = "Save_By" ID = "Save_By" value="<?php echo $Login_Name; ?>" >
	<p align="center">
		<input  name = "btn_save" id = "btn_save" type="button" VALUE = "บันทึก" 
			onclick = "Chk_Input_Data_cr_0089_type_2()" <?php echo $Show_Status; ?> />
	</p>
	</FORM>
	<BR>
	<?php echo $Score_Show; ?>
	<BR><BR>
	<CENTER>
		ขอขอบคุณในความร่วมมือตอบแบบประเมิน
	</CENTER>
	<BR>
	<P align="left" style="font-size: 9px;" width ="100%">
		THCAP-FORM-CR-0089 Rev.001 (20140724)
		
	</P>





</HTML>
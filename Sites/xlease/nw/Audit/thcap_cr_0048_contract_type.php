<?php
	include("../../config/config.php");
	include("document_function.php");
?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link type="text/css" rel="stylesheet" href="act.css"></link>
	<?php
		show_doc_msg("สรุปการโทรตรวจสอบลูกค้านิติบุคคล (THCAP-FORM-CR-0048)"," 22px");
		$Doc_Ref = "cr_0048_contract_type";
		$Input_Ref = "Contract_ID_Input";
	?>
	<fieldset>
		<legend>ข้อมูลหลัก</legend>
		<FORM method = "post" action ="thcap_cr_0048.php"
				onsubmit="return Chk_Input_Data(<?php echo "'".$Doc_Ref."'";?>,<?php echo "'".$Input_Ref."'";?>);"> 
			<DIV align="center" width = "50%">
				<Table>
					<TR><!-- Start Row 1 -->
						<TD align="right">
							<font style="font-size: 12px;"> ประเภทสินเชื่อ <font color="red"> *</font></font>
						</TD>
						<TD>
						<?php	
							Load_Contract_Type_For_Select($Doc_Ref);
						?>
						</TD>
					</TR><!-- End Row 1 -->
					<TR><!-- Start Row 2 -->
						<TD align="right">
							<font style="font-size: 12px;"> เลขที่สัญญา <font color="red"> *</font></font>
						</TD>
						<TD>
						<?php	
							Input_Contract_ID_From_User($Input_Ref);
						?>
						</TD>
						
					</TR><!-- End Row 2 -->
					<TR><!-- Start Row 3 -->
						<TD colspan="2" align="center" >
							<input type = "submit" value="ตกลง" >				
						</TD>
					</TR><!-- End Row 3 -->
					<?php
					
				?>
				</Table>
			</DIV>
			
		</FORM>
	</fieldset>
<?php
	include("History_Check_Document.php");
?>		
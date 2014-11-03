<?php
	include("../../config/config.php");
	include("document_function.php");
?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link type="text/css" rel="stylesheet" href="act.css"></link>
	<?php
		show_doc_msg("แบบประเมินการให้บริการ (สำหรับผู้จัดจำหน่าย) (THCAP-FORM-CR-0089 Rev.001 (20140724)) "," 22px");
		$Doc_Ref = "cr_0089_contract_type";
		$Input_Ref = "Contract_ID_Input";
	?>
	<fieldset>
		<legend>ข้อมูลหลัก</legend>
		<FORM method = "post" action ="thcap_cr_0089.php"
				onsubmit="return Chk_Input_Data_cr_0089_type_1()"> 
			<DIV align="center" width = "50%">
				<Table>
					<TR><!-- Start Row 2 -->
						<TD align="right">
							<font style="font-size: 12px;"> ผู้จัดจำหน่าย <font color="red"> *</font></font>
						</TD>
						<TD>
							<input type = "text" name = "Dealer_Name" ID = "Dealer_Name" >
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
	include("History_Check_Document_cr_0089.php");
?>		
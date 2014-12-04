<?php
	include("../../config/config.php");
	include("document_function.php");
?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link type="text/css" rel="stylesheet" href="act.css"></link>
	
	<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
	
	<script type="text/javascript">
		
			$(document).ready(function(){
				$("#Dealer_Name").autocomplete({
					source: "s_corp.php",
					minLength:1
				});
			});
			
	</script>
	
	<?php
		show_doc_msg_2_part("แบบประเมินการให้บริการ (สำหรับผู้จัดจำหน่าย)","(THCAP-FORM-CR-0089 Rev.001 (20140724))");
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
							<input type = "text" name = "Dealer_Name" ID = "Dealer_Name"  size = 50>
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
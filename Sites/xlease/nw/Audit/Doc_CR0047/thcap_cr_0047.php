<?php
	include("../../../config/config.php");
	include("../document_function.php");
	print_r($_POST);
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>ใบสรุปการตรวจสอบการโทรตรวจสอบเครดิตลูกค้าและเงื่อนไขสินเชื่อ</title>

<script language="javascript" type="text/javascript" src="../js/jquery-1.7.2.min.js"></script>
<script language="javascript" type="text/javascript" src="../js/jquery-ui-1.8.21.custom.min.js"></script>
<link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" /> 
<script language="javascript" type="text/javascript" src="../js/jquery.coolfieldset.js"></script>
<link rel="stylesheet" type="text/css" href="../css/jquery.coolfieldset.css" />
<link type="text/css" rel="stylesheet" href="act.css"></link>

<script>	
	$(function() {
		$( "#tabs" ).tabs();
	});
	$(function() {
		$( ".viewsign button:first" ).button({
            icons: {
                primary: "ui-icon-zoomin"
            },
            text: false
		});
	});

</script>

<style type="text/css">
	.viewCustomerInfo #tabs .ui-tabs-nav.ui-helper-reset.ui-helper-clearfix.ui-widget-header.ui-corner-all .ui-state-default.ui-corner-top.ui-tabs-selected.ui-state-active {
		font-family: Tahoma, Geneva, sans-serif;
		font-size: 14px;
		font-weight: bold;
		color: #eb8f00;
		text-decoration: none;
	}
	
	.viewCustomerInfo #tabs .ui-tabs-nav.ui-helper-reset.ui-helper-clearfix.ui-widget-header.ui-corner-all .ui-state-default.ui-corner-top {
		font-family: Tahoma, Geneva, sans-serif;
		font-size: 14px;
		font-weight: bold;
		color: #1c94c4;
		text-decoration: none;
	}
	
	#tabs
	{
		background-color:#fff;
	}
	
	.viewCustomerInfo #tabs #tabs-1 
	{
		background-color: #fff;
	}
	.ui-widget-content
	{
		background-color: #fff;
	}
	.viewCustomerInfo #tabs 
	{
		background-color: #fff;
	}
	.ui-tabs-panel.ui-widget-content.ui-corner-bottom
	{
		font-family: Tahoma, Geneva, sans-serif;
		font-size: 12px;
		font-weight: normal;
		color: #555;
		text-decoration: none;	
 	}

	.viewCustomerInfo
	{
		width: 950px;
	}
	body
	{
		font-family: Tahoma, Geneva, sans-serif;
		font-size: 12px;
		font-weight: normal;
		color: #555;
		text-decoration: none;
	}
</style>

<link type="text/css" rel="stylesheet" href="css_for_doc.css"></link>
<!---- หน้าต่าง Popup รูปภาพ ---->

 
<script type="text/javascript">
	$(document).ready(function(){
			$("#Emp_End_Date").datepicker({
	        			showOn: 'button',
	        			buttonImage: 'images/calendar.gif',
	        			buttonImageOnly: true,
		        		changeMonth: true,
			        	changeYear: true,
		    	    	dateFormat: 'yy-mm-dd'		
			

			});
	});		
		
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
};

function change_title(){
	var tab_value = $('.ui-tabs-selected').find('a').html();
	var cor_name = $('input[name="corp_regis"]').val();
	if(tab_value!='บันทึกการติดตาม')
	{
		document.title = 'รายละเอียดลูกค้านิติบุคคล_'+cor_name+'_'+tab_value;
	}
}

function printpage(){
	var tab_value = $('.ui-tabs-selected').find('a').html();
	var cor_name = $('input[name="corpName_THA"]').val();
	if(tab_value!='บันทึกการติดตาม')
	{
		document.title = cor_name+'_'+tab_value+'.pdf';
	}
	
	window.print();

}

</script>
	
</head>

<body onload = 'Disable_All_Element_CR0047_Job_Set_Input();'>
<center>
<div class="viewCustomerInfo" style="width: 95%">
	<?php
		// รับค่าตัวแปร จาก Form ที่เรียกใช้งาน
		$Contract_Type = pg_escape_string($_POST['cr_0046_contract_type']);
		$Contract_ID = pg_escape_string(trim($_POST['Contract_ID_Input']));
		show_doc_msg("ใบสรุปการตรวจสอบการโทรตรวจสอบเครดิตลูกค้าและเงื่อนไขสินเชื่อ	"," 22px");
			
		$Doc_Show = pg_escape_string($_GET['Doc_ID']);
		if(strlen($Doc_Show) > 0){
			?>	<div align="right">
						<?php 
							echo "เลขที่เอกสาร   ".$Doc_Show; 
						?>
				</div>
			<?PHP
		}else
		{
			// No thing to do
		}
	
	?>
    <div class="ui-tabs ui-widget ui-widget-content ui-corner-all" id="tabs" >
        <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" >
            <li class="ui-state-default ui-corner-top ui-state-active ui-state-active " style="float: right;"><a href="#tabs-2">หน้าที่ 2</a></li>
            <li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active" style="float: right;"><a href="#tabs-1">หน้าที่ 1</a></li>
        </ul>
            <FORM  id = "frmMain" method="post"  action = "Receive_Doc_Val_cr_0046.php" onsubmit="return Chk_Input_Data_cr_0046()"> 
            	<!-- Chk_Input_Data_cr_0046 In File document_function.php  -->       
        		
        		<div class="ui-tabs-panel ui-widget-content ui-corner-bottom" id="tabs-1">
        			<?php
						include("thcap_cr_0047_Page01.php"); 
						Disable_Element_From_H_To_W(); // ทำให้ Element ในข้ H to W ไม่สามารถใช้งานได้
					?>
  				</div>
  			
        		<div class="ui-tabs-panel ui-widget-content ui-corner-bottom" id="tabs-2">
        			<?php
						include("thcap_cr_0047_Page02.php"); 
					?>
					<div align="center">
        				<?php
							if(pg_escape_string($_POST["Purpose"]) == "Input")
							{
								?>
									<BR><!-- <input type="submit" VALUE = "บันทึก"/> -->
										<input name = "btn_save" id = "btn_save" type="button" VALUE = "บันทึก" onclick = "Chk_Input_Data_cr_0047_Detail()" />		
							<?php	
							}else{
								// nothing
							}
						?>
        			</div>
        		</div>
        	   </FORM>
        	
	</div>	
</div>
</center>
	
</body>
</html>

<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
//$action = "search_general";//$_GET["action"];
$action = $_GET["action"];
?>
<html>
    <head>
    <meta charset="UTF-8">
    <title>ค้นหาข้อมูล</title>
    <link type="text/css" rel="stylesheet" href="css/calendar_events.css"></link>
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
	<script type="text/javascript" src="../../jquery_fancybox/lib/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
    <script type="text/javascript">
    $(document).ready(function(){
	//========= แสดงปฏิทิน datepicker =========//
		 $("#txt_start_date").datepicker({
			showOn: 'button',
			buttonImage: '../../icons/calendar.gif',
			buttonImageOnly: true,
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd'
		});
			/*		
		$("#txt_end_date").datepicker({
			showOn: 'button',
			buttonImage: '../../icons/calendar.gif',
			buttonImageOnly: true,
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd'
		});*/
		
		//<input type="text" name="txt_contract_ref'+ counter +'" id="txt_contract_ref'+ counter +'" value="'+value+'">
	});
	
	//========= ค้นหาชื่อเรื่อง  autocomplete=========//			
	function search_title(){
		$("#txt_title").autocomplete({
			source: "search_title.php",
			minLength:1
		});
	}
	
	//========== ซ่อนหรือแสดงเงื่อนไขสำหรับการค้นหา ==========//
    function select_condition(){
		if($('#ddl_condition').val() == "not"){
			$('#span_title').hide();
			$('#span_created_date').hide();
			$('#span_events_status').hide();
			$('#txt_title').val('');
			$('#txt_start_date').val('');
			//$('#txt_end_date').val('');
		}else if($('#ddl_condition').val() == "title"){
			$('#span_title').show('fast');
			$('#span_created_date').hide();
			$('#span_events_status').hide();
			$('#txt_start_date').val('');
			//$('#txt_end_date').val('');
		}else if($('#ddl_condition').val() == "created_date"){
			$('#span_created_date').show('fast');
			$('#span_title').hide();
			$('#span_events_status').hide();
			$('#txt_title').val('');
		}else if($('#ddl_condition').val() == "events_status"){
			$('#span_events_status').show('fast');
			$('#span_title').hide();
			$('#span_created_date').hide();
			$('#txt_title').val('');
			$('#txt_start_date').val('');
			//$('#txt_end_date').val('');
		}
	}
	
	//========= ตรวจสอบค่าว่าง =========//
	function validate(){
		if($("#ddl_condition").val() == "not"){
			alert('กรุณา เลือกเงื่อนไขในการค้นหา');
			return false;
		}else if($("#ddl_condition").val() == "title"){
			if( $('#txt_title').val() == "" ){
				alert('กรุณาระบุ ระบุชื่อเรื่อง');
				return false;
			}
		}else if($("#ddl_condition").val() == "created_date"){
			if( ($('#txt_start_date').val() == "")  ){
				alert('กรุณา เลือกวันที่');
				return false;
			}
		}else if($("#ddl_condition").val() == "events_status"){
			
		}
		
		search_data();
		
	}
	
	//========== ค้นหาข้อมูล=========//
	function search_data(){
		var keyword = "";
		var file_name = "";
		var action = "general_search"; //$_GET["action"];  $('#search_type').val(); 
		if($("#ddl_condition").val() == "title"){
			keyword = $('#txt_title').val();
		}else if($("#ddl_condition").val() == "created_date"){
			keyword = $('#txt_start_date').val();
		}else if($("#ddl_condition").val() == "events_status"){
			keyword = $('input[id=rdo_events_status]:checked').val();
		}
		
		//alert("55555");
		if(action == "general_search"){
			file_name = "ajax_query_events.php";
		}else{
			file_name = "ajax_query_gen_events.php";
		}
		 
		$('#div_search_result').empty();
		//$('#div_search_result').html('<img src="../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
		$.post(file_name,{
			condition: $('#ddl_condition').val(),
			keyword: keyword,
			events_type: $('input[id=rdo_events_type]:checked').val()
		},
		function(data){
			/*if(data == 1){
				$('#div_gen_events').show();
			}else if(data == 2){
				$('#div_gen_events').hide();
			}*/
			$('#div_search_result').html(data);
		},'html');
	}
	
</script>
    </head>
    <body>
	<table boder="1">
		<tr align="center"><?php include("menu.html");?></tr>
		<tr><div class="block_content1" style="align:center">
        
		
  		<div  style="display:block;width:900px;valign:top;align:center" ><br><br> 
                <form id="frm_search" method="post" action="">
				<input type="hidden" id="search_type" name="search_type" value="<?php echo $search_type = $_GET["search_type"];?>"><br><br><br><br>
				<hr>
                <fieldset>
				<legend>เงื่อนไขการค้นหา</legend>
						<input type="radio" name="rdo_events_type" id="rdo_events_type" value="0">การนัดหมายส่วนตัว
						<input type="radio" name="rdo_events_type" id="rdo_events_type" value="1">การนัดหมายส่วนกลาง
						<br>
                        <select id="ddl_condition" name="ddl_condition" onChange="javascript:select_condition();" >
                            <option value="not" style="background-Color:#FFFCCC">ค้นหาตาม</option>
                            <option value="title">ชื่อเรื่อง</option>
                            <option value="created_date">วันที่นัดหมาย</option>
                            <option value="events_status">สถานะการนัดหมาย</option>
                        </select>
						<span id="span_title" style="display:none" >
							<input type="text" name ="txt_title" id="txt_title" onkeyup="search_title();" onblur="search_title();"  value="" size="80">
						</span>
						<span id="span_created_date" style="display:none">
							<label> วันที่</label>
							<input type="text" name="txt_start_date" id="txt_start_date" size="10" value="<?php echo $start_date; ?>">
							<!--<label>วันที่สิ้นสุด</label>
							<input type="text" name="txt_end_date" id="txt_end_date" size="10" value="">-->
						</span>
						<span id="span_events_status" style="display:none">
							<input type="radio" name="rdo_events_status" id="rdo_events_status" value="1">เปิด
							<input type="radio" name="rdo_events_status" id="rdo_events_status" value="2">ปิด
						</span>
							<input type="button" id="btn_search" name="btn_search" value="ค้นหา" onClick="javascript:validate()">
                </fieldset>
                </form>
				
				<div id ="div_search_result"></div>
				
            </div>
        </div>
		
		</tr>
	</table>
		
       
    </body>
</html>

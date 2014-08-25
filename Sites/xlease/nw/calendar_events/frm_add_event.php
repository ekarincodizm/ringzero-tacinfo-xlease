<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="css/calendar_events.css"></link>
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
	<script type="text/javascript" src="../../jquery_fancybox/lib/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

	<!-- Add jQuery library -->
	<script type="text/javascript" src="../../jquery_fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
	<script type="text/javascript" src="../../jquery_fancybox/source/jquery.fancybox.js?v=2.0.6"></script>
	<link rel="stylesheet" type="text/css" href="../../jquery_fancybox/source/jquery.fancybox.css?v=2.0.6" media="screen" />
	<link rel="stylesheet" type="text/css" href="../../jquery_fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.2" />
	<script type="text/javascript" src="../../jquery_fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.2"></script>
	<link rel="stylesheet" type="text/css" href="../../jquery_fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.2" />
	<script type="text/javascript" src="../../jquery_fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.2"></script>
	<script type="text/javascript" src="../../jquery_fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.0"></script>

    <script type="text/javascript">
    $(document).ready(function(){
    });
	
	var counter = 1;
	//========= ลบ textbox =========//
	function del_textbox(counter){
		if(counter==1){
			return false;
		}
		$("#TextBoxDiv" + counter).remove();
		counter--;
    }
	
	//========= เพิ่ม textbox =========//
	function add_textbox(value){
	//var counter = 1;
	 counter++;
    var newTextBoxDiv = $(document.createElement('span')).attr("id", 'TextBoxDiv' + counter);

	txt_box ='<span><label id="lbl_contract_ref'+counter+'"><a href=http://localhost/xlease-nw/xlease/nw/thcap_installments/frm_Index.php?show=1&idno='+value+' target=_blank>'+value+'</a></label>&nbsp;&nbsp;<img width=15px height=15px src=../../icons/delete1.ico onclick=del_textbox('+counter+'); style=cursor:pointer; ></span>';
   
    newTextBoxDiv.html(txt_box);
    newTextBoxDiv.appendTo("#TextBoxesGroup");
	
	}
	
	//========= ค้นหาเลขที่สัญญา =========//
		function search_contract(){
			$("#contract_ref").autocomplete({
				source: "search_contract.php",
				minLength:1,
				select: function(event, ui){
					if(ui.item.value != ""){
						add_textbox(ui.item.value);
					}
				}
			});
		}
	
	$('#btn_save').click(function(){
	
		var arradd = [];
			for( i=1; i<=counter; i++ ){
				var tmp_contract_ref = $('#lbl_contract_ref'+ i).text();
				arradd[i] =  tmp_contract_ref ;
				//arradd[i] =  { contract_ref:tmp_contract_ref };
				
			}
			
		if($("#topic").val() == ""){
			alert('กรุณา ระบุชื่อเรื่อง');
			return false;
		}else if($("#description").val() == ""){
			alert('กรุณาระบุ รายละเอียด');
			return false;
		}else if($('#starttime_hr').val() == "--"){
			alert('กรุณาระบุ เวลาเริ่มต้น');
			return false;
		}else if($('#endtime_hr').val() == "--"){
			alert('กรุณาระบุ เวลาสิ้นสุด');
			return false;
		}else if(($('#starttime_hr').val()) == ($('#endtime_hr').val())){
			alert('เวลาการนัดหมาย เริ่มต้น กับ  สิ้นสุด ไม่ควรเป็นเวลาเดียวกัน!!!');
			return false;
		}else if(($('#endtime_hr').val()) < ($('#starttime_hr').val())){
			alert('เวลาการนัดหมาย สิ้นสุดไม่ควรน้อยกว่าเวลาเริ่มต้น!!!');
			return false;
		}else if($('#place').val() == ""){
			alert('กรุณาระบุ สถานที่นัดหมาย');
			return false;
		}
		
	
	$.post('save_event.php',{
				action: 'ADD',
				title: $('#topic').val(),
                description: $('#description').val(),
                place: $('#place').val(),
                starttime_hr: $('#starttime_hr').val(),
                starttime_min: $('#starttime_min').val(),
                endtime_hr: $('#endtime_hr').val(),
                endtime_min: $('#endtime_min').val(),
                bday: $('#bday').val(),
                bmonth: $('#bmonth').val(),
                byear: $('#byear').val(),
                shared: $('input[id=rdo_shared]:checked').val(), 
                events_status: $('input[id=rdo_events_status]:checked').val(), 
                contract_ref: arradd,
				need_gen_events: $('input[id=rdo_gen_task_auto]:checked').val(),
				num_of_month1: $('#num_of_month').val()
				
            },
		function(data){
				
				if(data.success);{
					//alert(data);
					//location.href = "frm_cal_date.php";
					
					if($('#hdd_form').val() == "cal_date"){
						alert(data);
						location.href = "frm_cal_date.php";
					}else{
						alert(data);
						location.href = "frm_cal_week.php";
					}
					//alert($('#hdd_form').val() );
					
				}
				
            });
         });
	
	//========== เลือกว่าต้องการสร้างรายการนัดหมายอัตโนมัติหรือไม่ =========//
	function need_gen_events(){
		if($('input[id=rdo_gen_task_auto]:checked').val() == "0"){
			$("#div_gen_task").hide();
			$("#div_save").show();
		}else if($('input[id=rdo_gen_task_auto]:checked').val() == "1"){
			$("#div_gen_task").show();
			$("#div_save").hide();
		}
	}
		
	
	function display_list_events_of_date(){
	$('#dialog-form').remove();
    $('body').append('<div id="div_list_events_of_date"></div>');
    $('#div_list_events_of_date').load('list_events_of_date.php');
		$('#div_list_events_of_date').dialog({ 
			title: 'รายการนัดหมายวันนี้',
			resizable: false,
			modal: true,  
			width: 600,
			height:550,
		close: function(ev, ui){
				$('#div_list_events_of_date').remove();
                }
        });
	}
	
	
	
	function ShowPrint(id){
    
    $('#div_print').remove();
    
    $('body').append('<div id="div_print2"></div>');
    $('#div_print2').load('receipt_api.php?cmd=divprint&receive_date=<?php echo $receive_date; ?>&car_num=<?php echo $car_num; ?>&cost_val=<?php echo $cost_val; ?>&idno='+id);
    $('#div_print2').dialog({
        title: 'ออกใบเสร็จ : '+id,
        resizable: false,
        modal: true,  
        width: 300,
        height: 150,
        close: function(ev, ui){
            $('#div_print2').remove();
        }
    });
}
	
	
	

	
</script>

    <style>
    input.place { margin-bottom:12px; width:79%;  padding: .3em; }
    input.contract_ref { margin-bottom:12px; width:77%;  padding: .3em; }
    fieldset { padding:0; border:0; margin-top:25px; }

    </style>
    </head>
    <body>
	<?php 
	$date = isset($_GET["date"]) ? $_GET["date"] : date("d");
	$month = isset($_GET["month"]) ? $_GET["month"] : date('m'); //ถ้าส่งค่าเดือนมาใช้ค่าที่ส่งมา ถ้าไม่ส่งมาด้วย ใช้เดือนปัจจุบัน
    $year = isset($_GET["year"]) ? $_GET["year"] : date('Y');
	$form_name = $_GET["form_name"];
	
	//echo $date;
	//echo $month;
	//echo $year;
	//echo date("H");
	?>
            <form name="frm_events" id="frm_events" method="post" action="">
            <div id="dialog-form" title="Event Calendar">
            <fieldset>
                    <input type="hidden" name ="hdd_form" id="hdd_form" value="<?php echo $form_name; ?>" />
                    <label for="topic">ชื่อเรื่อง</label> &nbsp;&nbsp;<label for="topic" class="required_field">*</label>
                    <input type="text" name="topic" id="topic" value="" class="text ui-widget-content ui-corner-all" /> 
                    <br>    
                    <label for="detail">รายละเอียด</label> &nbsp;&nbsp;<label for="topic" class="required_field">*</label>
                    <textarea rows="4" name="description" id="description" class="text ui-widget-content ui-corner-all"></textarea>
                    <br>
                    <label>สถานที่นัดหมาย</label>&nbsp;&nbsp;<label for="topic" class="required_field">*</label>
                    <input type="text" name="place" id="place" value="" class="place ui-widget-content ui-corner-all" />
                    <br>
					
					
                    <label>รหัสเลขสัญญาอ้างอิง</label>
                   <input type="text" name ="contract_ref" id="contract_ref" onkeyup="search_contract();" onblur="search_contract();"  class="contract_ref ui-widget-content ui-corner-all" >
                   <span id="TextBoxesGroup"></span>
				   
				  <!-- <div id="log_contract_ref" style="height: 30px; width: 500px; overflow: auto;" class="contract_ref ui-widget-content ui-corner-all"></div>
				  <!-- <textarea rows="4" name="txt_ct" id="txt_ct" class="text ui-widget-content ui-corner-all"></textarea> -->
				   <br>
                    <label for="name">เวลาเริ่มต้น (hh:mm)</label>
                    <select name="starttimehr" id="starttime_hr" >
                       <?php
						$hr_now = date('H');
						for($i=1 ; $i <= 24 ; $i++)
						{
							if($i<10){
								$hr="0".$i;
								//$day=$i;
							}else{
								$hr=$i;
							}
                          ?>
						<option value="<?php echo $hr ?>"
                              <?php  if($hr == $hr_now){ echo "selected"; } ?> >
                              <?php echo $hr; ?></option>
						<?php } ?>
					</select>
                        :
                        <select name="starttimemin" id="starttime_min" >
                       <?php
						$min_now = date('i');
						for($i=0 ; $i <= 59 ; $i++)
						{
							if($i<10){
								$min="0".$i;
								
							}else{
								$min=$i;
							}
                          ?>
						<option value="<?php echo $min ?>"
                              <?php  if($min == $min_now){ echo "selected"; } ?> >
                              <?php echo $min; ?></option>
						<?php } ?>
					</select>
                    &nbsp;&nbsp;
                   
                    <label for="name">เวลาสิ้นสุด (hh:mm)</label>
					 <select name="endtimehr" id="endtime_hr" >
                       <?php
						$hr_now = date('H');
						for($i=1 ; $i <= 24 ; $i++)
						{
							if($i<10){
								$hr="0".$i;
								//$day=$i;
							}else{
								$hr=$i;
							}
                          ?>
						<option value="<?php echo $hr ?>"
                              <?php  if($hr == $hr_now){ echo "selected"; } ?> >
                              <?php echo $hr; ?></option>
						<?php } ?>
					</select>
					:
                       <select name="endtimemin" id="endtime_min" >
                       <?php
						$min_now = date('i');
						for($i=0 ; $i <= 59 ; $i++)
						{
							if($i<10){
								$min="0".$i;
								//$day=$i;
							}else{
								$min=$i;
							}
                          ?>
						<option value="<?php echo $min ?>"
                              <?php  if($min == $min_now){ echo "selected"; } ?> >
                              <?php echo $min; ?></option>
						<?php } ?>
					</select>
                    <br>
				   <br>
                    <label for="name">วันที่นัดหมาย</label>
                   
				   <select name="bday" id="bday" >
                       <?php
                       //========== Display year ==========//
                        //$date_now = date('d');
                       // echo $date_now;
						for($i=1 ; $i <= 31 ; $i++)
						{
							if($i<10){
								//$day="0".$i;
								$day=$i;
							}else{
								$day=$i;
							}
                          ?>
						<option value="<?php echo $day ?>"
                              <?php  if($day == $date){ echo "selected"; } ?> >
                              <?php echo $day; ?></option>
						<?php } ?>
					</select>
					
                    <?php
                    //========== Display month ==========//
                    ?>
                   
					<select id="bmonth" name="bmonth" >
						<?php $month_now = $month?>
						<option value="not"<?php if($month_now=="not"){echo "selected";} ?> style="background-Color:#FFFCCC" >เดือน</option>
						<option value="1"<?php if($month_now=='1'){echo "selected";} ?>>มกราคม</option>
						<option value="2"<?php if($month_now=='2'){echo "selected";} ?>>กุมภาพันธ์</option>
						<option value="3"<?php if($month_now=='3'){echo "selected";} ?>>มีนาคม</option>
						<option value="4"<?php if($month_now=='4'){echo "selected";} ?>>เมษายน</option>
						<option value="5"<?php if($month_now=='5'){echo "selected";} ?>>พฤษภาคม</option>
						<option value="6"<?php if($month_now=='6'){echo "selected";} ?>>มิถุนายน</option>
						<option value="7"<?php if($month_now=='7'){echo "selected";} ?>>กรกฎาคม</option>
						<option value="8"<?php if($month_now=='8'){echo "selected";} ?>>สิงหาคม</option>
						<option value="9"<?php if($month_now=='9'){echo "selected";} ?>>กันยายน</option>
						<option value="10"<?php if($month_now=='10'){echo "selected";} ?>>ตุลาคม</option>
						<option value="11"<?php if($month_now=='11'){echo "selected";} ?>>พฤศจิกายน</option>
						<option value="12"<?php if($month_now=='12'){echo "selected";} ?>>ธันวาคม</option>										
					</select>					
                    
                    <select name="year" id="byear" >
                       <?php
                       //========== Display year ==========//
                        $year_now = $year;
						for($i=10 ; $i >= 0 ; $i--)
						{
                            $this_year = $year_now + $i;
                            //$this_year_eng = $this_year + 543; ?>
						<option value="<?php echo $this_year ?>"
                              <?php  if($year_now == $this_year){ echo "selected"; } ?> >
                              <?php echo $this_year; ?></option>
						<?php } ?>
					</select>
                    </div>   
                   
                     
                   <br>
                   <label>การแบ่งปัน</label>
                   <input type="radio" name="rdo_shared" id="rdo_shared" value="0" checked >ไม่แบ่งปัน 
                   <input type="radio" name="rdo_shared" id="rdo_shared" value="1" >แบ่งปัน
                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                   <label>สถานะการนัดหมาย</label>
                   <input type="radio" name="rdo_events_status" id="rdo_events_status" value="1" checked class="calendar ui-widget-content ui-corner-all">เปิด
                   <input type="radio" name="rdo_events_status" id="rdo_events_status" value="2" class="calendar ui-widget-content ui-corner-all">ปิด
                   <br>
                  
                   <label>ต้องการสร้างการนัดหมายอัตโนมัติหรือไม่?</label>
                   <input type="radio" name="rdo_gen_task_auto" id="rdo_gen_task_auto" value="0"  checked onchange="need_gen_events();"  >ไม่ต้องการ
                   <input type="radio" name="rdo_gen_task_auto" id="rdo_gen_task_auto" value="1" onchange="need_gen_events();" >ต้องการ
                    <br>
                    
                   <div name="div_gen_task" id="div_gen_task" style="display:none" >
                    <br>
                        <label>จำนวนเดือน</label>
                         <select id="num_of_month" name="num_of_month" >
                          <option value="not" style="background-Color:#FFFCCC" >จำนวนเดือน</option>
                          <option value="6">6</option>
						  <option value="12">12</option>
						  <option value="24">24</option>
						  <option value="36">36</option>
						  <option value="48">48</option>				
                        </select>	
				   <br><br>
                   </div>
				    <input type="button" name="btn_save" id="btn_save" value="บันทึก"/>
            </fieldset> 
            </div>          
       </form> 
    </body>
</html>

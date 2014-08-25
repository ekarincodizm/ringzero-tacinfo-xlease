<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php 
session_start(); 
include("../../config/config.php");

    $id = pg_escape_string($_GET["id"]);
	$action = pg_escape_string($_GET["action"]);
       
        
    $query_events_detail = "SELECT * FROM \"v_calendar_events_all\" WHERE \"id\" = $id  ";
	
	   
    $results = pg_query($query_events_detail);						 
    $num_rows = pg_num_rows($results);
            
    $row = pg_fetch_array($results);
?>

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
	
	$('#btn_save').click(function(){
	
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
		}
		
	$.post('save_event.php',{
				action: "EDIT",
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
                contract_ref: $('#contract_ref').val(),
				need_gen_events: '0',//$('input[id=rdo_gen_task_auto]:checked').val(),
				events_id: $('#events_id').val(),
				id: $('#id').val()
            },
		function(data){
				alert(data);
				//$('#dialog-form').hide();
				location.href = "frm_cal_date.php";
				//$('#dialog-form').remove();
				
		
		/*
		 $('body').append('<div id="dialog_list_events"></div>');
		  $('#dialog_list_events').load('test.php');
		  $('#dialog_list_events').dialog({
		    title: 'แสดงข้อมูลการนัดหมาย',
		    resizable: false,
		    modal: true,  
		    width: 800,
		    height: 600,
				});	
*/
				
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
			
	//========= ค้นหาเลขที่สัญญา =========//			
	function search_contract(){
		$("#contract_ref").autocomplete({
			source: "search_contract.php",
			minLength:1
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
	//$date = isset($_GET["date"]) ? $_GET["date"] : date("d");
	//$month = isset($_GET["month"]) ? $_GET["month"] : date('m'); //ถ้าส่งค่าเดือนมาใช้ค่าที่ส่งมา ถ้าไม่ส่งมาด้วย ใช้เดือนปัจจุบัน
   // $year = isset($_GET["year"]) ? $_GET["year"] : date('Y');
	
	//echo $date;
	//echo $month;
	//echo $year;
	//echo date("H");
	?>
            <form name="frm_events" id="frm_events" method="post" action="">
            <div id="dialog-form" title="Event Calendar">
            <fieldset>
                    <input type="hidden" name="events_id" id="events_id" value="<?php echo $row["events_id"]; ?>"/>
					<input type="hidden" name="id" id="id" value="<?php echo $row["id"]; ?>"/>
                    <label for="topic">ชื่อเรื่อง</label> &nbsp;&nbsp;<label for="topic" class="required_field">*</label>
                    <input type="text" name="topic" id="topic" value="<?php echo $row["title"]; ?>" class="text ui-widget-content ui-corner-all" /> 
                    <br>    
                    <label for="detail">รายละเอียด</label> &nbsp;&nbsp;<label for="topic" class="required_field">*</label>
                    <textarea rows="4" name="description" id="description" value="" class="text ui-widget-content ui-corner-all"><?php echo $row["description"];?></textarea>
                    <br>
                    <label>สถานที่นัดหมาย</label>
                    <input type="text" name="place" id="place" value="<?php echo $row["place"];?>" class="place ui-widget-content ui-corner-all" />
                    <br>
                    <label>รหัสเลขสัญญาอ้างอิง</label>
                   <input type="text" name ="contract_ref" id="contract_ref" value="<?php echo $row["contract_ref"]; ?>" onkeyup="search_contract();" onblur="search_contract();" class="contract_ref ui-widget-content ui-corner-all" >
                   <br>
                    <label for="name">เวลาเริ่มต้น (hh:mm)</label>
                    <select name="starttimehr" id="starttime_hr" >
                       <?php
						$hr_now = date('H');
						$hh = substr($row["start_time"],0,2);
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
                              <?php  if($hr == $hh){ echo "selected"; } ?> >
                              <?php echo $hr; ?></option>
						<?php } ?>
					</select>
                        :
                        <select name="starttimemin" id="starttime_min" >
                       <?php
						$min_now = date('i');
						$mm = substr($row["start_time"],3,2);
						for($i=0 ; $i <= 59 ; $i++)
						{
							if($i<10){
								$min="0".$i;
								
							}else{
								$min=$i;
							}
                          ?>
						<option value="<?php echo $min ?>"
                              <?php  if($min == $mm){ echo "selected"; } ?> >
                              <?php echo $min; ?></option>
						<?php } ?>
					</select>
                    &nbsp;&nbsp;
                   
                    <label for="name">เวลาสิ้นสุด (hh:mm)</label>
					 <select name="endtimehr" id="endtime_hr" >
                       <?php
						$hr_now = date('H');
						$hh = substr($row["end_time"],0,2);
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
                              <?php  if($hr == $hh){ echo "selected"; } ?> >
                              <?php echo $hr; ?></option>
						<?php } ?>
					</select>
					:
                       <select name="endtimemin" id="endtime_min" >
                       <?php
						$min_now = date('i');
						$mm = substr($row["end_time"],3,2);
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
                              <?php  if($min == $mm){ echo "selected"; } ?> >
                              <?php echo $min; ?></option>
						<?php } ?>
					</select>
                    <br>
				   <br>
				  
                    <label for="name">วันที่นัดหมาย</label>
                   
				   <select name="bday" id="bday" >
                       <?php
                       //========== Display year ==========//
                        $date_now = date('d');
                       // echo $date_now;
					   $rs_date = $row["day"];
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
                              <?php  if($day == $rs_date){ echo "selected"; } ?> >
                              <?php echo $day; ?></option>
						<?php } ?>
					</select>
					
                    <?php
                    //========== Display month ==========//
                    ?>
                   
					<select id="bmonth" name="bmonth" >
						<?php $rs_month = $row["month"]; ?>
						<option value="not"<?php if($rs_month=="not"){echo "selected";} ?> style="background-Color:#FFFCCC" >เดือน</option>
						<option value="1"<?php if($rs_month=='1'){echo "selected";} ?>>มกราคม</option>
						<option value="2"<?php if($rs_month=='2'){echo "selected";} ?>>กุมภาพันธ์</option>
						<option value="3"<?php if($rs_month=='3'){echo "selected";} ?>>มีนาคม</option>
						<option value="4"<?php if($rs_month=='4'){echo "selected";} ?>>เมษายน</option>
						<option value="5"<?php if($rs_month=='5'){echo "selected";} ?>>พฤษภาคม</option>
						<option value="6"<?php if($rs_month=='6'){echo "selected";} ?>>มิถุนายน</option>
						<option value="7"<?php if($rs_month=='7'){echo "selected";} ?>>กรกฎาคม</option>
						<option value="8"<?php if($rs_month=='8'){echo "selected";} ?>>สิงหาคม</option>
						<option value="9"<?php if($rs_month=='9'){echo "selected";} ?>>กันยายน</option>
						<option value="10"<?php if($rs_month=='10'){echo "selected";} ?>>ตุลาคม</option>
						<option value="11"<?php if($rs_month=='11'){echo "selected";} ?>>พฤศจิกายน</option>
						<option value="12"<?php if($rs_month=='12'){echo "selected";} ?>>ธันวาคม</option>										
					</select>					
                    
                    <select name="year" id="byear" >
                       <?php
                       //========== Display year ==========//
                        $year_now = date('Y');
						$rs_year = $row["year"];
						for($i=10 ; $i >= 0 ; $i--)
						{
                            $this_year = $year_now + $i;
                            //$this_year_eng = $this_year + 543; ?>
						<option value="<?php echo $this_year ?>"
                              <?php  if($this_year == $rs_year){ echo "selected"; } ?> >
                              <?php echo $this_year; ?></option>
						<?php } ?>
					</select>
                    </div>   
                   
                     
                   <br>
                   <label>การแบ่งปัน</label>
                   <input type="radio" name="rdo_shared" id="rdo_shared" <?php if($row["shared"] == "0"){ echo "checked"; }?> value="0" >ไม่แบ่งปัน 
                   <input type="radio" name="rdo_shared" id="rdo_shared" <?php if($row["shared"] == "1"){ echo "checked"; }?> value="1" >แบ่งปัน
                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                   <label>สถานะการนัดหมาย</label>
                   <input type="radio" name="rdo_events_status" id="rdo_events_status" <?php if($row["events_status"] == "1"){ echo "checked"; }?>  value="1" class="calendar ui-widget-content ui-corner-all">เปิด
                   <input type="radio" name="rdo_events_status" id="rdo_events_status" <?php if($row["events_status"] == "2"){ echo "checked"; }?>   value="2" class="calendar ui-widget-content ui-corner-all">ปิด
                   <br>
                  
					<!--
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
                   </div>-->
				    <input type="button" name="btn_save" id="btn_save" value="บันทึก"/>
            </fieldset> 
            </div>          
       </form> 
    </body>
</html>

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
	$('#btn_save').click(function(){
	
		var events_status = "";
		var shared = "";
		var need_gen_events ="";
		
		if(document.getElementById("events_status_opened").checked == true){events_status = document.getElementById("events_status_opened").value;}
		if(document.getElementById("events_status_closed").checked == true){events_status = document.getElementById("events_status_closed").value;}
		if(document.getElementById("shared_no").checked == true){shared = document.getElementById("shared_no").value;}
		if(document.getElementById("shared_yes").checked == true){shared = document.getElementById("shared_yes").value;}
		if(document.getElementById("gen_task_no").checked == true){need_gen_events = document.getElementById("gen_task_no").value;}
		if(document.getElementById("gen_task_yes").checked == true){need_gen_events = document.getElementById("gen_task_yes").value;}
		
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
                shared: shared,
                events_status: events_status,
                contract_ref: $('#contract_ref').val(),
				week_day: $('#week_day').val(),
				need_gen_events: need_gen_events,
				week: $('#week').val(),
				week_of_month: $('#week_of_month').val(),
				week_of_year: $('#year_for_gen_events').val(),
				num_of_month1: $('#num_of_month').val()
            },
		function(data){
		 $('body').append('<div id="dialog_list_events"></div>');
		  $('#dialog_list_events').load('test.php');
		  $('#dialog_list_events').dialog({
		    title: 'แสดงข้อมูลการนัดหมาย',
		    resizable: false,
		    modal: true,  
		    width: 800,
		    height: 600,
				});				
            });
         });
    });
	
	//========== เลือกว่าต้องการสร้างรายการนัดหมายอัตโนมัติหรือไม่ =========//
	function need_gen_events(){
                    if(document.getElementById("gen_task_no").checked == true){
                        $("#div_gen_task").hide();
						$("#div_save").show();
                    }else if(document.getElementById("gen_task_yes").checked == true){
                        $("#div_gen_task").show();
						$("#div_save").hide();
                    }    
                }
	//========= เลือกรูปแบบการนัดหมาย แบบวันที่ (1-31) หรือแบบวัน (จันทร์-ศุกร์)
	function select_cal_type(){
                    if(document.getElementById("cal_date").checked == true){
                        $("#div_cal_date").show();
						$("#div_cal_week").hide();
                    }else if(document.getElementById("cal_week").checked == true){
						$("#div_cal_date").hide();
						$("#div_cal_week").show();
                    }    
                }
</script>

    <style>
    input.place { margin-bottom:12px; width:81%;  padding: .3em; }
    input.contract_ref { margin-bottom:12px; width:76%;  padding: .3em; }
    fieldset { padding:0; border:0; margin-top:25px; }

    </style>
    </head>
    <body>
            <form name="frm_events" id="frm_events" method="post" action="">
            <div id="dialog-form" title="Event Calendar">
            <fieldset>
                    
                    <label for="topic">ชื่อเรื่อง</label> &nbsp;&nbsp;<label for="topic" font-color="red">*</label>
                    <input type="text" name="topic" id="topic" value="" class="text ui-widget-content ui-corner-all" /> 
                    <br>    
                    <label for="detail">รายละเอียด</label> &nbsp;&nbsp;<label for="topic">*</label>
                    <textarea rows="5" name="description" id="description" class="text ui-widget-content ui-corner-all"></textarea>
                    <br>
                    <label>สถานที่นัดหมาย</label>
                    <input type="text" name="place" id="place" value="" class="place ui-widget-content ui-corner-all" />
                    <br>
                    <label>รหัสเลขสัญญาอ้างอิง</label>
                   <input type="text" name ="contract_ref" id="contract_ref" class="contract_ref ui-widget-content ui-corner-all" >
                   <br>
                    <label for="name">เวลาเริ่มต้น (hh:mm)</label>
                    <?php
                    echo "&nbsp;<select name=starttimehr id=starttime_hr>\n\t<option value='--'>--\n";
                            $thour = 24 ;
                            $sthour = 0 ;
                            $midnight=false;
                                if($time12hour==1){
                                    $thour = 13 ; $sthour = 1 ; 
                                }

                                if(($time12hour==1)&&(substr($rowe->starttime,0,2)=="00")) 
                                    $midnight=true ;
                                    for($i = $sthour;$i<$thour;$i++){
                                        if($i<10) {
                                            echo "\t<option value='0$i'" ;
                                        
                                            if(substr($rowe->starttime,0,2)==("0".$i)) 
                                                    echo " selected" ;
                                          
                                            if($time12hour==1){
                                                if((intval(substr($rowe->starttime,0,2)) - 12) == $i) 
                                                    echo " selected " ;
                                            }
                                            echo ">0$i\n" ;
                                        }
                                        else {
                                            echo "\t<option value=$i" ;
                                            
                                            if(substr($rowe->starttime,0,2)=="$i") 
                                                    echo " selected" ;
                                                if (($midnight)&&($i==12)) 
                                                    echo " selected " ;
                                                if($time12hour==1) {
                                                    if ((intval(substr($rowe->starttime,0,2)) - 12) == $i) 
                                                            echo " selected " ;
                                                }
                                            echo ">$i\n";
                                        }
                                    }
                        
                        echo "</select>&nbsp;<b>:</b>&nbsp;\n";
                        echo "<select name=starttimemin id=starttime_min>\n";
                        for ($i=0;$i<60;$i=$i+5){
                        if ($i<10) {
                                echo "\t<option value='0$i'" ;
                                if (substr($rowe->starttime,3,2)==("0".$i)) echo " selected" ;
                                echo ">0$i\n" ;
                                }
                        else {
                                echo "\t<option value=$i" ;
                                if (substr($rowe->starttime,3,2)=="$i") echo " selected" ;
                                echo ">$i\n";
                                }
                        }
                        echo "</select>\n";

                        if ($time12hour==1) {
                          echo " &nbsp; <select name='startperiod'>\n";
                          echo "\t<option value='am'" ;
                          if (intval(substr($rowe->starttime,0,2)) < 12) echo " selected " ; 
                          echo ">am" ;
                          echo "\t<option value='pm'" ;
                          if (intval(substr($rowe->starttime,0,2)) >= 12) echo " selected " ; 
                          echo ">pm" ;
                          echo "</select><br/>\n";
                        }
                        else echo "<input type='hidden' name='startperiod' value=''>" ;
                    
                    ?>
                    &nbsp;&nbsp;
                    <label for="name">เวลาสิ้นสุด (hh:mm)</label>
                    <?php
                     echo "&nbsp;<select name=endtimehr id=endtime_hr>\n\t<option value='--'>--\n";
                        $thour = 24 ;
                        $sthour = 0 ;
                        $midnight=false;
                        if ($time12hour==1) { $thour = 13 ; $sthour = 1 ; }
                        if (($time12hour==1)&&(substr($rowe->endtime,0,2)=="00")) $midnight=true ;
                        for ($i = $sthour;$i<$thour;$i++){
                        if ($i<10) {
                                echo "\t<option value='0$i'" ;
                                if (substr($rowe->endtime,0,2)==("0".$i)) echo " selected" ;
                                if ($time12hour==1) {
                                  if ((intval(substr($rowe->endtime,0,2)) - 12) == $i) echo " selected " ;
                                  }
                                echo ">0$i\n" ;
                                }
                        else {
                                echo "\t<option value=$i" ;
                                if (substr($rowe->endtime,0,2)=="$i") echo " selected" ;
                                if (($midnight)&&($i==12)) echo " selected " ;
                              if ($time12hour==1) {
                                  if ((intval(substr($rowe->endtime,0,2)) - 12) == $i) echo " selected " ;
                                  }
                                echo ">$i\n";
                                }
                        }
                        echo "</select>&nbsp;<b>:</b>&nbsp;\n";
                        echo "<select name=endtimemin id=endtime_min>\n";
                        for ($i=0;$i<60;$i=$i+5){
                        if ($i<10) {
                                echo "\t<option value='0$i'" ;
                                if (substr($rowe->endtime,3,2)==("0".$i)) echo " selected" ;
                                echo ">0$i\n" ;
                                }
                        else {
                                echo "\t<option value=$i" ;
                                if (substr($rowe->endtime,3,2)=="$i") echo " selected" ;
                                echo ">$i\n";
                                }
                        }
                        echo "</select>\n";

                        if ($time12hour==1) {
                          echo " &nbsp; <select name='endperiod'>\n";
                          echo "\t<option value='am'" ;
                          if (intval(substr($rowe->endtime,0,2)) < 12) echo " selected " ; 
                          echo ">am" ;
                          echo "\t<option value='pm'" ;
                          if (intval(substr($rowe->endtime,0,2)) >= 12) echo " selected " ; 
                          echo ">pm" ;
                          echo "</select><br/>\n";
                        }
                        else echo "<input type='hidden' name='endperiod' value=''><br/>\n" ;
                    ?>
                    
                    <br>
                    <label>รูปแบบการนัดหมาย</label>
                   <input type="radio" name="calendar_type" id="cal_date" value="0" checked onchange="select_cal_type();">แบบวันที่ (1-31)
                   <input type="radio" name="calendar_type" id="cal_week" value="1" onchange="select_cal_type();">แบบวัน(จันทร์ - ศุกร์)
				   <br>
				   <div id="div_cal_date" name="div_cal_date"  >
				   <br>
                    <label for="name">วันที่นัดหมาย</label>
                   
				   <select name="bday" id="bday" >
                       <?php
                       //========== Display year ==========//
                        $date_now = date('d');
                        echo $date_now;
						for($i=1 ; $i <= 31 ; $i++)
						{
							if($i<10){
								$day="0".$i;
							}else{
								$day=$i;
							}
                          ?>
						<option value="<?php echo $day ?>"
                              <?php  if($day == $date_now){ echo "selected"; } ?> >
                              <?php echo $day; ?></option>
						<?php } ?>
					</select>
					
                    <?php
                    //========== Display month ==========//
                    ?>
                   
					<select id="bmonth" name="bmonth" >
						<?php $month_now = date('m');?>
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
                        $year_now = date('Y');
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
                    <div id="div_cal_week" name="div_cal_week" style="display:none">
					<br>
					<select id="week_day" name="week_day" >
						<?php $month_now = date('m');?>
						<option value="01"<?php if($month_now=='01'){echo "selected";} ?>>จันทร์</option>
						<option value="02"<?php if($month_now=='02'){echo "selected";} ?>>อังคาร</option>
						<option value="03"<?php if($month_now=='03'){echo "selected";} ?>>พุธ</option>
						<option value="04"<?php if($month_now=='04'){echo "selected";} ?>>พฤหัสบดี</option>
						<option value="05"<?php if($month_now=='05'){echo "selected";} ?>>ศุกร์</option>
						<option value="06"<?php if($month_now=='06'){echo "selected";} ?>>เสาร์</option>
						<option value="07"<?php if($month_now=='07'){echo "selected";} ?>>อาทิตย์</option>										
					</select>
					<select id="week" name="week" >
						<?php $month_now = date('m');?>
						<option value="01"<?php if($month_now=='01'){echo "selected";} ?>>สัปดาห์ที่ 1</option>
						<option value="02"<?php if($month_now=='02'){echo "selected";} ?>>สัปดาห์ที่ 2</option>
						<option value="03"<?php if($month_now=='03'){echo "selected";} ?>>สัปดาห์ที่ 3</option>
						<option value="04"<?php if($month_now=='04'){echo "selected";} ?>>สัปดาห์ที่ 4</option>
						<option value="05"<?php if($month_now=='05'){echo "selected";} ?>>สัปดาห์ที่ 5</option>										
					</select>
					<select id="week_of_month" name="week_of_month" >
						<?php $month_now = date('m');?>
						<option value="not"<?php if($month_now=="not"){echo "selected";} ?> style="background-Color:#FFFCCC" >เดือน</option>
						<option value="01"<?php if($month_now=='01'){echo "selected";} ?>>มกราคม</option>
						<option value="02"<?php if($month_now=='02'){echo "selected";} ?>>กุมภาพันธ์</option>
						<option value="03"<?php if($month_now=='03'){echo "selected";} ?>>มีนาคม</option>
						<option value="04"<?php if($month_now=='04'){echo "selected";} ?>>เมษายน</option>
						<option value="05"<?php if($month_now=='05'){echo "selected";} ?>>พฤษภาคม</option>
						<option value="06"<?php if($month_now=='06'){echo "selected";} ?>>มิถุนายน</option>
						<option value="07"<?php if($month_now=='07'){echo "selected";} ?>>กรกฎาคม</option>
						<option value="08"<?php if($month_now=='08'){echo "selected";} ?>>สิงหาคม</option>
						<option value="09"<?php if($month_now=='09'){echo "selected";} ?>>กันยายน</option>
						<option value="10"<?php if($month_now=='10'){echo "selected";} ?>>ตุลาคม</option>
						<option value="11"<?php if($month_now=='11'){echo "selected";} ?>>พฤศจิกายน</option>
						<option value="12"<?php if($month_now=='12'){echo "selected";} ?>>ธันวาคม</option>										
					</select>	
					<select name="week_of_year" id="week_of_year" >
                       <?php
                       //========== Display year ==========//
                        $year_now = date('Y');
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
                   <input type="radio" name="shared" id="shared_no" value="0" checked >ไม่แบ่งปัน 
                   <input type="radio" name="shared" id="shared_yes" value="1" >แบ่งปัน
                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                   <label>สถานะการนัดหมาย</label>
                   <input type="radio" name="events_status" id="events_status_opened" value="0" checked class="calendar ui-widget-content ui-corner-all">เปิด
                   <input type="radio" name="events_status" id="events_status_closed" value="1" class="calendar ui-widget-content ui-corner-all">ปิด
                   <br>
                  
                   <label>ต้องการสร้างการนัดหมายอัตโนมัติหรือไม่?</label>
                   <input type="radio" name="gen_task_auto" id="gen_task_no" value="0"  checked onchange="need_gen_events();"  >ไม่ต้องการ
                   <input type="radio" name="gen_task_auto" id="gen_task_yes" value="1" onchange="need_gen_events();" >ต้องการ
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

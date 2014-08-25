<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php

include("../../config/config.php");
include_once ('cal_header.inc.php');
include_once ("search_func.php");
include_once ("cal_utils.php");

$action = "search_general_1";//$_GET["action"];
?>
<html>
    <head>
    <meta charset="UTF-8">
    <title>ปฏิทินนัดหมาย</title>
    <!--<link type="text/css" rel="stylesheet" href="css/calendar_events.css"></link>-->
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
	<script type="text/javascript" src="../../jquery_fancybox/lib/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function(){
	});	
	
	//========= เปิดหน้าจอบันทึกข้อมูลการนัดหมาย  (frm_add_events.php)=========//
	function form_modal_add_events(date,month,year){	
		$('body').append('<div id="dialog-form"></div>');
		$('#dialog-form').load('frm_add_event.php?form_name=cal_week&date='+date+'&month='+month+'&year='+year);
		$('#dialog-form').dialog({
		    title: 'บันทึกการนัดหมาย',
		    resizable: false,
		    modal: true,  
		    width: 650,
		    height: 560,
			close: function(ev, ui){
						$('#dialog-form').remove();
					}
			});	
		}

	//========= ค้นหาเลขที่สัญญา =========//			
	function search_title(){
		$("#txt_title").autocomplete({
			source: "search_title.php",
			minLength:1
		});
	}

    function goTo(month, year){
        window.location.href = "frm_cal_week.php?year="+ year +"&month="+ month;
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
			if( ($('#txt_start_date').val() == "") && ($('#txt_end_date').val() == "") ){
				alert('กรุณา เลือกวันที่');
				return false;
			}
		}else if($("#ddl_condition").val() == "events_status"){
			
		}
		
	}
	
	
</script>

    </head>
    <body>
	<table boder="1">
		<tr align="center"><?php include("menu.html");?></tr>
		<tr>
		<div class="block_content1" style="align:center">
			<div  style="display:block;width:900px;valign:top;align:center" >
				<br><br><br><br><br><br>
				<hr>
				<!--<div style="display:block;width:900px;valign:top;align:center"  > -->
				<!--	<div style="display:block;width:900px;valign:top;align:center" > -->
							<a href="frm_cal_date.php"><img src="../../icons/today.gif" align="right" alt="แบบวันที่" onclick='form_modal_add_events($i,$month,$year);' style="cursor:pointer;"></a>
							<a href="frm_cal_week.php"><img src="../../icons/week.gif" align="right" alt="แบบวัน" onclick='form_modal_add_events($i,$month,$year);' style="cursor:pointer;"></a>
				<!--	</div> -->
				<br/>
				
				<?php
                
                    $weekDay = array('อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสฯ', 'ศุกร์', 'เสาร์');
                    $thaiMon = array( "01" => "มกราคม", "02" => "กุมภาพันธ์", "03" => "มีนาคม", "04" => "เมษายน",
                    "05" => "พฤษภาคม","06" => "มิถุนายน", "07" => "กรกฎาคม", "08" => "สิงหาคม",
                    "09" => "กันยายน", "10" => "ตุลาคม", "11" => "พฤศจิกายน", "12" => "ธันวาคม");

                    //Sun - Sat
                    $month = isset($_GET['month']) ? $_GET['month'] : date('m'); //ถ้าส่งค่าเดือนมาใช้ค่าที่ส่งมา ถ้าไม่ส่งมาด้วย ใช้เดือนปัจจุบัน
                    $year = isset($_GET['year']) ? $_GET['year'] : date('Y'); //ถ้าส่งค่าปีมาใช้ค่าที่ส่งมา ถ้าไม่ส่งมาด้วย ใช้ปีปัจจุบัน
					$year1 = $year;
					
					//========= หาวันแรกของสัปดาห์ =========//
					$day = date("d");
					$month = date("m");
					$year =  date("Y");
					 
					




/************************/
/* view events per week */
/************************/

			function week($week,$date){
			global $mth,$week,$m,$d,$y,$ld,$fd,$viewweekok,$viewcalok,$viewdayok,$searchweekok,$popupevent,$popupeventwidth,$popupeventheight,$weekstartday,$calstartyear,$caladvanceyear,$allowsearch,$addeventok,$userid,$userview,$userlogin,$addeventwin_w,$addeventwin_h,$shortdesclen,$catview,$uname,$ugroup,$showuserentry,$publicview,$weekview_w,$notimeentry;

			

				if (!$date){
				$year = $y;
				$month = $m;
				$day = $d;
				}
				else{
				$year = substr($date,0,4);
				$month = substr($date,5,2);
				$day = substr($date,8,2);
				}

				// offset to get correct day calculation when "date" is sent
				$day_offset = date("w", mktime(0,0,0,$month,$day,$year));
				$dayoff = 0 ;		// use to get back original url "date"
				while ($day_offset>date("w")) { 
					$day-- ; 
					$day_offset = date("w", mktime(0,0,0,$month,$day,$year));
					$dayoff++ ;
				}
				while ($day_offset<date("w")) { 
					$day++ ; 
					$day_offset = date("w", mktime(0,0,0,$month,$day,$year));
					$dayoff-- ;
				}


			// get first day of the week based on "$weekstartday"
				function firstDayOfWeek($year,$month,$day){
				 global $fd,$weekstartday;
				 $dayOfWeek=date("w");
				 $sunday_offset = ($dayOfWeek) * 60 * 60 * 24 ;
				 $startday_offset = ($weekstartday-1) * 60 * 60 * 24;
				 $fd = date("Y-m-d", mktime(0,0,0,$month,$day+1,$year) - $sunday_offset + $startday_offset);
				 return $fd;
				}
				firstDayOfWeek($year,$month,$day);

				// get last day of the week based on "$weekstartday"
				function lastDayOfWeek($year,$month,$day){
				 global $ld,$weekstartday;
				 $dayOfWeek=date("w");
				 $saturday_offset= (6-$dayOfWeek) * 60 * 60 * 24 ;
				 $startday_offset = ($weekstartday-1) * 60 * 60 * 24;
				 $ld  = date("Y-m-d", mktime(0,0,0,$month,$day+1,$year) + $saturday_offset + $startday_offset);
				 return $ld;
				}
				lastDayOfWeek($year,$month,$day);


			// display header with week number
						echo "<table  border=1 width=100%  cellspacing=0 align=center><tr><td align=center><b>Events from</b>&nbsp;&nbsp;";

						$fdy = substr($fd,0,4);
						$fdm = substr($fd,5,2);
						if (substr($fdm,0,1) == "0"){
						 $fdm = str_replace("0","",$fdm);}
						$fdd = substr($fd,8,2);
						//echo $fdd." ".$mth[$fdm]." ".$fdy; // เริ่มจากวันที่
						echo "<b>".$fdd."-".$fdm."-".$fdy."</b>"; // from date
						echo " ".("till")." ";
						$ldy = substr($ld,0,4);
						$ldm = substr($ld,5,2);
						if (substr($ldm,0,1) == "0"){
						 $ldm = str_replace("0","",$ldm);}
						$ldd = substr($ld,8,2);
						echo "<b>".$ldd."-".$ldm."-".$ldy."</b>"; //ถึงวันที่

						$weeknumber = weekNumber($day+$dayoff,$month,$year); // แสดงจำนวน week

						if ($ldy>($y+$caladvanceyear)) $weeknumber = 53 ;	// to ensure weeks cannot go beyond restricted dates
						if ($fdy<($calstartyear)) $weeknumber = 1 ;	// to ensure weeks cannot go beyond restricted dates
						echo "&nbsp;&nbsp;<b>(week number : ".$weeknumber.")</b>";

						$gdy = $ldy ;
						$gdm = $ldm ;
						if ($gdy>($y+$caladvanceyear)) { $gdy = $fdy ; $gdm = $fdm ; }
						
						echo "</td></tr></table>" ;

						// display hyperlinks to previous and next week
						$calendyear = $y + $caladvanceyear ;
						echo "<table width=100% border=0  align=center><tr><td align=left><div class=menufont>" ;
						if (($year > $calstartyear) || (($fdm >= 1)&&($fdy == $calstartyear))) 
							{
							echo "<a href=frm_cal_week.php?op=week&date=".date("Y-m-d", mktime(0,0,0,$month,$day-7,$year))."&catview=$catview><< ".("Previous week")."</a> &nbsp; ";
							}
						if ($ldy <= $calendyear) {
						echo "<a href=frm_cal_week.php?op=week&date=".date("Y-m-d", mktime(0,0,0,$month,$day+7,$year))."&catview=$catview>".("Next week")." >> </a>\n";
						}

						// display the hyperlinks to each day
						$weekDay = array('อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสฯ', 'ศุกร์', 'เสาร์');
						$thaiMon = array( "1" => "มกราคม", "2" => "กุมภาพันธ์", "3" => "มีนาคม", "4" => "เมษายน",
											"5" => "พฤษภาคม","6" => "มิถุนายน", "7" => "กรกฎาคม", "8" => "สิงหาคม",
											"9" => "กันยายน", "10" => "ตุลาคม", "11" => "พฤศจิกายน", "12" => "ธันวาคม");
											
						echo "</div></td><td>";
						
							echo "<table border=0 width=100% align=right><tr >
									<td   align=right >
										<form><div>
											<b> สัปดาห์ที่: </b>" ;
											
											echo "<select name=goweek onchange=\"javascript:gotoweek(this);\">";
													for($i=1;$i<53;$i++){
														echo "<option value=".$i ;
																if ($weeknumber==$i) echo " selected " ;
																	echo ">".$i;
													}
													
													if (showWeek53($year)>52){
														for($i=53;$i<=showWeek53($year);$i++){
															echo "<option value=".$i ;
																	if ($weeknumber==$i) echo " selected " ;
																		echo ">".$i;
														}
													}
											echo "</select>&nbsp;&nbsp;&nbsp;&nbsp;";
						
						
											echo "<b>เดือน:</b>" ;
											echo "<select name=gomonth onchange=\"javascript:gotomonth(this);\">";
													for($i=1;$i<13;$i++){
														echo "<option value=".$i ;
																if ($ldm==$i) echo " selected " ;
																echo ">".$thaiMon[$i];
																//echo ">".$mth[$i];
													}
											echo "</select>&nbsp;&nbsp;";
						
									echo "</div></form>" ;
							echo "</td>" ;
						
							//echo "<td align=right >" ;
									//if (($allowsearch==1)&&($searchweekok==1)) search();
							//echo "</td>";
						echo "</tr></table>" ;
						
							
						echo "</td></tr></table>" ; // table ด้านบน


						$ld = date("Y-m-d", mktime(0,0,0,$ldm,$ldd+1,$ldy));
						while ($fd != $ld){
							$fdy = substr($fd,0,4);
							$fdm = substr($fd,5,2);
							if (substr($fdm,0,1) == "0"){
							  $fdm = str_replace("0","",$fdm);
							  }
							$fdd = substr($fd,8,2);
							if($fdd < 10){
								$fdd_to_db = substr($fdd,1,1);
							}else{
								$fdd_to_db = $fdd; 
							}

							// display the day header
							echo "<table width=100%  border=0 class=titlefont cellspacing=0 align=center>";
							
							echo "<tr bgcolor=#BCBCBC ><td valign=top>";
							$weekday = date ("w", mktime(12,0,0,$fdm,$fdd,$fdy));
							echo "<img src=\"../../icons/plus.gif\" onclick=\"form_modal_add_events($fdd,$fdm,$fdy);\" style=\"cursor:pointer;\">";
							
							echo "<b>&nbsp;&nbsp;".$weekDay[$weekday]."&nbsp;</b>";
							
							$weekday++;
							
							 
							echo "<b>".$fdd." ".$thaiMon[$fdm]." ".$fdy."</b>" ;
							
							echo "</td></tr>";
							echo "<tr ><table bgcolor=#EEFDEF width=100% height=40 border=0 valign=top><tr><td valign=top>";
							
								 $query = "select * from v_calendar_events_all where flag = '1' and day = '$fdd_to_db' and month = '$fdm' and year = '$fdy' and approved = '1'  order by day, month,year, start_time";   
								
			
								//echo $query;
								$results = pg_query($query);	
								$num_rows = pg_num_rows($results);
								
								//echo $num_rows;
								$list = '';
								while($row = pg_fetch_array($results)){
									 
									$i_loop++;
									$bgcolor = ( ($i_loop%2)==0 )? "#FFFFFF" : "#F0F0F0" ;
									$id = $row['id'];
									$title = $row['title'];
									$description = $row['description'];
									$place = $row['place'];
									$shared = $row['shared'];
									$events_status = $row['events_status'];
									$start_time =$row['start_time'];
									$end_time = $row['end_time'];
									$day = $row['day'];
									$month =$row['month'];
									$year = $row['year'];
									$approved = $row['approved'];
									$contract_ref = $row['contract_ref'];
									$created_by = $row['created_by'];
									$events_id = $row['events_id'];
									
									if($events_status == "1"){
										$list .= "<div><b>".$start_time.'-'.$end_time.'&nbsp;&nbsp;&nbsp;&nbsp;'.$title."</b><img src='../../icons/edit.png' onclick='modal_edit_events($id);' style='cursor:pointer;' ></div>";
										
									}else{
										$list .= "<div><b><strike>".$start_time.'-'.$end_time.'&nbsp;&nbsp;&nbsp;&nbsp;'.$title."</strike></b></div>";
									}
										$list .= "<div><b>เลขที่สัญญาอ้างอิง: </b>"."&nbsp;&nbsp;";
										$contract_ref_split = explode(",",$contract_ref);
										
										foreach($contract_ref_split as $val){
											if($val == null or $val == "undefined"){echo "";}else{
											$list .= "<a href=http://localhost/xlease-nw/xlease/nw/thcap_installments/frm_Index.php?show=1&idno=".$val." target=_blank>".$val."</a>&nbsp;&nbsp;";}
										}
										
										$list .= "</div>";
										//$list .= "<div>เลขที่สัญญาอ้างอิง: <a href=frm_cal_date.php?contract_ref=$contract_ref target=_blank>".$contract_ref."</a></div>";
										
										$list .= "<div><b>รายละเอียด:</b>".$description."</div>";
										$list .= "<a href='#' onclick='modal_display_events_detail($id)'>  อ่านต่อ  </a>";
										
										$list .= "<br><hr>";
							   }
							echo $list;
							echo "</td></tr></table></tr>";
					echo "</table>";

        
        
	//========== query and display events ==========//
	echo "<table class=weekevent width=$weekview_w cellspacing=0 align=center><tr><td>" ;
		$fd = date("Y-m-d", mktime(0,0,0,$fdm,$fdd+1,$fdy));
		echo "</td></tr></table>" ;
	}
						}

						switch ($op){
							
							// view per week 
							case"week":{
								week($week,$date);
							break;
							}
							
							// default: 
							default:{
							week($week,$date);
							break;
							}
						}


echo "<script>
 
 //========= เปิดหน้าจอสำหรับแสดงรายละเอียดการนัดหมาย  (show_events_detail.php)=========//
 function modal_display_events_detail(id){
    $('body').append('<div id=\"div_show_events_detail\"></div>');
    $('#div_show_events_detail').load('show_events_detail.php?id='+id);
		$('#div_show_events_detail').dialog({ 
			title: 'แสดงรายละเอียดการนัดหมาย',
			resizable: false,
			modal: true,  
			width: 500,
			height:450,
		close: function(ev, ui){
				$('#div_show_events_detail').remove();
                }
        });
}

//========= เปิดหน้าจอสำหรับแก้ไขข้อมูลการนัดหมาย (frm_edit_event.php) =========//
function modal_edit_events(id){
    $('body').append('<div id=\"dialog-form\"></div>');
    $('#dialog-form').load('frm_edit_event.php?id='+id);
		$('#dialog-form').dialog({ 
			title: 'แก้ไขการนัดหมาย',
			resizable: false,
			modal: true,  
			width: 650,
			height:560,
		close: function(ev, ui){
				$('#dialog-form').remove();
                }
        });
}
</script>";
                    ?>
						</tr> 
					</table> 
				</div>
			</div>
        </div>
		</tr>
	</table>
    </body>
</html>

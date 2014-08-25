<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
session_start();
include("../../config/config.php");


$action = "search_general_1";//$_GET["action"];
$created_by = $_SESSION["av_iduser"];
?>
<html>
    <head>
    <meta charset="UTF-8">
    <title>ปฏิทินนัดหมาย</title>
    <link type="text/css" rel="stylesheet" href="css/calendar_events.css"></link>
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
	<script type="text/javascript" src="../../jquery_fancybox/lib/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function(){
	});	
	
	//========= เปิดหน้าจอบันทึกข้อมูลการนัดหมาย  (frm_add_events.php)=========//
	function form_modal_add_events(date,month,year){	
		$('body').append('<div id="dialog-form"></div>');
		$('#dialog-form').load('frm_add_event.php?form_name=cal_date&date='+date+'&month='+month+'&year='+year);
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
		
	//========= เปิดหน้าจอแสดงรายการนัดหมายในแต่ละวัน =========//
	function display_list_events_of_date(date,month,year){
    $('body').append('<div id=\"div_list_events_of_date\"></div>');
    $('#div_list_events_of_date').load('list_events_of_date.php?date='+date+'&month='+month+'&year='+year);
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
	
	//========== ไปยังเดือน ก่อนหน้าหรือถัดไป =========//
    function goTo(month, year){
        window.location.href = "frm_cal_date.php?year="+ year +"&month="+ month;
    }
	
</script>
    </head>
    <body>
	<table boder="1">
		<tr align="center"><?php include("menu.html");?></tr>
		<tr>
		<div class="block_content" style="align:center">
			<div  style="display:block;width:900px;valign:top;align:center" >
			
			<br><br><br><br><br><br>
				<hr>
				<div style="display:block;width:900px;valign:top;align:center"  >
					<div style="display:block;width:900px;valign:top;align:center" >
							<a href="frm_cal_date.php"><img src="../../icons/today.gif" align="right" alt="แบบวันที่" onclick='form_modal_add_events($i,$month,$year);' style="cursor:pointer;"></a>
							<a href="frm_cal_week.php"><img src="../../icons/week.gif" align="right" alt="แบบวัน" onclick='form_modal_add_events($i,$month,$year);' style="cursor:pointer;"></a>
					</div>
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
                    //วันที่
                    $startDay = $year.'-'.$month."-01";   //วันที่เริ่มต้นของเดือน
                    $timeDate = strtotime($startDay);   //เปลี่ยนวันที่เป็น timestamp
                    $lastDay = date("t", $timeDate);   //จำนวนวันของเดือน
                    $endDay = $year.'-'.$month."-". $lastDay;  //วันที่สุดท้ายของเดือน
                    $startPoint = date('w', $timeDate);   //จุดเริ่มต้น วันในสัปดาห์

                    echo "ตำแหน่งของวันที่ $startDay คือ <strong>", $startPoint , " (ตรงกับ วัน" , $weekDay[$startPoint].")</strong>";
 
                    //$title = "เดือน $thaiMon[$month] <strong>". $startDay. " : ". $endDay."</strong>";
                    $title = "เดือน $thaiMon[$month] $year <strong>";
 
                    //ลดเวลาลง 1 เดือน
                    $prevMonTime = strtotime ( '-1 month' , $timeDate  );
                    $prevMon = date('m', $prevMonTime);
                    $prevYear = date('Y', $prevMonTime);
                    
                    //เพิ่มเวลาขึ้น 1 เดือน
                    $nextMonTime = strtotime ( '+1 month' , $timeDate  );
                    $nextMon = date('m', $nextMonTime);
                    $nextYear = date('Y', $nextMonTime);
 
                    echo '<div style=\"display:block;width:900px;valign:top;align:center\">';
                        echo '<div id="nav">
                                <button class="navLeft" onclick="goTo(\''.$prevMon.'\', \''.$prevYear.'\');"><< เดือนที่แล้ว</button>
                                <div class="title"><b>'.$title.'<b></div>
                                <button class="navRight" onclick="goTo(\''.$nextMon.'\', \''.$nextYear.'\');">เดือนต่อไป >></button>
                             </div>';
            
                        echo "<table  align='top' style= 'border: 1px solid #eee; padding: .6em 10px; text-align: left;' >"; //เปิดตาราง
                            echo "<tr >
                                    <th>อาทิตย์</th>
                                    <th>จันทร์</th>
                                    <th>อังคาร</th>
                                    <th>พุธ</th>
                                    <th>พฤหัสฯ</th>
                                    <th>ศุกร์</th>
                                    <th>เสาร์</th>
                                </tr>";
                            
                            $bgcolor="#F4F4F4";
                            echo "<tr  >";    //เปิดแถวใหม่
                                    $col = $startPoint;          //ให้นับลำดับคอลัมน์จาก ตำแหน่งของ วันในสับดาห์

                                    if($startPoint < 7){         //ถ้าวันอาทิตย์จะเป็น 7
                                     echo str_repeat("<td style= 'border: 1px solid #eee; padding: .6em 10px; text-align: left;' width='400px' height='60px' text-align= 'center'> </td>", $startPoint); //สร้างคอลัมน์เปล่า กรณี วันแรกของเดือนไม่ใช่วันอาทิตย์
                                    }

                                    for($i=1; $i <= $lastDay; $i++){ //วนลูป ตั้งแต่วันที่ 1 ถึงวันสุดท้ายของเดือน
                                        $col++;       //นับจำนวนคอลัมน์ เพื่อนำไปเช็กว่าครบ 7 คอลัมน์รึยัง
                                         echo "<td bgcolor=$bgcolor style= 'border: 1px solid #eee; padding: .6em 10px; text-align: left;' width='400px' height='60px' text-align= 'center' valign='top' background-color='#eeeeee'>" , $i , "<img src='../../icons/plus.gif'align='right' onclick='form_modal_add_events($i,$month,$year);' style='cursor:pointer;'>";
									
												$qry = pg_query(" SELECT COUNT(id) FROM calendar_events WHERE \"flag\" = '1'
																											 	AND ( \"created_by\" = '$created_by') AND ( \"day\" = '$i') 
																											AND ( \"month\" = '$month') AND ( \"year\" = '$year') ");
																											
												
												
												$res = pg_fetch_array($qry);
												if($res[0] == 0){echo "";}else{
												echo "<br><br><a href='#' onclick='display_list_events_of_date($i,$month,$year);' > ## ".$res[0]." events ## </a>";
												}
												
											echo"</td>";
											
                                             if($col % 7 == false){   //ถ้าครบ 7 คอลัมน์ให้ขึ้นบรรทัดใหม่
                                            echo "</tr><tr>";   //ปิดแถวเดิม และขึ้นแถวใหม่
                                            $col = 0;     //เริ่มตัวนับคอลัมน์ใหม่
                                        }
                                    }

                                    if($col < 7){         // ถ้ายังไม่ครบ7 วัน
                                     echo str_repeat("<td  style= 'border: 1px solid #eee; padding: .6em 10px; text-align: left;' width='400px' height='60px' text-align= 'center'> </td>", 7-$col); //สร้างคอลัมน์ให้ครบตามจำนวนที่ขาด
                                    }

                            echo '</tr>';  //ปิดแถวสุดท้าย
                        echo '</table>'; //ปิดตาราง
                    echo '</div>';
                    ?>
										</tr> 
									</table> 
								</div>
								
					
				</div>
            </div>
        </div>
		</tr>
	</table>
    </body>
</html>

<?php
//include("connect_db.php");
session_start(); 
include("../../config/config.php");

$created_by = $_SESSION["av_iduser"];
$date = pg_escape_string($_GET["date"]);
$month = pg_escape_string($_GET["month"]);
$year = pg_escape_string($_GET["year"]);

$sql_events_of_date =" SELECT * FROM \"v_calendar_events_all\" WHERE \"flag\" = '1' AND (\"created_by\" = '$created_by') 
								AND (\"day\" = '$date') AND (\"month\" = '$month') AND (\"year\" = '$year')
								ORDER BY \"day\", \"month\", \"year\",\"start_time\" ";

$results = pg_query($sql_events_of_date);						 
$num_rows = pg_num_rows($results);
	
echo "<div id = 'div_list_events_of_date'>";	
echo "<table align='center' valign='top' width='100%' cellpadding='0' cellspacing='1' border='0'>";
	
	if($num_rows == 0){
	echo "<tr  height='25'><td align='center' color:#333;><b>ยังไม่มีข้อมูล</b></td></tr>";
	}else{

     echo "<tr bgcolor='CCCCCC'>
				<td width='20%'><b>วันที่</b></td> 
				<td width='70%'><b>ชื่อเรื่อง</b></td>
				<td width='5%'></td>
				<td width='5%'></td>
			</tr>";
     
            $i_loop =0;
	  while($row = pg_fetch_array($results)){
             
			$i_loop++;
			$bgcolor = ( ($i_loop%2)==0 )? "#FFFFFF" : "#F0F0F0" ;
            $id = $row["id"];
			$title = $row["title"];
            $description = $row["description"];
            $place = $row["place"];
            $shared = $row["shared"];
            $events_status = $row["events_status"];
			$start_time =$row["start_time"];
			$end_time = $row["end_time"];
			$day = $row["day"];
			$month =$row["month"];
			$year = $row["year"];
			$approved = $row["approved"];
			$created_by = $row["created_by"];
            $events_id = $row["events_id"];
                
	  echo "<tr bgcolor='$bgcolor' onMouseOver='className=&quot;row&quot;' onMouseOut='className=&quot;&quot;' >
                    <td><b>";
							if($month <10){
								$format_month = "0".$month;
							}else{
								$format_month = $month;
							}
								
				echo $day.'-'.$format_month.'-'.$year; echo "</b>&nbsp;&nbsp;(";echo $start_time.'-'.$end_time; echo ")</td>";
					
				if($events_status == "1"){
				echo "<td>".$title."</td>";
				echo "<td align='center'> <img src='../../icons/detail.gif' onclick='modal_display_events_detail($id);' style='cursor:pointer;' ></td>";
				echo "<td align='center'> <img src='../../icons/edit.png' onclick='modal_edit_events($id);' style='cursor:pointer;' ></td></tr>";
				}else{
				echo "<td><strike>".$title."</strike></td>";
				echo "<td align='center'> <img src='../../icons/detail.gif' onclick='modal_display_events_detail($id);' style='cursor:pointer;' ></td>";
				echo "<td align='center'> </td></tr>";
				}	
        }
	}
 echo "</table>";
 echo "</div>";

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
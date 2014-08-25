<?php

//========== ประกาศตัวแปรใช้งานภายใน class =========//
$v_name ="";
$user_id = $_SESSION["av_iduser"];
$date = date('d');
$month = date('m');
$year = date('Y');

//========= รับค่าตัวแปร show_event.php ==========//
if($shared == '0'){
    $v_name = "v_calendar_events_private";
}elseif($shared == '1'){
    $v_name = "v_calendar_events_public";
}

$sql_search = "select * from \"$v_name\" where \"day\" = '$date' and \"month\" = '$month' and \"year\" = '$year' ";


$results = pg_query($sql_search);						 
$num_rows = pg_num_rows($results);
	
	echo "<table align='center' valign='top' width='100%' cellpadding='0' cellspacing='0' border='0'>";
	
	if($num_rows == 0){
	echo "<tr  height='25'><td align='center' color:#333;><b>ยังไม่มีข้อมูล</b></td></tr>";
	}else{
    echo "<tr bgcolor='CCCCCC'>
				<td width='550px'><b>วันที</b></td> 
				<td width='50px'></td>
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
                <td>";
					if($events_status == 1){
						echo "<strike><b>$title</b> &nbsp;&nbsp;&nbsp;&nbsp;($day - $month - $year &nbsp;&nbsp;$start_time-$end_time)</strike>  <br><br>
								$description";
					}else{
						echo "<b>$title</b> &nbsp;&nbsp;&nbsp;&nbsp;($day - $month - $year &nbsp;&nbsp;$start_time-$end_time)  <br><br>
						$description";
					}
			echo "</td>       
					<td align='center'><img src='../../icons/detail.gif' onclick='modal_display_events_detail($id);' style='cursor:pointer;'></td>
              </tr>";
        }
	}
 echo"</table>";

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

//========= เปิดหน้าจอสำหรับแก้ไขข้อมูลการนัดหมาย (frm_edit_events.php) =========//
function modal_edit_events(id){
    $('body').append('<div id=\"div_edit_events\"></div>');
    $('#div_edit_events').load('frm_edit_event_new.php?action=EDIT&id='+id);
		$('#div_edit_events').dialog({ 
			title: 'แก้ไขข้อมูลการนัดหมาย',
			resizable: false,
			modal: true,  
			width: 650,
			height:560,
		close: function(ev, ui){
				$('#div_edit_events').remove();
                }
        });
};

</script>";
?>

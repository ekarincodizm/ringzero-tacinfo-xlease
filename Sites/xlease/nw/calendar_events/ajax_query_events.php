<?php
/*
$condition => ให้ค้นหาจาก ชื่อเรื่อง, วันที่นัดหมาย และ สถานะการนัดหมาย  
$events_type => ประเภทการนัดหมาย 0 = การนัดหมายส่วนตัว, 1=การนัดหมายส่วนกลาง

Field ใน DB
- shared = > 
*/
//session_start(); 

include('../../config/config.php');

$created_by = $_SESSION["av_iduser"];
$condition = pg_escape_string($_POST["condition"]);
$keyword =  pg_escape_string($_POST["keyword"]);
$events_type = pg_escape_string($_POST["events_type"]); 

if(!empty($keyword)){
    $show = "";
    $show .= "<table width=\"100%\">";
	
	$qry_search = "SELECT * FROM v_calendar_events_all  WHERE flag = '1'  AND created_by = '$created_by' AND shared ='$events_type' ";
	
	if($condition == "title"){
		$qry_search .= " AND title like '%$keyword%' ";
	}elseif($condition == "created_date"){
		$year = substr($keyword ,0,4);
		$month = substr($keyword ,5,2);
		$date = substr($keyword ,8,2);
		$qry_search .= " AND day = '$date' and month = '$month' and year ='$year' ";
	}elseif($condition == "events_status"){
		$qry_search .= " AND events_status ='$keyword' ";
	}
	
	$results = pg_query($qry_search);						 
	$num_rows = pg_num_rows($results);
	
	if($num_rows == 0){
		$show .= "<tr><td class=\"no_result\"><br>ไม่พบข้อมูล</td></tr>";
	}else{
		$show .= "<tr><br>ผลการค้นพบ  $num_rows รายการ<tr>";
		$show .= "<tr style=\"font-weight:bold; background-color:#C4E1FF;\">
					<td width=\"90%\" class=\"word-wrap\">กำหนดการนัดหมาย</td>
					<td width=\"10%\">แก้ไข</td>
				 </tr>";
	
		while($result = pg_fetch_array($results)){
			$row++;
			
			$id = $result["id"];
			$events_id = $result["events_id"];
			$title = $result["title"];
			$description = $result["description"];
			$events_status = $result["events_status"];
			$day = $result["day"];
			$month = $result["month"];
			$year = $result["year"];
			$start_time = $result["start_time"];
			$end_time = $result["end_time"];
		
			
			if($month <10){
				$format_month = "0".$month;
			}else{
				$format_month = $month;
			}
						 
			if($row%2==0){
				$show .= "<tr class=\"odd\" class=\"word-wrap\">";
			}else{
				$show .= "<tr class=\"even\" class=\"word-wrap\">";
			}
				
					$show .= "<td width=\"90%\" class=\"word-wrap\">";
								if($events_status == 2){//ปิดการนัดหมาย
					$show .= "<strike><b>$title</b> &nbsp;&nbsp;&nbsp;&nbsp;($day/$format_month/$year &nbsp;&nbsp;จาก  $start_time ถึง $end_time)</strike> <br>
									$description <br><br><td></td> <tr>";
								}else{
					$show .= "<b>$title</b> &nbsp;&nbsp;&nbsp;&nbsp;($day/$format_month/$year &nbsp;&nbsp;จาก $start_time ถึง $end_time)<br>
									$description <br><br></td>
							  <td width=\"10%\"><input type=\"hidden\" id=\"id\" name=\"id\" value=\"$id\">
								<img src='../../icons/edit.png' onclick='modal_edit_events($id);' style='cursor:pointer;' >
							  </td>
							</tr>";
								}
		}
	}
}
    $show .= "</table>";
    echo $show;

echo "<script>
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
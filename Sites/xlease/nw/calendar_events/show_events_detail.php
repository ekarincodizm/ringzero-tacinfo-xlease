<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>แสดงรายละเอียดการนัดหมาย</title>
        <link rel="stylesheet" href="jquery-ui-1.10.3/themes/base/jquery.ui.all.css">
        <link rel="stylesheet" href="css/calendar_events.css">
        <script src="jquery-ui-1.10.3/jquery-1.9.1.js"></script>
        <script src="jquery-ui-1.10.3/ui/jquery.ui.core.js"></script>
        <script src="jquery-ui-1.10.3/ui/jquery.ui.widget.js"></script>
        <script src="jquery-ui-1.10.3/ui/jquery.ui.mouse.js"></script>
        <script src="jquery-ui-1.10.3/ui/jquery.ui.button.js"></script>
        <script src="jquery-ui-1.10.3/ui/jquery.ui.draggable.js"></script>
        <script src="jquery-ui-1.10.3/ui/jquery.ui.position.js"></script>
        <script src="jquery-ui-1.10.3/ui/jquery.ui.resizable.js"></script>
        <script src="jquery-ui-1.10.3/ui/jquery.ui.dialog.js"></script>
        <script src="jquery-ui-1.10.3/ui/jquery.ui.accordion.js"></script>
        <script src="jquery-ui-1.10.3/ui/jquery.ui.effect.js"></script>
    </head>
    <body>
    <?php 
        session_start(); 
        include("../../config/config.php");
  
		$id = pg_escape_string($_GET["id"]);
 
		$query_events_detail = "select * from \"v_calendar_events_all\" where \"id\" = $id  ";

		$results = pg_query($query_events_detail);						 
		$num_rows = pg_num_rows($results);
       
		while($row = pg_fetch_array($results)){      
			$id = $row["id"];
			$title = $row["title"];
			$description = $row["description"];
			$place = $row["place"];
			$shared = $row["shared"];
			$appointment_status = $row["events_status"];
			$start_time =$row["start_time"];
			$end_time = $row["end_time"];
			$day = $row["day"];
			$month =$row["month"];
			$year = $row["year"];
			$approved = $row["approved"];
			$created_by = $row["created_by"];
        ?>
        <div id="div_show_event_detail">
            <table border="0" width="100%">
                <tr>
                    <td colspan="2"><b><?php echo $title; ?></b>
                    <hr/>
                    </td>  
                </tr>
                <tr>
                    <td>
						<?php 
							if($month <10){
									$format_month = "0".$month;
								}else{
									$format_month = $month;
								}
						?>
						<b>วันที่:</b>&nbsp;&nbsp;<?php echo $year ."-". $format_month ."-". $day ?>
					</td>
                    <td><b>เวลา: </b>&nbsp;&nbsp;<?php echo $start_time ."-". $end_time ?></td>
                </tr>
                <tr>
                    <td colspan="2"><br><b>รายละเอียด: </b>&nbsp;&nbsp;<?php echo $description;?>
                        <br>
                    </td>  
                </tr>
            </table>
        </div>
       <?php  }?>
    </body>
</html>

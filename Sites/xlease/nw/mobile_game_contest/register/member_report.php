<?php
include("../config/config.php");

function page_navigator($before_p,$plus_p,$total,$total_p,$chk_page){   
	global $e_page;
	global $querystr;
	$urlfile="member_report.php"; // ส่วนของไฟล์เรียกใช้งาน ด้วย ajax (ajax_dat.php)
	$per_page=10;
	$num_per_page=floor($chk_page/$per_page);
	$total_end_p=($num_per_page+1)*$per_page;
	$total_start_p=$total_end_p-$per_page;
	$pPrev=$chk_page-1;
	$pPrev=($pPrev>=0)?$pPrev:0;
	$pNext=$chk_page+1;
	$pNext=($pNext>=$total_p)?$total_p-1:$pNext;		
	$lt_page=$total_p-4;
	if($chk_page>0){  
		echo "<a  href=\"$urlfile?s_page=$pPrev\" class=\"naviPN\">ก่อนหน้า</a>";
	}
	for($i=$total_start_p;$i<$total_end_p;$i++){  
		$nClass=($chk_page==$i)?"class=\"selectPage\"":"";
		if($e_page*$i<$total){
		echo "<a href=\"$urlfile?s_page=$i\" $nClass  >".intval($i+1)."</a> ";   
		}
	}		
	if($chk_page<$total_p-1){
		echo "<a href=\"$urlfile?s_page=$pNext\"  class='naviPN'>ถัดไป</a>";
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>รระบบลงทะเบียน :: TAC INFO e-Commerce Web Design Competition 2013</title>
<link href="../libralies/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
<link href="../libralies/bootstrap/css/bootstrap-responsive.css" rel="stylesheet" type="text/css" />
<link href="../css/flick/jquery-ui-1.9.0.custom.css" rel="stylesheet" type="text/css" />

<link href="css/member_report.css" rel="stylesheet" type="text/css" />
<link href="../css/nevigation.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../scripts/jquery-1.9.0.js"></script>
<script type="text/javascript" src="../scripts/jquery-ui-1.10.0.custom.js"></script>
<script type="text/javascript" src="../libralies/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript">
</script>
</head>

<body>
<div class="show_content">
    <div align="center">
        <div class="container">
            <div class="top">
                <div class="logo"></div>
                <div class="head_title">
                    <!--<div class="step_logo"></div>-->
                </div>
            </div>
            <div class="middle">
                <div class="report_box">
                	<div class="title margin-bottom">
                    	<span class="inline_block">
                    		<h3>รายงานผู้สมัครแข่งขัน .: </h3>
                        </span>
                        <span class="inline_block">
                    		<h3>Member Report</h3>
                        </span>
                    </div>
                    <div class="alert-block alert-info">
                    	<span>ตารางรายชื่อทีมที่สมัครเข้าร่วมแข่งขันโครงการ TEWDC 2013</span>
                    </div>
                    <div class="input_container">
                    	<table class="table-bordered table-striped margin-bottom" width="100%">
                        	<thead>
                                <tr class="border_bottom">
                                    <th width="5%">#</th>
                                    <th width="25%">ชื่อทีม</th>
                                    <th width="20%">ประเภทการแข่งขัน</th>
                                    <th width="13%">วันที่สมัคร</th>
                                    <th width="13%">จำนวนสมาชิก</th>
                                    <th width="13%">รายละเอียด</th>
                                </tr>
                            </thead>
                            <tbody>
                            	<?php
								$q = "select * from \"v_TAC_contest_team\" order by \"teamID\" asc";
								$qr = pg_query($q);
								$total=pg_num_rows($qr);
								$resultRows=pg_num_rows($qr);
								$e_page=30; // กำหนด จำนวนรายการที่แสดงในแต่ละหน้า   
								if(!isset($_GET['s_page'])){   
									$_GET['s_page']=0;   
								}else{   
									$chk_page=$_GET['s_page'];     
									$_GET['s_page']=$_GET['s_page']*$e_page;   
								}   
								$q.=" LIMIT $e_page offset ".$_GET['s_page'];
								$qr=pg_query($q);
								if(pg_num_rows($qr)>=1){   
									$plus_p=($chk_page*$e_page)+pg_num_rows($qr);   
								}else{   
									$plus_p=($chk_page*$e_page);       
								}   
								$total_p=ceil($total/$e_page);   
								$before_p=($chk_page*$e_page)+1;
								
								$i = 1;
								
								while($rs = pg_fetch_array($qr))
								{
									$teamID = $rs['teamID'];
									$team_name = $rs['team_name'];
									$team_register_date = $rs['team_register_date'];
									$type_name = $rs['type_name'];
									$sum_member = $rs['sum_member'];
									
									echo "<tr>";
									echo "<td>$i</td>";
									echo "<td>$team_name</td>";
									echo "<td>$type_name</td>";
									echo "<td>$team_register_date</td>";
									echo "<td align=\"center\">$sum_member</td>";
									echo "<td align=\"center\">";
									echo "<img src=\"images/details.png\" width=\"16\" height=\"16\" onclick=\"window.open('team_details.php?teamid=$teamID');\" style=\"cursor:pointer;\" />";
									echo "</td>";
									echo "</tr>";
								}
								?>
                            </tbody>
                        </table>
                        <div class="row">
                        <?php
                        if($total>0)
                        {
                            echo "<div class=\"browse_page\">"; 
                             // เรียกใช้งานฟังก์ชั่น สำหรับแสดงการแบ่งหน้า   
                            page_navigator($before_p,$plus_p,$total,$total_p,$chk_page);
                            echo "</div>";
                        }
						?>
                        </div>
                    </div>
                </div>
            </div>
            <!--<div class="bottom">
                <div class="bottom_content"></div>
                <div class="powered"></div>
            </div>-->
        </div>
    </div>
</div>
<script type="text/javascript" src="scripts/validate.js"></script>
</body>
</html>
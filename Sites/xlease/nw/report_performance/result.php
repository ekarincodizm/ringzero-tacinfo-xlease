<?php
	include("../../config/config.php");
?>
<style type="text/css">
.row1 {
	background-color:#b7e0fc;
}
.row2 {
	background-color:#a5d8fb;
}
table {
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 13px;
	font-weight: normal;
	color: #555;
	text-decoration: none;
}
#navigate {
	height: 26px;
	background-color: #dadada;
	border: 1px solid #ccc;
}
.browse_page{
	clear:both;
	height:26px;
	margin-top:5px;
	display:block;
	float: right;
	margin-right: 5px;
}
.browse_page a,.browse_page a:hover{
	display:block;
	height:18px;
	width:18px;
	font-size:10px;
	float:left;
	margin-right:2px;
	border:1px solid #CCCCCC;
	background-color:#F4F4F4;
	color:#333333;
	text-align:center;
	line-height:18px;
	font-weight:bold;
	text-decoration:none;
}
.browse_page a:hover{
	border:1px solid #0A85CB;
	background-color:#0A85CB;
	color:#FFFFFF;
}
.browse_page a.selectPage{
	display:block;
	height:18px;
	width:18px;
	font-size:10px;
	float:left;
	margin-right:2px;
	border:1px solid #0A85CB;
	background-color:#0A85CB;
	color:#FFFFFF;
	text-align:center;
	line-height:18px;
	font-weight:bold;
}
.browse_page a.SpaceC{
	display:block;
	height:18px;
	width:18px;
	font-size:10px;
	float:left;
	margin-right:2px;
	border:0px dotted #0A85CB;
	font-size:11px;
	background-color:#FFFFFF;
	color:#333333;
	text-align:center;
	line-height:18px;
	font-weight:bold;
}
.browse_page a.naviPN{
	width:50px;
	font-size:12px;
	display:block;
	height:18px;
	float:left;
	border:1px solid #0A85CB;
	background-color:#0A85CB;
	color:#FFFFFF;
	text-align:center;
	line-height:18px;
	font-weight:bold;	
}
</style>
<?php
	$searchWord=$_GET['word'];
	$searchType=$_GET['type'];
	$searchDate=$_GET['date'];
	
	
	/*if($searchType=="2")
	{
		$sql="select * from public.\"Vmenu_log_adv\" where \"id_user\"='$searchWord' and cast(\"menu_date\" as character varying) like '%$searchDate%'";
	}
	else if($searchType=="1")
	{
		$sql="select * from public.\"Vmenu_log_adv\" where \"id_user\"='$searchWord'";
	}*/
	//echo $sql;
	
	echo "<table width=\"900\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\">";
	echo "<tr bgcolor=\"#59b6f7\" align=\"center\">";
	echo "<td align=\"center\"><b>ลำดับ</b></td>";
	echo "<td align=\"center\"><b>รหัสเมนู</b></td>";
	echo "<td align=\"center\"><b>ชื่อเมนู</b></td>";
	echo "<td align=\"center\"><b>รหัสพนักงาน</b></td>";
	echo "<td align=\"center\"><b>ชื่อพนักงาน</b></td>";
	echo "<td align=\"center\"><b>ชื่อเล่น</b></td>";
	echo "<td align=\"center\"><b>รหัสแผนก</b></td>";
	echo "<td align=\"center\"><b>ชื่อแผนก</b></td>";
	echo "<td align=\"center\"><b>วันและเวลาที่ทำรายการ</b></td>";
	echo "</tr>";
	
	
	
	function page_navigator($before_p,$plus_p,$total,$total_p,$chk_page,$searchWord,$searchType,$searchDate){   
		global $e_page;
		//global $querystr;
		$urlfile="report_performance.php"; // ส่วนของไฟล์เรียกใช้งาน ด้วย ajax (ajax_dat.php)
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
			echo "<a  href='$urlfile?word=".$searchWord."&type=".$searchType."&date=".$searchDate."&s_page=$pPrev' class='naviPN'>ก่อนหน้า</a>";
		}
		for($i=$total_start_p;$i<$total_end_p;$i++){  
			$nClass=($chk_page==$i)?"class=\"selectPage\"":"";
			if($e_page*$i<$total){
			echo "<a href='$urlfile?word=".$searchWord."&type=".$searchType."&date=".$searchDate."&s_page=$i' $nClass  >".intval($i+1)."</a>";   
			}
		}		
		if($chk_page<$total_p-1){
			echo "<a href='$urlfile?word=".$searchWord."&type=".$searchType."&date=".$searchDate."&s_page=$pNext'  class='naviPN'>ถัดไป</a>";
		}
	}
	if($searchType=="2")
	{
		$q="select * from public.\"Vmenu_log_adv\" where \"id_user\"='$searchWord' and cast(\"menu_date\" as character varying) like '%$searchDate%' order by \"mlogid\" asc";
	}
	else if($searchType=="1")
	{
		$q="select * from public.\"Vmenu_log_adv\" where \"id_user\"='$searchWord' order by \"mlogid\" desc";
	}
	$qr=pg_query($q);
	$total=pg_num_rows($qr);
	$resultRows=pg_num_rows($qr);
	$e_page=50; // กำหนด จำนวนรายการที่แสดงในแต่ละหน้า   
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
	
	
	$i=1;
	//$dbquery=pg_query($sql);
	//$rows=pg_num_rows($dbquery);
	if($resultRows==0)
	{
		echo "<tr>";
		echo "<td align=\"center\" colspan=\"9\" bgcolor=\"#b7e0fc\">*ไม่มีข้อมูล</td>";
		echo "</tr>";
	}
	$m=0;
	while($rs=pg_fetch_assoc($qr))
	{
		$mlogid=$rs['mlogid'];
		$id_menu=$rs['id_menu'];
		$name_menu=$rs['name_menu'];
		$id_user=$rs['id_user'];
		$fullname=$rs['fullname'];
		$nickname=$rs['nickname'];
		$user_group=$rs['user_group'];
		$dep_name=$rs['dep_name'];
		$menu_date=$rs['menu_date'];
		if($m%2==0)
		{
			echo "<tr class=\"row1\">";
		}
		else
		{
			echo "<tr class=\"row2\">";
		}
		
		echo "<td>$mlogid</td>";
		echo "<td>$id_menu</td>";
		echo "<td>$name_menu</td>";
		echo "<td>$id_user</td>";
		echo "<td>$fullname</td>";
		echo "<td>$nickname</td>";
		echo "<td>$user_group</td>";
		echo "<td>$dep_name</td>";
		echo "<td>$menu_date</td>";
		echo "</tr>";
		$m++;
	}
	if($total>0)
	{
		echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"900\" id=\"tbNavigate\">";
		echo "<tr>";
		echo "<td align=\"center\" valign=\"middle\" id=\"navigate\">";
		echo "<div class=\"browse_page\">"; 
		 // เรียกใช้งานฟังก์ชั่น สำหรับแสดงการแบ่งหน้า   
		page_navigator($before_p,$plus_p,$total,$total_p,$chk_page,$searchWord,$searchType,$searchDate);
		echo "</div>";
		echo "</td>";
		echo "</tr>";
		echo "</table>";
    }
?>
<?php
	include("config/config.php");
	function page_navigator($before_p,$plus_p,$total,$total_p,$chk_page){   
		global $e_page;
		global $querystr;
		$urlfile="approved.php"; // ส่วนของไฟล์เรียกใช้งาน ด้วย ajax (ajax_dat.php)
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
			echo "<a  href=\"$urlfile?s_page=$pPrev&querystr=".$querystr."\" class=\"naviPN\">ก่อนหน้า</a>";
		}
		for($i=$total_start_p;$i<$total_end_p;$i++){  
			$nClass=($chk_page==$i)?"class=\"selectPage\"":"";
			if($e_page*$i<$total){
			echo "<a href=\"$urlfile?s_page=$i&querystr=".$querystr."\" $nClass  >".intval($i+1)."</a> ";   
			}
		}		
		if($chk_page<$total_p-1){
			echo "<a href=\"$urlfile?s_page=$pNext&querystr=".$querystr."\"  class='naviPN'>ถัดไป</a>";
		}
	}
	
	if($_SESSION['app_username']==""||$_SESSION['app_user_type']=="user")
	{
		echo "<script type=\"text/javascript\">window.location.href = 'index.php';</script>";
	}
	else
	{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>หน้าของฉัน</title>
<link href="css/home.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div align="center">
	<?php require("proc/top.php"); ?>
	<div class="container">
    	<div class="title"><h1>แบบฟอร์มที่ผ่านการพิจารณาแล้ว</h1></div>
        <div class="content">
        	<ul class="list">
            	<li class="row darkgray">
                    <div class="name bold center inline">หัวข้อเรื่อง</div>
                    <div class="state bold center inline">สถานะ</div>
                    <div class="state bold center inline">ผู้ทำรายการ</div>
                    <div class="date bold center inline">วันที่</div>
                    <div class="form bold center inline">การปรับปรุง</div>
                </li>
                <?php
				
					$q = "select \"formID\",\"company_name\",\"doer_name\",\"doer_time\",\"form_state\" from $schema.\"app_frm\" where \"form_state\"='3' or \"form_state\"='4' order by \"formID\" desc";
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
					$i = 0;
					while($rs = pg_fetch_array($qr))
					{
						$id = $rs['formID'];
						$title = $rs['company_name'];
						$state = $rs['form_state'];
						$q1 = "select \"status_name\" from $schema.\"status\" where \"statusID\"='$state'";
						$qr1 = pg_query($q1);
						if($q1)
						{
							$rs1 = pg_fetch_array($qr1);
							$state = $rs1['status_name'];
						}
						$doer_name = $rs['doer_name'];
						$doer_time = mb_substr($rs['doer_time'],0,10);
						if($i%2==0)
						{
							echo "<li class=\"row lightgray\">";
						}
						else
						{
							echo "<li class=\"row\">";
						}
						$i++;
						echo "<div class=\"name left inline\"><a class=\"link\" href=\"show_form.php?id=$id\">$title</a></div>";
						echo "<div class=\"state center inline\">$state</div>";
						echo "<div class=\"state center inline\">$doer_name</div>";
						echo "<div class=\"date center inline\">$doer_time</div>";
						echo "<div class=\"form center inline\">พิจารณาแล้ว</div>";
						echo "</li>";
					}
				?>
            </ul>
        </div>
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
<script type="text/javascript" src="scripts/jquery-1.8.2.js"></script>
<script type="text/javascript">
function mark(id) {
	$.post('proc/mark_frm.php',{id:id},function(data){
		if(data=='1')
		{
			window.location.reload();
		}
		else
		{
			alert('ไม่สามารถดำเนินการได้');
		}
	});
}
</script>
</body>
</html>
<?php
	}
?>
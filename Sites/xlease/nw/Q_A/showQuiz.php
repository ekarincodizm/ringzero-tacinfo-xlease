<?php

	include("../../config/config.php");
	$id_user=$_SESSION['av_iduser'];
	$sql="select * from \"Vfuser\" where \"id_user\"='$id_user'";
	$dbquery=pg_query($sql);
	$result=pg_fetch_assoc($dbquery);
	
	$user=$result['username'];
	$emp_level = $result['emplevel'];
	
	$id=$_GET['id'];
	$type=$_GET['type'];
	$word=$_POST['tbxSearch'];
	//$poster=$_POST['tbxPoster'];
	$content=$_POST['tbxContent'];
	$date=date("Y-m-d H:i:s");
	if($type=="post")
	{
		$sql="select * from \"qaAnswer\" where \"questionID\"='$id' and \"answerContent\"='$content' and \"answerPoster\"='$user'";
		$dbquery=pg_query($sql);
		$numRow=pg_num_rows($dbquery);
		if($numRow==0)
		{
			if($content!="")
			{
				$sql1="insert into \"qaAnswer\"(\"questionID\", \"answerContent\", \"answerPoster\", \"answerPostTime\") values('$id','$content','$user','$date')";
				$dbquery1=pg_query($sql1);
				
				//ACTIONLOG
				$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(TAL) ทำการ POST ระบบถามตอบ', '$date')");
				//ACTIONLOG---
				
				echo "
					<script type=\"text/javascript\">
						window.location.href='showQuiz.php?id=$id';
					</script>
				";
			}
			else
			{
				echo "
					<script type=\"text/javascript\">
						alert('กรุณาพิมพ์คำตอบก่อนครับ');
						window.location.href='showQuiz.php?id=$id';
					</script>
				";
			}
		}
		else
		{
			echo "
				<script type=\"text/javascript\">
					alert('ไม่อนุญาติให้ส่งข้อมูลซ้ำครับ');
					window.location.href='showQuiz.php?id=$id';
				</script>
			";
		}
	}
	$sql="select * from \"qaAnswer\" where \"questionID\"='$id'";
	$dbquery=pg_query($sql);
	$rows=pg_num_rows($dbquery);
	
	//$rows=pg_num_rows($dbquery);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Question And Answer :: ระะบบถามตอบ</title>
<link href="style.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
<style type="text/css">
#spanRemove {
	position:absolute;
	top:5px;
	right:0px;
}
</style>
<script type="text/javascript" src="jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="autocomplete.js"></script>
<script type="text/javascript">
function removepost(id){
	$.post('removepost.php',{id:id,type:'a'},function(data){
		if(data=='1')
		{
			window.location.href='<?php echo "showQuiz.php?id=$id"; ?>';
		}
		else
		{
			alert('ไม่สามารถลบโพสต์ได้ กรุณาแจ้งโปรแกรมเมอร์เพื่อดำเนินการแก้ไขครับ');
			window.location.href='<?php echo "showQuiz.php?id=$id"; ?>';
		}
	});
}
</script>
</head>

<body>
<div align="center">
	<div id="main">
    	<div align="center">
        	<div id="qaTitle">
            	<table border="0" cellpadding="0" cellspacing="0" width="700">
                    <tr>
                        <td width="700" height="200"><img src="images/head.png"></td>
                    </tr>
                </table>
            </div>
            <div id="line"></div>
            <form name="frm_search" action="frm_Index.php" method="post">
                <table border="0" cellpadding="0" cellspacing="0" id="tbSearch">
                    <tr>
                        <td id="tdSearchLabel" valign="middle">ค้นหา : </td>
                        <td><input type="text" maxlength="50" name="tbxSearch" id="tbxSearch" /><input type="hidden" id="inputHidden" value=""></td>
                        <td><input type="radio" name="radio" id="rdoNotAns" value="1" /> ไม่มีคำตอบ 
                        <input type="radio" name="radio" id="rdoHaveAns" value="2" /> มีคำตอบ 
                        <input type="radio" name="radio" id="rdoAll" value="3" /> ทั้งหมด </td>
                        <td id="tdSearchSubmit"><input name="btn_submit" type="submit" value="ค้นหา" /></td>
                    </tr>
                </table>
            </form>
<?php
			
            $sql="select * from \"qaQuestion\" where \"questionID\"=$id";
			$dbquery=pg_query($sql);
			while($result=pg_fetch_assoc($dbquery))
			{
			$id2=$result['questionID'];
			$q=$result['questionName'];
			$c=$result['questionContent'];
			$author=$result['questionPoster'];
			$post_date=mb_substr($result['questionPostTime'],0,10);
			$post_time=mb_substr($result['questionPostTime'],11,8);
			
			//------------------------------------------//
            echo "<ul id=\"ulShowQ\">";
            echo "<li id=\"liQuestion\"><span id=\"spanTab\">Q : $q</span></li>";
			echo "<li id=\"liAnswer1\">";
			echo "<div id=\"divContent\">$c</div>";
			echo "<div id=\"divAuthor\">";
			echo "<span class=\"author\">โดย</span>";
			echo "<span id=\"spanAuthor\" class=\"author\">$author</span>";
			echo "<span class=\"author\" id=\"spanDate\">$post_date</span>";
			echo "<span class=\"author\" id=\"spanTime\">$post_time</span>";
			echo "</div>";
			echo "</li>";
            echo "<li id=\"liAnswer2\">";
			//$sql="select * from \"qaAnswer\" where \"questionID\"='$id'";
			//$sql.=" order by \"questionID\" asc";
			//$dbquery=pg_query($sql);
			//$rows=pg_num_rows($dbquery);
			
			function page_navigator($before_p,$plus_p,$total,$total_p,$chk_page,$id,$x)
			{   
				global $e_page;
				global $querystr;
				$urlfile="showQuiz.php"; // ส่วนของไฟล์เรียกใช้งาน ด้วย ajax (ajax_dat.php)
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
					echo "<a  href='$urlfile?s_page=$pPrev&id=$id&x=$x&y=b&querystr=".$querystr."' class='naviPN'>Prev</a>";
				}
				for($i=$total_start_p;$i<$total_end_p;$i++){  
					$nClass=($chk_page==$i)?"class='selectPage'":"";
					if($e_page*$i<$total){
					echo "<a href='$urlfile?s_page=$i&id=$id&x=$x&querystr=".$querystr."' $nClass  >".intval($i+1)."</a> ";   
					}
				}		
				if($chk_page<$total_p-1){
					echo "<a href='$urlfile?s_page=$pNext&id=$id&x=$x&y=n&querystr=".$querystr."'  class='naviPN'>Next</a>";
				}
			}   
			$q="select * from \"qaAnswer\" where \"questionID\"='$id'";
			$q.=" order by \"answerID\" desc";
			$qr=pg_query($q);
			$total=pg_num_rows($qr);
			//$rows=$total;
			$e_page=10; // กำหนด จำนวนรายการที่แสดงในแต่ละหน้า   
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
				if($rows==0)
				{
					echo"<div id=\"divAnswer1\">*** ยังไม่มีคำตอบ ***";
					echo"</div>";
				}
				while($rs=pg_fetch_assoc($qr))
				{
					$ans_id = $rs['answerID'];
					$ansContent=$rs['answerContent'];
					$ansPoster=$rs['answerPoster'];
					$ansDate=$rs['answerPostTime'];
					
					$sql="select * from \"Vfuser\" where \"username\"='$ansPoster'";
					$dbquery=pg_query($sql);
					$result=pg_fetch_assoc($dbquery);
					$fullName=$result['fullname'];
					
					echo"<div id=\"divAnswerBorder\">";
					echo"<table width=\"640\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">";
					echo"<tr>";
					echo"<td rowspan=\"2\" align=\"center\" valign=\"middle\" id=\"td_NumAnswer\">ตอบโดย :<br><a title=\"$fullName\" style=\"cursor:pointer;\">$ansPoster<a></td>";
					echo"<td id=\"td_ShowPoster\" align=\"left\" valign=\"middle\">";
					echo "<h3>";
					echo "วันทีตอบคำถาม : ".substr($ansDate,0,10)." เวลา : ".substr($ansDate,11,8);
					echo "</h3>";
					if($emp_level<=1)
					{
						echo "<span class=\"author\" id=\"spanRemove\" onClick=\"removepost('$ans_id');\"></span>";
					}
					echo "</td>";
					echo"</tr>";
					echo"<tr>";
					echo"<td id=\"td_ShowAnswer\" align=\"left\" valign=\"top\">$ansContent</td>";
					echo"</tr>";
					echo"</table>";
					echo"</div>";
				}
			echo "</li>";
        	echo"</ul>";
			//------------------------------------------//
			}
			if($total>0){ ?>
            <table border="0" cellpadding="0" cellspacing="0" width="660">
            <tr>
            <td align="right" valign="middle" id="navigate">
            <div class="browse_page">
             <?php   
             // เรียกใช้งานฟังก์ชั่น สำหรับแสดงการแบ่งหน้า   
              page_navigator($before_p,$plus_p,$total,$total_p,$chk_page,$id,$x);    
              ?>
            </div>
            </td>
            </tr>
            </table>
            </div>
            
            <?php } ?>
            <form name="form_Quize" action="showQuiz.php?type=post&id=<?php echo $id; ?>" method="post">
       	  		<ul id="ulPostQuize">
                	<li id="postquiztitle">โพสต์คำตอบ</li>
              		<li>คำตอบ :</li>
                    <li id="libgContentBox">
                    	<script type="text/javascript" src="nicEdit.js"></script>
                    	<script type="text/javascript">
							bkLib.onDomLoaded(function() {
								new nicEditor({iconsPath : 'nicEditorIcons.gif'}).panelInstance('tbxContent');
							});
						</script>
                      <textarea name="tbxContent" id="tbxContent"></textarea>
          			</li>
                    <li><input type="image" src="images/save_button.png" name="btnSubmit" id="btnSubmit" /></li>
                </ul>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
	function make_autocom(autoObj,showObj){
	var mkAutoObj=autoObj;
	var mkSerValObj=showObj;
	new Autocomplete(mkAutoObj, function() {
	this.setValue = function(id) {     
	document.getElementById(mkSerValObj).value = id;
	}
	if ( this.isModified )
	this.setValue("");
	if ( this.value.length < 1 && this.isNotClick )
	return ;   
	return "questionSearchAutoComplete.php?q=" +encodeURIComponent(this.value);
	});
	}  
	// การใช้งาน
	// make_autocom(" id ของ input ตัวที่ต้องการกำหนด "," id ของ input ตัวที่ต้องการรับค่า");
	make_autocom("tbxSearch","inputHidden");
</script>
</body>
</html>
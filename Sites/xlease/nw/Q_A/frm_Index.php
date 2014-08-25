<?php
	include("../../config/config.php");
	$id_user=$_SESSION['av_iduser'];
	$sql="select * from \"Vfuser\" where \"id_user\"='$id_user'";
	$dbquery=pg_query($sql);
	
	$result=pg_fetch_assoc($dbquery);
	
	$user=$result['username'];
	$emp_level = $result['emplevel'];
	mb_internal_encoding('utf-8');
	$type=$_GET['type'];
	$searchFor=$_POST['radio'];
	//$word=$_POST['tbxSearch'];
	//$poster=$_POST['tbxPoster'];
	$question=$_POST['tbxQuestion'];
	$content=$_POST['tbxContent'];
	$date=date("Y-m-d H:i:s");
	$questiontype=$_POST['lbType'];
	
	$q=$_POST['tbxSearch'];
	$word=$q;
	
	if($type=="post")
	{
		$sql="select * from \"qaQuestion\" where \"questionName\"='$question' and \"questionPoster\"='$user' and \"questionType\"='$questiontype' and \"questionContent\"='$content'";
		$dbquery=pg_query($sql);
		$numRow=pg_num_rows($dbquery);
		if($numRow==0)
		{
			if($question!=""||$content!="")
			{
				$sql1="insert into \"qaQuestion\"(\"questionName\", \"questionPoster\", \"questionPostTime\", \"questionType\", \"questionContent\") values('$question','$user','$date','$questiontype','$content')";
				$dbquery1=pg_query($sql1);
				
				//ACTIONLOG
				$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(TAL) ทำการ POST ระบบถามตอบ', '$date')");
				//ACTIONLOG---
				echo "
					<script type=\"text/javascript\">
						window.location.href='frm_Index.php';
					</script>
				";
			}
			else
			{
				echo "
					<script type=\"text/javascript\">
						alert('กรุณาระบุหัวข้อหรือเนื้อหาของคำถามก่อนครับ');
						window.location.href='frm_Index.php';
					</script>
				";
			}
		}
		else
		{
			echo "
				<script type=\"text/javascript\">
					alert('ไม่อนุญาติให้ส่งข้อมูลซ้ำครับ');
					window.location.href='frm_Index.php';
				</script>
			";
		}
	}
	
	//$rows=pg_num_rows($dbquery);
	
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Question And Answer :: ระะบบถามตอบ</title>
<link href="style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="autocomplete.js"></script>
<link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
	setInterval(function(){
		if(newWindow.closed)
		{
			window.location.reload();
		}
	},1000);
}
function removepost(id)
{
	$.post('removepost.php',{id:id,type:'q'},function(data){
		if(data=='1')
		{
			window.location.href='frm_Index.php';
		}
		else
		{
			alert('ไม่สามารถลบโพสต์ได้ กรุณาแจ้งโปรแกรมเมอร์เพื่อดำเนินการแก้ไขครับ');
			window.location.href='frm_Index.php';
		}
	});
}
</script>
		
</head>

<body bgcolor="#000022" onLoad="test();">
<div align="center">
<div id="main">
    	<div align="center">
        	<div id="qaTitle">
            	<table border="0" cellpadding="0" cellspacing="0" width="700" >
                    <tr>
             			<td width="700" height="200"><img src="images/head.png"></td>
                       
                        
                    </tr>
                </table>
            </div>
            <div id="line"></div>
            <form name="frm_search" id="frm_search" action="frm_Index.php" method="post">
                <table border="0" cellpadding="0" cellspacing="0" id="tbSearch">
                    <tr>
                        <td id="tdSearchLabel" valign="middle">ค้นหา : </td>
                        <td><input type="text" maxlength="50" name="tbxSearch" id="tbxSearch" /><input type="hidden" id="inputHidden" value=""></td>
                        <td><input name="radio" type="radio" id="rdoNotAns" value="1" <?php if($searchFor==1){ echo "checked=\"checked\""; } ?> /> ไม่มีคำตอบ 
                        <input type="radio" name="radio" id="rdoHaveAns" value="2" <?php if($searchFor==2){ echo "checked=\"checked\""; } ?> /> มีคำตอบ 
                        <input type="radio" name="radio" id="rdoAll" value="3" <?php if($searchFor==3){ echo "checked=\"checked\""; } ?> /> ทั้งหมด </td>
                        <td id="tdSearchSubmit"><input name="btn_submit" type="submit" value="ค้นหา" /></td>
                    </tr>
                </table>
                
            </form>
           
            <?php
			
			echo "<a align=\"right\" onclick=\"javascript:popU('postQues.php?id=$id','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=720,height=620')\"><img src=\"images/postQ.png\" width=\"130\" height=\"38\" style=\"cursor:pointer;\"/></a>"; //เปิดหน้าโพสคำถาม
			
			?>
           
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
			
<!--จาก่ตรงนี้-->
            <?php   
			// สร้างฟังก์ชั่น สำหรับแสดงการแบ่งหน้า   
			function page_navigator($before_p,$plus_p,$total,$total_p,$chk_page,$word){   
				global $e_page;
				global $querystr;
				$urlfile="frm_Index.php"; // ส่วนของไฟล์เรียกใช้งาน ด้วย ajax (ajax_dat.php)
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
					echo "<a  href='$urlfile?txtSearch=".$word."&s_page=$pPrev&querystr=".$querystr."' class='naviPN'>Prev</a>";
				}
				for($i=$total_start_p;$i<$total_end_p;$i++){  
					$nClass=($chk_page==$i)?"class='selectPage'":"";
					if($e_page*$i<$total){
					echo "<a href='$urlfile?txtSearch=".$word."&s_page=$i&querystr=".$querystr."' $nClass  >".intval($i+1)."</a> ";   
					}
				}		
				if($chk_page<$total_p-1){
					echo "<a href='$urlfile?txtSearch=".$word."&s_page=$pNext&querystr=".$querystr."'  class='naviPN'>Next</a>";
				}
			}   
			?>
			<?php
			//////////////////////////////////////// เริ่มต้น ส่วนเนื้อหาที่จะนำไปใช้ในไฟล์ ที่เรียกใช้ด้วย ajax
			?>
			
			<?php
			if($searchFor==1)
			{
				$q="select * from \"qaQuestion\" where \"questionID\" not in (select cast(\"questionID\" as integer) from \"qaAnswer\") and (\"qaQuestion\".\"questionName\" like '%$word%' or \"qaQuestion\".\"questionPoster\" like '%$word%' or \"qaQuestion\".\"questionType\" like '%$word%' or \"qaQuestion\".\"questionContent\" like '%$word%')";
			}
			else if($searchFor==2)
			{
				$q="select * from \"qaQuestion\" where \"questionID\" in (select cast(\"questionID\" as integer) from \"qaAnswer\") and (\"qaQuestion\".\"questionName\" like '%$word%' or \"qaQuestion\".\"questionPoster\" like '%$word%' or \"qaQuestion\".\"questionType\" like '%$word%' or \"qaQuestion\".\"questionContent\" like '%$word%')";
			}
			else//if($searchFor==3)
			{
				$q="select * from \"qaQuestion\" where \"questionName\" like '%$word%' or \"questionPoster\" like '%$word%' or \"questionType\" like '%$word%' or \"questionContent\" like '%$word%'";	
			}
			//else
			//{
				//if($word=="")
				//{
					//$q="select * from \"qaQuestion\"";
				//}
				//else
				//{
					//$q="select * from \"qaQuestion\" where \"questionID\"='$word'";
				//}
			//}
			$q.=" order by \"questionID\" desc";
			$qr=pg_query($q);
			$total=pg_num_rows($qr);
			$e_page= 50; // กำหนด จำนวนรายการที่แสดงในแต่ละหน้า   
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
			?>
			<?php
			while($rs=pg_fetch_assoc($qr)){
				$id=$rs['questionID'];
				if(mb_strlen($rs['questionName'])>60)
				{
					$q=mb_substr($rs['questionName'],0,60,'utf8')."...";
				}
				else
				{
					$q=$rs['questionName'];
				}
				if(mb_strlen($rs['questionContent'])>170)
				{
					$c=mb_substr($rs['questionContent'],0,170,'utf8')."...";
				}
				else
				{
					$c=$rs['questionContent'];
				}
				$author=$rs['questionPoster'];
				$post_date=mb_substr($rs['questionPostTime'],0,10);
				$post_time=mb_substr($rs['questionPostTime'],11,8);
				$sql1="select * from \"qaAnswer\" where \"questionID\"='$id'";
				$dbquery1=pg_query($sql1);
				$rows1=pg_num_rows($dbquery1);
					
				
				echo "<ul id=\"ulShowQ\">";
				echo "<li id=\"liQuestion\">";
				
				//echo "<div id=\"ansNum\">ตอบ : $rows1</div>";
	
				if ($rows1 > 0)
					{
						echo "<div id=\"ansNum\">ตอบ : $rows1</div>";
					}
					else
					{
						echo "<div id=\"ansNum\" style=\"color:red;\">ตอบ : $rows1</div>";
					}
				
				echo "<div id=\"divTitle\">Q : $q</div>";
				
				echo "<a onclick=\"javascript:popU('showQuiz.php?id=$id','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=750,height=700')\"><img src=\"images/more.png\" width=\"36\" height=\"19\" style=\"cursor:pointer;\" /></a>"; //เปิดหน้าตอบคำถาม
				echo "<div id=\"divAuthor\">";
				
				echo "<span class=\"author\">โดย</span>";
				echo "<span id=\"spanAuthor\" class=\"author\">$author</span>";
				echo "<span class=\"author\" id=\"spanDate\">$post_date</span>";
				echo "<span class=\"author\" id=\"spanTime\">$post_time</span>";
				
				if($emp_level<=1)
				{
					echo "<span class=\"author\" id=\"spanRemove\" onClick=\"removepost('$id');\"></span>";
				}
				
				echo "</div>";
				
				//echo "</li>";
				//echo "<li id=\"liAnswer\"><span id=\"answerLabel\">Detail : </span>$c</li>";
				/*echo "<li id=\"liMore\"><a onclick=\"javascript:popU('showQuiz.php?id=$id','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=750,height=700')\"><img src=\"images/more.png\" width=\"36\" height=\"19\" style=\"cursor:pointer;\" /></a></li>";*/
				
				echo"</ul>";
				
			}
			?>
            <?php if($total>0){ ?>
            <table border="0" cellpadding="0" cellspacing="0" width="660">
            <tr>
            <td align="right" valign="middle" id="navigate">
            <div class="browse_page">
             <?php   
             // เรียกใช้งานฟังก์ชั่น สำหรับแสดงการแบ่งหน้า   
              page_navigator($before_p,$plus_p,$total,$total_p,$chk_page,$word);    
              ?>
            </div>
            </td>
            </tr>
            </table>
            </div>
            <?php } ?>
            
<!--ถึงตรงนี้ = ย้ายโพสคำถามไปหน้าใหม่--> 
		
 </div>
</div>
</div>
</body>
</html>
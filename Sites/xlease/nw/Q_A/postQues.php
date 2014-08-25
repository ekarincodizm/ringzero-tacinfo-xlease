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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- TemplateBeginEditable name="doctitle" -->
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


<!-- TemplateEndEditable -->
<!-- TemplateBeginEditable name="head" -->
<!-- TemplateEndEditable -->
</head>

<body>
<div align="center">
	<div id="main">
    	<div align="center">
        	<div id="qaTitle">
            	<table border="0" cellpadding="0" cellspacing="0" width="663">
                    <tr>
                        <td width="700" height="200"><img src="images/head.png"></td>
                    </tr>
                </table>
              </div>
            
<!--เริ่มหน้าโพสต์คำถาม-->          
<form name="form_Quize" action="frm_Index.php?type=post" method="post">
       	  		<ul id="ulPostQuize">
                	<li id="postquiztitle">โพสต์คำถาม</li>
                    <li>คำถาม :</li>
                    <li><input type="text" name="tbxQuestion" id="tbxQuestion" maxlength="150" /></li>
              		<li>รายละเอียด :</li>
                    <li id="libgContentBox">
                   	  <script type="text/javascript" src="nicEdit.js"></script>
           	      <script type="text/javascript">
							bkLib.onDomLoaded(function() {
								new nicEditor({iconsPath : 'nicEditorIcons.gif'}).panelInstance('tbxContent');
							});
						</script>
                    	<textarea name="tbxContent" id="tbxContent"></textarea>
          			</li>
                    <li>ประเภท :</li>
                    <li>
                    	<select name="lbType" id="lbType">
                    <?php
						$sql2="select * from \"qaType\"";
						$dbquery2=pg_query($sql2);
						while($result2=pg_fetch_assoc($dbquery2))
						{
							$value=$result2['qaTypeID'];
							$typeName=$result2['qaTypeName'];
							echo "<option value=\"$value\">$typeName</option>";
						}
					?>
                    	</select>
                    </li>
                    <li><input type="image" src="images/save_button.png" name="btnSubmit" id="btnSubmit" /></li>
                </ul>
            </form>
</body>
</html>

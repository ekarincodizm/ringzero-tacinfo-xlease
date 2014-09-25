<?php
session_start();
$_SESSION["av_iduser"];
$idno=pg_escape_string($_POST["idno_names"]);
include("../config/config.php");
?>

<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />

<?php
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}
if($_SESSION["session_company_code"]=="AVL")
{
 $file_namepic="logo_av.jpg";
}
else
{
 $file_namepic="logo_thaiace.jpg";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>แก้ไขผู้ใช้งาน</title>
<script type="text/javascript" src="autocomplete.js"></script>
<link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
<script type="text/javascript">
<!--
var xmlHttp;

function createXMLHttpRequest() {
    if (window.ActiveXObject) {
        xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
    } 
    else if (window.XMLHttpRequest) {
        xmlHttp = new XMLHttpRequest();
    }
}
function checkemail(str){
	if(document.getElementById("email").value!=""){
		var emailFilter=/^.+@.+\..{2,3}$/;
		var str=document.getElementById("email").value;
		if (!(emailFilter.test(str))) { 
			   alert ("ท่านใส่อีเมล์ไม่ถูกต้อง");
			   document.getElementById("email").select();
			   return false;
		}else{
			//update_detail();
			update_fuser.submit();
		}
	}else{
		//update_detail();
		update_fuser.submit();
	}
}

/*function update_detail() 
 {
   createXMLHttpRequest();
            
			var s_title = document.getElementById("a_title").value;
			var s_fname = document.getElementById("a_fname").value;
			var s_lname = document.getElementById("a_lname").value;
			var s_username = document.getElementById("a_username").value;

			var s_gp = document.getElementById("a_gp").value;
			var s_fd = document.getElementById("a_fd").value;
			var s_office= document.getElementById("a_office").value;
			var s_status = document.getElementById("a_status").value;
			var s_id = document.getElementById("a_id").value;
			var s_email = document.getElementById("email").value;
			
            xmlHttp.open("get", "update_user.php?f_title="+s_title+"&f_fullname="+s_fname+"&f_lname="+s_lname+"&f_username="+s_username+"&f_gp="+s_gp+"&f_fd="+s_fd+"&f_office="+s_office+"&f_status="+s_status+"&id="+s_id+"&f_email="+s_email,true);
			 
		
											   
            xmlHttp.onreadystatechange = function () {
                if (xmlHttp.readyState == 4) {
                    if (xmlHttp.status == 200) {
						update_fuser.submit();
						
                        displayInfo(xmlHttp.responseText);						
                    } else {
                        displayInfo("พบข้อผิดพลาด: " + xmlHttp.statusText); 
                    }
                }
				    
            };
            xmlHttp.send(null);
        }*/
        
        function displayInfo() {
            document.getElementById("divInfo").innerHTML = xmlHttp.responseText;
			 
        }

function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>

<style type="text/css">
    #warppage
	{
	width:800px;
	margin-left:auto;
	margin-right:auto;
	
	min-height: 5em;
	background: rgb(240, 240, 240);
	padding: 5px;
	border: rgb(128, 128, 128) solid 0.5px;
	border-radius: .625em;
	-moz-border-radius: .625em;
	-webkit-border-radius: .625em;
	}
.style1 {
	font-size: small;
	font-weight: bold;
}
.style2 {
	font-size: medium;
	font-weight: bold;
}
</style>
</head>

<body>
<div id="swarp" style="width:950px; height:auto; margin-left:auto; margin-right:auto;">
<div class="style2" id="super_head" style="padding-left:10px; height:90px; width:800px;"><span class="style2" style="padding-left:10px; height:60px; width:800px; "><div style="width:90px; float:left;"><img src="../images/<?php echo $file_namepic; ?>" width="80" height="80" /></div><div style="padding-top:20px;"><span><?php echo $_SESSION["session_company_name"]; ?></span><br /><?php echo $_SESSION["session_company_thainame"]; ?></div></div>
<div id="warppage" style="width:1050px; height:auto;">
<div id="headerpage" style="height:10px; text-align:center"></div>
<div class="style1" id="menu" style="height:30px; padding-left:10px; padding-top:10px; padding-right:10px;"><a href="user_manage.php">จัดการผู้ใช้</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;<a href="menu_manage.php">จัดการเมนู</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;<a href="" onclick="window.close();">x ปิดหน้านี้</a>
  <hr /></div>
<div id="contentpage" style="height:auto; padding-left:10px; padding-right:10px;">
   <?php
  $piduser=pg_escape_string($_GET["iduser"]);
  
  $qry_user=pg_query("select A.*,B.*,B.dep_id AS iddg,B.dep_name AS namegr from fuser A 
                      LEFT OUTER JOIN department B on A.user_group=B.dep_id
					  LEFT OUTER JOIN f_department C on A.user_dep=C.fdep_id
					  where A.id_user='$piduser' ");
  
  $resu=pg_fetch_array($qry_user);
  
    $s_office=$resu["office_id"];
	$title=trim($resu["title"]);
	$fname=trim($resu["fname"]);
	$lname=trim($resu["lname"]);
	$full_name=$title.$fname." ".$lname;
	$us_name=$resu["username"];
	$a_fd=$resu["user_dep"];
	$email=$resu["email"];
   
   if($resu["status_user"]=='t')
   {
   
      $res_sta="<select name=\"a_status\" id=\"a_status\">
	   <option value=\"1\">ใช้งาน</option>
	   <option value=\"0\">ระังับใช้งาน</option>
	</select>";
   
   }
   else
   {
       $res_sta="<select name=\"a_status\" id=\"a_status\">
	   <option value=\"0\">ระังับใช้งาน</option>
	   <option value=\"1\">ใช้งาน</option>
	</select>";
   
   }

 
  
  
  ?>
  <button onclick="MM_openBrWindow('chpwd.php?iduser=<?php echo $piduser;?>&fullname=<?php echo $full_name; ?>&uname=<?php echo $us_name; ?>','','width=600,height=300')" >Reset Password</button>
  <br /><br />
  <form name="update_fuser" action="update_user.php" method="post" enctype="multipart/form-data">
  <table width="100%" border="0" style="background-color:#EEF2DB;" cellspacing="1" >
  <tr style="background-color:#D0DCA0;">
    <td>username</td>
    <td>คำนำหน้า</td>
	<td>ชื่อ</td>
	<td>นามสกุล</td>
    <td>กลุ่มผู้ใช้</td>
	<td>ฝ่าย</td>
    <td>office</td>
    <td>status</td>
	<td>ใช้งานระบบ</td>
  </tr>
  <tr style="background-color:#EEF2DB;"><input type="hidden" name="a_id" id="a_id" value="<?php echo $piduser; ?>"  />
    <td><input type="text" name="a_username" id="a_username" value="<?php echo trim($resu["username"]); ?>" size="10"/></td>
    <td><input type="text" name="a_title" id="a_title" value="<?php echo $title;?>" size="10" /></td>
	<td><input type="text" name="a_fname" id="a_fname" value="<?php echo $fname;?>" size="20" /></td>
	<td><input type="text" name="a_lname" id="a_lname" value="<?php echo $lname;?>" size="20" /></td>
    <td>
	<select name="a_gp" id="a_gp">
	<option value="<?php echo $resu["iddg"]; ?>"><?php echo $resu["namegr"]; ?></option>
	<?php
	$qry_gpuser=pg_query("select * from department");
	while($resg=pg_fetch_array($qry_gpuser))
	 {
	?>
	  <option value="<?php echo $resg["dep_id"]; ?>"><?php echo $resg["dep_name"]; ?></option>
	<?php
	 }
	?>  
	</select></td>
		 <td>
	<select name="a_fd" id="a_fd" value="<?php echo $a_fd?>">
	<option value="" >---เลือก---</option>
	<?php
	$qry_dep=pg_query("select * from f_department where fstatus='TRUE' order by fdep_id");
	while($resd=pg_fetch_array($qry_dep))
	 {
	?>
	  <option value="<?php echo $resd["fdep_id"]; ?>" <?php if($a_fd==$resd["fdep_id"]){ echo "selected"; }?>><?php echo $resd["fdep_name"]; ?></option>
	<?php
	 }
	?>  
	 </td>
     <td>
	<select name="a_ofiice" id="a_office">
	 <option value="<?php echo $resu["office_id"]; ?>"><?php echo $s_office; ?></option>
	<option value="<?php echo $_SESSION["session_company_nv"]; ?>">NV [<?php echo $_SESSION["session_company_nv"]; ?>]</option>
	<option value="<?php echo $_SESSION["session_company_jr"]; ?>">JR[<?php echo $_SESSION["session_company_jr"]; ?>]</option>
	<option value="<?php echo $_SESSION["session_company_tv"]; ?>">TV[<?php echo $_SESSION["session_company_tv"]; ?>]</option>
		</select>	</td>
    
	<td>
		<?php
		echo $res_sta;
		?>
	</td>
	<td>
		<select name="u_system" id="u_system">
			<option value="0" <?php if($resu["isUserTA"] == "0"){echo "selected";} ?> >XLEASE เท่านั้น</option>
			<option value="1" <?php if($resu["isUserTA"] == "1"){echo "selected";} ?> >XLEASE และ TA</option>
		</select>
	</td>
  </tr>
  <tr>
	<td colspan="8">E-mail : <input type="text" name="email" id="email" value="<?php echo $email;?>" size="30" /></td>
  </tr>
 
    <tr>	
  <td  colspan="4"  valign="top" >ไฟล์สแกนลายเซ็น :<input type="file" name="file0" id="file0" ></td>
   <td  colspan="5">
	<?php 
	if($idno ==""){$idno_s=$piduser;}
	else{$idno_s=$idno;}
	
	$qry_fuser_detailaudit = pg_query("select \"u_sign\" from  \"fuser_detail\" 
    where \"id_user\" =  '$idno_s' ");
 
	$pathu_sign = pg_fetch_result($qry_fuser_detailaudit,0);
	if($pathu_sign !=""){
	$pathu_sign='../nw/upload/sign/'.$pathu_sign;?>
	<img src="<?php echo $pathu_sign;?>" width=150,height=50>
	<?php }?>
  </td>
  </tr>
  </form>
  <tr>
    <td colspan="5"><input type="button" value="SAVE" onclick="return checkemail()"  /><div id="divInfo"></div></td>
  </tr>
</table>
 <?php
  $qry_menu=pg_query("select A.*,B.* from  f_usermenu A 
                      LEFT OUTER JOIN f_menu B on A.id_menu=B.id_menu
					  where A.id_user='$piduser' order by B.name_menu ");
 ?>
 <form method="post" action="update_menu_user.php" >
 <input type="hidden" name="s_id" value="<?php echo $piduser; ?>"  />
 <table width="100%" border="0" style="background-color:#D5EAC8;">
  <tr style="background-color:#A8D38D;">
    <td colspan="2">รายการที่ใช้งาน</td>
    <td>สถานะ</td>
  </tr>
  <?php
  while($resmenu=pg_fetch_array($qry_menu))
  {
   $stas=$resmenu['status'];
  if($stas=='t')
  {
   $se_sta="<select name=\"a_st[]\">
	   <option value=\"TRUE\">ใช้งาน</option>
	   <option value=\"FALSE\">ระังับใช้งาน</option>
	</select>";
  }
  else
  {
    $se_sta="<select name=\"a_st[]\">
	   <option value=\"FALSE\">ระังับใช้งาน</option>
	   <option value=\"TRUE\">ใช้งาน</option>
	</select>";
  } 
  $a++;
  
   
  ?>
  <tr>
    
    <input type="hidden" name="i_menu[]" value="<?php echo $resmenu["id_menu"]; ?>" />
	<td width="85"><?php echo $resmenu["id_menu"]; ?></td>
    <td width="545"><?php echo $resmenu["name_menu"]; ?></td>
    <td width="126">
	<?php
    	echo $se_sta;
	?>	</td>
  </tr>
  <?php
  } 
  ?>
  <tr style="background-color:#CAF9D9;">
    <td colspan="3"><div align="center">
      <input name="submit" type="submit" value="UPDATE"  />
    </div></td>
    </tr>
    <tr  style="background-color:#A8D38D;">
    <td width="85"><input type="button" value="เพิ่มรายการ" onclick="parent.location='frm_addusermenu.php?uid=<?php echo $piduser ;?>'"/></td>
    <td width="545">&nbsp;</td>
    <td width="126"><input type="button" value="BACK" onclick="javascript:history.back();" /></td>
  </tr>
</table>
</form>
</div>
<div id="footerpage"></div>
</div>
</div>
</div>
</body>
</html>

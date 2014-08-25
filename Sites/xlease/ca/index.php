<?php
session_start();
include("../config/config.php");
$id_p_user=pg_escape_string($_GET["id_post_user"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title><?php echo $_SESSION["session_company_name"]; ?> co.,ltd</title>
<!-- InstanceEndEditable -->
<style type="text/css">
<!--
.style1 {
	font-family: Tahoma;
	font-size: medium;
}
.style3 {
    font-family: Tahoma;
	color: #ffffff;
	font-weight: bold;
	font-size: medium;
}
.style4 {
    font-family: Tahoma;
	color: #000000;
  }
  .style5 {
    font-family: Tahoma;
	color: #000000;
	font-size: medium;
  }

-->
</style>
<!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable -->
</head>

<body style="background-color:#ffffff; margin-top:0px;" onload="setfocus();">

<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
<h1 class="style4"> <?php echo $_SESSION["session_company_name"]; ?></h1>
</div>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  <div class="style3" style="background-color:#333333; width:auto; height:20px; padding-left:10px;">user : 
  <?php
   $res_profile=pg_query("select fullname,id_user from \"Vfuser\" where id_user='$id_p_user'");
   $res_userprofile=pg_fetch_array($res_profile);
   echo $res_userprofile["fullname"];
   
  ?>  
  </div>
  <div class="style3" style="background-color:#000000; width:auto; height:20px; padding-left:10px;">
  รายการที่สามารถใช้งานได้
  </div>
  <div class="style5" style=" background-color:#CCCCCC; width:auto; height:100px; padding-left:10px;">
  <table width="788" border="0">
 
 

  <?php
		  $res_menu=pg_query("select A.*,B.* from f_usermenu A inner join f_menu B on A.id_menu=B.id_menu where A.status='1' AND  
		                       A.id_user='$id_p_user'");
		  while($res_usermenu=pg_fetch_array($res_menu))
		  {
  ?>		 
   <tr>
    <td width="450"><a href="<?php echo $res_usermenu["path_menu"]; ?>"><?php echo $res_usermenu["name_menu"]; ?></a></td>
    <td width="209"><?php echo $res_usermenu["id_menu"]; ?></td>
    <td width="107"><?php echo $res_usermenu["status"]; ?></td>
  </tr> 
  <?php		
		  }
  ?>
  </table>
  </div>
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>

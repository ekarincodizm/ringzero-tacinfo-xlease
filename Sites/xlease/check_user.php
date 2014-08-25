<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>AV. leasing co.,ltd</title>
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

<body style="background-color:#ffffff; margin-top:0px;">
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
<h1 class="style4"> AV.LEASING</h1>
</div>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">


<?php

//echo $u_name." ".$pwd;
//global $full_name,$c_username,$gp_user,$office_id,$user_name,$name,$user_gp;
require("config/config.php");
require("config/function_av.php");

//av_pwd
//av_user

if(isset($chk_pwd) and (isset($chk_user)))
{
 echo "มี";
}
else
{
 //echo "ไม่มี";
 $chk_user=pg_escape_string($_POST["av_user"]);
 $seed = $_SESSION["session_company_seed"];
 $chk_pwd = md5(md5($_POST['av_pwd']).$seed);

 $result=list_User("fuser","where username='$chk_user' and password='$chk_pwd' AND status_user=true");
 
 
 if(empty($result["username"]))
 {
    echo "Sorry , plase log in again";
    echo "<meta http-equiv=\"refresh\" content=\"1;URL=index.php\">"."<br>";
 }
 else
 {
  
   $av_iduser=$result["id_user"];
   
   $av_gpuser=$result["user_group"];
   
   $av_officeid=$result["office_id"];
   
   session_register("av_iduser");
   session_register("av_gpuser");
   session_register("av_officeid"); 
  
   $_SESSION["av_iduser"]=$av_iduser;
   $_SESSION["av_gpuser"]=$av_gpuser;
   $_SESSION["av_officeid"]=$av_officeid;
   
 /*
    if($av_gpuser=="CA")
	{
	 #echo "Group  Cashier";
	 echo "<meta http-equiv=\"refresh\" content=\"0;URL=ca/index.php?id_post_user=$av_iduser\">";
    }
   if($av_gpuser=="PS")
    {
	 #echo "Group  Post CA";
	 echo "<meta http-equiv=\"refresh\" content=\"0;URL=post/index.php?id_post_user=$av_iduser\">";
    }
	if($av_gpuser=="AD")
	{
	 #echo "Group  Administrator";
	 echo "<meta http-equiv=\"refresh\" content=\"0;URL=admin/index.php?id_post_user=$av_iduser\">";
	}
	if($av_gpuser=="TRF")
	{
	 #echo "Group  Administrator";
	 echo "<meta http-equiv=\"refresh\" content=\"0;URL=tr/index.php?id_post_user=$av_iduser\">";
	}
   */
  ?> 
  <div class="style3" style="background-color:#333333; width:auto; height:20px; padding-left:10px;">user : 
  <?php
   $id_p_user=$_SESSION["av_iduser"];
   $res_profile=pg_query("select fullname,id_user from \"Vfuser\" where id_user='$id_p_user'");
   $res_userprofile=pg_fetch_array($res_profile);
   echo $res_userprofile["fullname"]."office =".$_SESSION["av_officeid"];
   
  ?>  
  </div>
  <div class="style3" style="background-color:#000000; width:auto; height:20px; padding-left:10px; text-align:justify;">
  รายการที่สามารถใช้งานได้
  </div>
  <div class="style5" style=" background-color:#FFFFFF; width:auto; height:100px; padding-left:10px;">
  <table width="788" border="0">
 
 

  <?php
		  $res_menu=pg_query("select A.*,B.* from f_usermenu A inner join f_menu B on A.id_menu=B.id_menu where A.status='1' AND  
		                       A.id_user='$av_iduser'");
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
   
     
<?php
  }  
 }
 
?>
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
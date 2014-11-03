<?php
session_start();
$_SESSION["av_iduser"];
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
<title>จัดการผู้ใช้</title>
<script type="text/javascript" src="autocomplete.js"></script>
<link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
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
<div id="swarp" style="width:800px; height:auto; margin-left:auto; margin-right:auto;">
<div class="style2" id="super_head" style="padding-left:10px; height:90px; width:800px;"><span class="style2" style="padding-left:10px; height:60px; width:800px; "><div style="width:90px; float:left;"><img src="../images/<?php echo $file_namepic; ?>" width="80" height="80" /></div><div style="padding-top:20px;"><span><?php echo $_SESSION["session_company_name"]; ?></span><br /><?php echo $_SESSION["session_company_thainame"]; ?></div></div>
<div id="warppage" style="width:800px; height:auto;">
<div id="headerpage" style="height:10px; text-align:center"></div>
<div class="style1" id="menu" style="height:30px; padding-left:10px; padding-top:10px; padding-right:10px;"><a href="user_manage.php">จัดการผู้ใช้</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;<a href="menu_manage.php">จัดการเมนู</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;<a href="" onclick="window.close();">x ปิดหน้านี้</a>
  <hr /></div>
<div id="contentpage" style="height:auto;">
 
 <div class="style5" style="width:auto;  padding-left:10px;">
  <?php
  $qry_user=pg_query("	select 
  								\"id_user\",\"username\",\"fullname\",\"user_group\",\"office_id\",\"status_user\" 
  						from 
  								\"Vfuser\" 
  						order by 
  								user_group,status_user desc");
  
   ?>
  <table width="778" border="0" style="background-color:#EEEDCC;">
  <tr>
    <td colspan="4" style="text-align:center;"><input type="button" onclick="javascript:history.back();" value="BACK" /></td>
    <td colspan="5" style="text-align:center;"><input type="button" value="เพิ่มผู้ใช้" onclick="parent.location='add_user.php'" /></td>
    </tr>
  <tr style="background-color:#D0DCA0">
    <td width="26">No.</td>
    <td width="84">ID</td>
    <td width="130">username</td>
    <td width="239">ชื่อ - นามสกุล </td>
	<td>ชื่อเล่น</td>
    <td width="63">กลุ่มผู้ใช้</td>
    <td width="56">office</td>
    <td width="102">status</td>
    <td width="44">&nbsp;</td>
  </tr>
  <?php
  $a=0;
  while($res=pg_fetch_array($qry_user))
  {
   $a++;
   //หาชื่อเล่น 
   $qrynickname=pg_query("select \"nickname\" from \"fuser_detail\" where \"id_user\"='$res[id_user]'");
   list($nickname)=pg_fetch_array($qrynickname);
  ?>
  <tr style="background-color:#EEF2DB">
    <td><?php echo $a; ?></td>
    <td><?php echo $res["id_user"]; ?></td>
    <td><?php echo $res["username"]; ?></td>
    <td><?php echo $res["fullname"]; ?></td>
	<td><?php echo $nickname; ?></td>
    <td><?php echo $res["user_group"]; ?></td>
    <td><?php echo $res["office_id"]; ?></td>
    <td><?php 
    	 // print_r($res);
	     if($res["status_user"]=='t')
		 {
		   echo "ใช้งาน";
		 }
		 else
		 {
		   echo "ระงับใช้งาน";
		 } 
	    ?>    </td>
    <td><a href="detail_user.php?iduser=<?php echo $res["id_user"]; ?>">แก้ไข</a></td>
  </tr>
  <?php
  }
  ?>
  
</table>

 
 

</div>
<div id="footerpage"></div>
</div>
</div>
</div>
</body>
</html>

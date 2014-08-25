<?php
session_start();
$_SESSION["av_iduser"];
$u_id=pg_escape_string($_GET["uid"]);
include("../config/config.php");
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
<title>เพิ่มรายการใช้งาน</title>
<script type="text/javascript" src="autocomplete.js"></script>
<link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
<script type="text/javascript">
    var xmlHttp;

function createXMLHttpRequest() {
    if (window.ActiveXObject) {
        xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
    } 
    else if (window.XMLHttpRequest) {
        xmlHttp = new XMLHttpRequest();
    }
}
 
 function save_menu() 
 {
   createXMLHttpRequest();
            
			var s_idmenu = document.getElementById("ad_idmenu").value;
			var s_status = document.getElementById("ad_status").value;
			var s_uid =document.getElementById("u_id").value;
			
            xmlHttp.open("get", "save_usermenu.php?f_idmenu="+s_idmenu+"&f_status="+s_status+"&f_uid="+s_uid,true);
			 
		
											   
            xmlHttp.onreadystatechange = function () {
                if (xmlHttp.readyState == 4) {
                    if (xmlHttp.status == 200) {
                        displayInfo(xmlHttp.responseText);
                    } else {
                        displayInfo("พบข้อผิดพลาด: " + xmlHttp.statusText); 
                    }
                }
				    
            };
            xmlHttp.send(null);
        }
        
        function displayInfo() {
            document.getElementById("divInfo").innerHTML = xmlHttp.responseText;
			setTimeout("location.reload(true);",1500); 
        }
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
<div id="swarp" style="width:800px; height:auto; margin-left:auto; margin-right:auto;">
<div class="style2" id="super_head" style="padding-left:10px; height:90px; width:800px;"><span class="style2" style="padding-left:10px; height:60px; width:800px; "><div style="width:90px; float:left;"><img src="../images/<?php echo $file_namepic; ?>" width="80" height="80" /></div><div style="padding-top:20px;"><span><?php echo $_SESSION["session_company_name"]; ?></span><br /><?php echo $_SESSION["session_company_thainame"]; ?></div></div>
<div id="warppage" style="width:800px; height:auto;">
<div id="headerpage" style="height:10px; text-align:center"></div>
<div class="style1" id="menu" style="height:30px; padding-left:10px; padding-top:10px; padding-right:10px;"><a href="user_manage.php">จัดการผู้ใช้</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;<a href="menu_manage.php">จัดการเมนู</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;<a href="" onclick="window.close();">x ปิดหน้านี้</a>
  <hr /></div>
<div id="contentpage" style="height:auto; padding-left:10px; padding-right:10px;">
 
  <table width="750" border="0" style="background-color:#EEEDCC">
  <?php 
   $list_menu=pg_query("select * from f_usermenu A LEFT OUTER JOIN f_menu B 
                                 on A.id_menu=B.id_menu
								 where A.id_user='$u_id' order by B.name_menu ");
   $res_cc=pg_num_rows($list_menu);
   if($res_cc==0)
   {
   ?>
   <tr style="background-color:#E3E7AB">
    <td colspan="3">ยังไม่มีรายการใช้งาน</td>
    </tr>
  <tr>
   <?php
   }
   else
   {
   
   
  ?>
  <tr style="background-color:#EEF2DB">
    <td colspan="3">รายการใช้งานปัจจุบัน</td>
    </tr>
	 <tr style="background-color:#D0DCA0">
    <td>id menu </td>
    <td>name menu </td>
    <td>สถานะ</td>
	 </tr>
  <?php
    while($res_m=pg_fetch_array($list_menu))
	{
  ?>
  <tr style="background-color:#E4E4E4;">
    <td width="164"><?php echo $res_m["id_menu"]; ?></td>
    <td width="207"><?php echo $res_m["name_menu"]; ?>	</td>
    <td><?php 
	                     if($res_m["status"]=='t')
	                     {
						   echo "ใช้งานได้";
						 }
						 else
						 {
						   echo "ระงับการใช้งาน";
						 }
						 ?></td>
    </tr>
  <?php
     }
  ?>	
  <tr>
    <td colspan="3">&nbsp;</td>
    </tr>
 
  <?php
   }
  ?>
   <tr style="background-color:#FFFF99;">
    <td colspan="2">เลือกรายการ</td>
    <td>สถานะ</td>
    </tr>
  <?php
   
   $qry_menu=pg_query("select * from f_menu 
	where \"id_menu\" not in(select \"id_menu\" from f_usermenu where id_user='$u_id')
	order by name_menu");
   
  ?>
  <tr>
    <input type="hidden" id="u_id" name="u_id" value="<?php echo $u_id; ?>"  /> 
    <td colspan="2" style="background-color:#FFFF99;"><select name="ad_idmenu" id="ad_idmenu">
        <?php
	 while($res=pg_fetch_array($qry_menu))
	 {
	?>
        <option value="<?php echo trim($res["id_menu"]); ?>"><?php echo trim($res["name_menu"]); ?></option>
        <?php
	 }
	?>
      </select>	</td>
    <td style="background-color:#FFFF99;"><select name="ad_status" id="ad_status">
	   <option value="1">ใช้งาน</option>
	   <option value="0">ระงับการใช้งาน</option>
	   </select></td>
    </tr>
  <tr>
    <td><input name="button" type="button" value="SAVE" onclick="save_menu()"/></td>
    <td><div id="divInfo"></div></td>
    <td><input type="button" value="BACK" onclick="javascript:history.back();"  /></td>
  </tr>
</table>
</div>
<div id="footerpage"></div>
</div>
</div>
</div>
</body>
</html>

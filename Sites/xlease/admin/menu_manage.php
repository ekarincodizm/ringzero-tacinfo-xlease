<?php
session_start();
$_SESSION["av_iduser"];
$idno=pg_escape_string($_POST["idno_names"]);
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
<title>จัดการเมนู</title>
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
	
		function startRequest_Lock() {
		    createXMLHttpRequest();
            var sText = document.getElementById("var_lockidno").value;
            xmlHttp.open("get", "lock_idno.php?stalock=0&idnoget=" + sText, true);
            xmlHttp.onreadystatechange = function () {
                if (xmlHttp.readyState == 4) {
                    if (xmlHttp.status == 200) {
                        displayInfo_lock(xmlHttp.responseText);
                    } else {
                        displayInfo_lock("พบข้อผิดพลาด: " + xmlHttp.statusText); 
                    }
                }
				    
            };
            xmlHttp.send(null);
        }
		
		function startRequest_nuLock() {
		    createXMLHttpRequest();
            var sText = document.getElementById("var_lockidno").value;
            xmlHttp.open("get", "lock_idno.php?stalock=1&idnoget=" + sText, true);
            xmlHttp.onreadystatechange = function () {
                if (xmlHttp.readyState == 4) {
                    if (xmlHttp.status == 200) {
                        displayInfo_lock(xmlHttp.responseText);
                    } else {
                        displayInfo_lock("พบข้อผิดพลาด: " + xmlHttp.statusText); 
                    }
                }
				    
            };
            xmlHttp.send(null);
        }
		
        
        function displayInfo_lock() {
            document.getElementById("divInfo_lock").innerHTML = xmlHttp.responseText;
			 
        }
		
		function startRequest_acc() {
		    createXMLHttpRequest();
            var acc_Text = document.getElementById("var_lockidno").value;
            xmlHttp.open("get", "create_accpayment.php?idno_acc=" + acc_Text, true);
            xmlHttp.onreadystatechange = function () {
                if (xmlHttp.readyState == 4) {
                    if (xmlHttp.status == 200) {
                        displayInfo_acc(xmlHttp.responseText);
                    } else {
                        displayInfo_acc("พบข้อผิดพลาด: " + xmlHttp.statusText); 
                    }
                }
				    
            };
            xmlHttp.send(null);
        }
        
        function displayInfo_acc() {
            document.getElementById("divInfo_acc").innerHTML = xmlHttp.responseText;
			 
        }
		
			function startRequest_ccc() {
		    createXMLHttpRequest();
            var ccc_Text = document.getElementById("var_lockidno").value;
            xmlHttp.open("get", "create_cuspayment.php?idno_ccc=" + ccc_Text, true);
            xmlHttp.onreadystatechange = function () {
                if (xmlHttp.readyState == 4) {
                    if (xmlHttp.status == 200) {
                        displayInfo_ccc(xmlHttp.responseText);
						
                    } else {
                        displayInfo_ccc("พบข้อผิดพลาด: " + xmlHttp.statusText); 
                    }
                }
				    
            };
            xmlHttp.send(null);
        }
        
        function displayInfo_ccc() {
		    
            document.getElementById("divInfo_ccc").innerHTML = xmlHttp.responseText;
			
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
<div id="swarp" style="width:1000px; height:auto; margin-left:auto; margin-right:auto;">
<div class="style2" id="super_head" style="padding-left:10px; height:90px; width:1000px;"><span class="style2" style="padding-left:10px; height:60px; width:800px; "><div style="width:90px; float:left;"><img src="../images/<?php echo $file_namepic; ?>" width="80" height="80" /></div><div style="padding-top:20px;"><span><?php echo $_SESSION["session_company_name"]; ?></span><br /><?php echo $_SESSION["session_company_thainame"]; ?></div></div>
<div id="warppage" style="width:1000px; height:auto;">
<div id="headerpage" style="height:10px; text-align:center"></div>
<div class="style1" id="menu" style="height:30px; padding-left:10px; padding-top:10px; padding-right:10px;"><a href="user_manage.php">จัดการผู้ใช้</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;<a href="menu_manage.php">จัดการเมนู</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;<a href="" onclick="window.close();">x ปิดหน้านี้</a>
  <hr /></div>
<div id="contentpage" style="height:auto;">
 
 <div class="style5" style="width:auto;  padding-left:10px;">
 <?php
  $qry_user=pg_query("select * from f_menu order by id_menu");
  
  
  ?>
  <table width="930" border="0" style="background-color:#EEEDCC;">
  <tr>
    <td colspan="3" style="text-align:center;"><input type="button" onclick="window.close();" value="CLOSE" /></td>
    <td colspan="3" style="text-align:center;"><input type="button" onclick="window.location='add_menu.php'" value="ADD" /></td>
    </tr>
  <tr style="background-color:#D0DCA0">
    <td width="25">No.</td>
    <td width="78">id_menu</td>
    <td width="226">name menu password</td>
    <td width="306">path link </td>
    <td width="80">สถานะ</td>
    <td width="">คำอธิบายเมนู</td>
    <td width="">การใ้ช้งานปัจจุบัน</td>
	<td width="">alert admin</td>
    <td width="37">&nbsp;</td>
  </tr>
  <?php
  $a=0;
  while($res=pg_fetch_array($qry_user))
  {
   $a++;
  ?>
  <tr style="background-color:#EEF2DB">
    <td><?php echo $a; ?></td>
    <td><?php echo $res["id_menu"]; ?></td>
    <td><?php echo $res["name_menu"]; ?></td>
    <td><?php echo $res["path_menu"]; ?></td>
    <td><?php echo $res["status_menu"]; ?></td>
    <td><?php echo $res["menu_desc"]; ?></td>
    <td><?php echo $res["menu_status_use"]; ?></td>
	<td><input type="checkbox"<?php if($res["isAlert"]==1){echo "checked";} ?> onclick="return false"></td>
    <td><a href="detail_menu.php?idmenu=<?php echo $res["id_menu"]; ?>">แก้ไข</a></td>
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

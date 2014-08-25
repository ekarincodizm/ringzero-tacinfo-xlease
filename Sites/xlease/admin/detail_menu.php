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
<title>แก้ไขเมนู</title>
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
 
 function update_menu()
 {
   createXMLHttpRequest();
            
			var s_id = document.getElementById("a_ids").value;
			var s_name = document.getElementById("a_menu_name").value;
			var s_path = document.getElementById("a_path").value;
			var s_sta  = document.getElementById("a_sta").value;	
			var s_desc  = document.getElementById("a_menu_desc").value;	
			var s_stsuse  = document.getElementById("a_menu_status_use").value;	
			var s_alert;
			
			if(document.getElementById("a_alert").checked==true){
				s_alert = 1;
			} else {
				s_alert = 0;
			}
		
            xmlHttp.open("get", "update_menu.php?idmenu="+s_id+"&fmenu_name="+s_name+"&f_path="+s_path+"&f_sta="+s_sta+"&f_desc="+s_desc+"&f_stsuse="+s_stsuse+"&f_alert="+s_alert,true); 
			 
		
											   
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
			
			setTimeout("urls();",2000);	
			 
        }
		function urls() {
		  		
          		   
          window.location.href = 'menu_manage.php';
						
			 
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
  $pidmenu=pg_escape_string($_GET["idmenu"]);
  
  $qry_menu=pg_query("select * from f_menu where id_menu='$pidmenu' ");
  
  $resu=pg_fetch_array($qry_menu);
 
  if($resu["status_menu"]=='1')
  {
    $ss_sta="<select name=\"a_sta\" id=\"a_sta\" >
	       <option value=\"1\">ใช้งาน</option>
		   <option value=\"0\">ระงับการใช้งาน</option>
		</select>";
     
  }
  else
  {
     $ss_sta="<select name=\"a_sta\"  id=\"a_sta\" >
	        <option value=\"0\">ระงับการใช้งาน</option>
		    <option value=\"1\">ใช้งาน</option>
		</select>";
    
  }
  
  
  ?>
  <table width="930" border="0" style="background-color:#EEF2DB;" cellspacing="1" >
  <tr style="background-color:#D0DCA0;">
    <td width="144">id_menu</td>
    <td width="144">name_menu</td>
    <td width="376">path menu </td>
    <td width="102">status</td>
	<td>คำอธิบายเมนู</td>
    <td>การใช้งานปัจจุบัน</td>
	<td>alert admin</td>
    </tr>
  <tr style="background-color:#B7B7B7;"><input type="hidden" name="a_idm" id="a_idm" value="<?php echo $pidmenu; ?>"  />
    <td><input type="text" size="10" name="a_ids" id="a_ids" value="<?php echo trim($resu["id_menu"]); ?>" /></td>
    <td><input type="text" name="a_menu_name" id="a_menu_name"value="<?php echo $resu["name_menu"]; ?>"  /></td>
    <td><input type="text" size="35" name="a_path" id="a_path" value="<?php echo $resu["path_menu"]; ?>"  /></td>
    <td><?php echo $ss_sta; ?></td>
	<td>
		<input type="text" size="35" name="a_menu_desc" id="a_menu_desc" value="<?php echo $resu["menu_desc"]; ?>" />
	</td>
	<td>
		<select name="a_menu_status_use" id="a_menu_status_use">
			<option value="ยังใช้อยู่" <?php if($resu["menu_status_use"]=='ยังใช้อยู่'){ echo "selected"; } ?> >ยังใช้อยู่</option>
			<option value="ล้าสมัย" <?php if($resu["menu_status_use"]=='ล้าสมัย'){ echo "selected"; } ?>>ล้าสมัย</option>
			<option value="เลิกใช้" <?php if($resu["menu_status_use"]=='เลิกใช้'){ echo "selected"; } ?>>เลิกใช้</option>
		</select>
	</td>
	<td>
		<input type="checkbox"  name="a_alert" id="a_alert" value="1" <?php if($resu["isAlert"]==1){echo "checked";} ?>/>
	</td>
    </tr>
  <tr>
    <td colspan="6"><input type="button" value="SAVE" onclick="update_menu()"  /><div id="divInfo"></div></td>
  </tr>
</table>
</div>
<div id="footerpage"></div>
</div>
</div>
</div>
</body>
</html>

<?php
session_start();
include("../config/config.php");
$e_vd=pg_escape_string($_GET["vid"]);
$sql_evd=pg_query("select * from account.vender where \"VenderID\"='$e_vd'");
$res_evd=pg_fetch_array($sql_evd);



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title><?php echo $_SESSION["session_company_name"]; ?> co.,ltd</title>
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
 
 function save_acc()
 {
   createXMLHttpRequest();
         
			var s_hvid = document.getElementById("h_vid").value;
			var s_vtype = document.getElementById("v_type").value;
			var s_vname = document.getElementById("v_name").value;
			var s_vadd = document.getElementById("v_add").value;
			var s_vtel  = document.getElementById("v_tel").value;
			
			if(s_vname=="")
			{
			 alert("กรุณาใส่ข้อมูลร้าน");
			 return;
			}
				
		    xmlHttp.onreadystatechange=completeState;		
				
            xmlHttp.open("get", "process_edit_vender.php?vtype="+s_vtype+"&vname="+s_vname+"&vadd="+s_vadd+"&vtel="+s_vtel+"&h_vedit="+s_hvid,true); 
			 
		 xmlHttp.send(null);
		} 
											   
            function completeState() {
                if (xmlHttp.readyState == 4) {
                    if (xmlHttp.status == 200) {
					
					 //   sdiv=document.getElementById("divInfo").innerHTML = xmlHttp.responseText;
					  sdiv=xmlHttp.responseText;
					 // clearBox();
					  divClear();					  
			         //setTimeout("divClear();",1000);	
					 clearBox();
                       }
					 }
				 }	  
		   function clearBox()
		   {
		    document.getElementById("v_type").value="";
			document.getElementById("v_name").value="";
			document.getElementById("v_add").value="";
			document.getElementById("v_tel").value="";
		
		    window.location.href = 'frm_list_vender.php';	
		    
		   }		
				
           function divClear() 
		   {
		     
			 alert(sdiv);
		   
		   } 
      
		
</script>

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
<h1 class="style4"> <?php echo $_SESSION["session_company_name"]; ?></h1>
</div>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  <div class="style3" style="background-color:#333333; width:auto; height:20px; padding-left:10px;"><?php echo $_SESSION["session_company_name"]; ?> </div>
  <div class="style3" style="background-color:#000000; width:auto; height:20px; padding-left:10px;"></div>
  <div class="style5" style="width:auto; height:100px; padding-left:10px;"><br />
  
  <button onclick="window.location='frm_list_vender.php'"> รายชื่อ vender </button>
   <form>
   
   <table width="400" border="0" cellpadding="1" cellspacing="1" style="background-color:#CCCCCC;">
    <tr style="background-color:#66CCCC">
    <td colspan="3"><div align="center"><span class="style5" style="width:auto; height:100px; padding-left:10px;">Edit Vender <?php echo $res_evd["VenderID"]; ?></span></div></td>
    </tr>
	<input type="hidden" id="h_vid" name="h_vid" value="<?php echo $res_evd["VenderID"]; ?>"  /> 
    <tr style="background-color:#FFFFFF;">
    <td width="79">คำนำหน้า</td>
    <td width="305" colspan="2"><select name="v_type" id="v_type">
	
	                <?php echo "<option value=\"\">".$res_evd["type_vd"]."</option>"; ;?>
	                <option value="บจก">บจก</option>
					<option value="บมจ">บจม</option>
					<option value="หจก">หจก</option>
					<option value="นาย">นาย</option>
					<option value="นาง">นาง</option>
					<option value="นางสาว">นางสาว</option>
					</select>	</td>
  </tr>
  <tr style="background-color:#FFFFFF;">
    <td>ชื่อ</td>
    <td colspan="2"><input type="text" name="v_name" id="v_name" style="width:300px;" value="<?php echo $res_evd["vd_name"]; ?>"/></td>
  </tr>
  <tr style="background-color:#FFFFFF;">
    <td>ที่อยู่</td>
    <td colspan="2"><textarea name="v_add" id="v_add" style="width:300px;"><?php echo $res_evd["vd_address"]; ?></textarea></td>
  </tr>
  <tr style="background-color:#FFFFFF;">
    <td>โทรศัพท์</td>
    <td colspan="2"><input type="text" name="v_tel" id="v_tel" style="width:300px;" value="<?php echo $res_evd["vd_tel"]; ?>"/></td>
  </tr>
  <tr style="background-color:#FFFFFF;">
    <td>&nbsp;</td>
    <td><input type="button" name="save_gs" onclick="save_acc();" value="save" /></td>
    <td><input type="reset" /></td>
  </tr>
</table>
</form>
  </div>
  <div class="story" id="divInfo">
    <h3>&nbsp;</h3>
  </div>
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>

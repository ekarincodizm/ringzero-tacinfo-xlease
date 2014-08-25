<?php
session_start();
include("../config/config.php");
$_SESSION["av_iduser"];
$idno=pg_escape_string($_POST["idno_names"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV_admin.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>

<link rel="stylesheet" href="menu/lavalamp_test.css" type="text/css" media="screen">
    <script type="text/javascript" src="menu/jquery-1.2.3.min.js"></script>
    <script type="text/javascript" src="menu/jquery.easing.min.js"></script>
    <script type="text/javascript" src="menu/jquery.lavalamp.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $("#menu").lavaLamp({
                fx: "backout",
                speed: 700,
                click: function(event, menuItem) {
                    return true;
                }
            });
        });
    </script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION["session_company_name"]; ?></title>
<script type="text/javascript" src="autocomplete.js"></script>
<link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
<style type="text/css">
    .mouseOut {
    background:#C7ECFA;
    color:#000000;
    }

    .mouseOver {
    background:#00CCCC;
    color: #000000;
    }
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
.style7 {color: #0000FF}

   
    

-->
</style>
<!-- InstanceBeginEditable name="doctitle" -->
<title><?php echo $_SESSION["session_company_name"]; ?></title>
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

<!-- InstanceEndEditable -->

<!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable -->
</head>

<body style="background-color:#ffffff; margin-top:0px;">
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
<h1 class="style4"> <?php echo $_SESSION["session_company_name"]; ?></h1>
</div>
<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  <div class="style3" style="background-image:url(../css/bg_bar.jpg); background-repeat:no-repeat; width:800px; height:27px; ">
       <div style="float:left; width:520px;">
	   <ul class="lavaLampNoImage" id="menu">
	   
	   <?php
	   $qrymenu=pg_query("select * from f_menu where id_menu like 'A0%' ");
	   while($res_menu=pg_fetch_array($qrymenu))
	   {
	    #$xp_path=substr($res_menu["path_menu"],0,10); 
		$xp_path=$res_menu["path_menu"]; 
	   ?>
        <li><a href="../<?php echo $xp_path; ?>"><?php echo $res_menu["name_menu"]; ?></a></li>
	<?php
	 }
	 
	?>
	<li class="current"><a href="" onclick="window.close();">ปิดหน้านี้</a></li>
</ul>
</div>

      <div align="right" style="padding-right:27px; width:180px; float:left;">
	  <div style="padding-top:4px;  height:27px; background-image:url(../css/bg_search.jpg); background-repeat:no-repeat;" align="center">	
	  <form method="post" action="profile.php" name="frmsent"> 
	    <input type="text" id="idno_names" name="idno_names" style="border:0px;" onchange="document.frmsent.submit();"/>
	    <input name="h_id" type="hidden" id="h_id" value="0"  />
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
		return "../admin/listdata.php?q=" + this.value;
    });	
}	
make_autocom("idno_names","h_id");
</script>
</form>
	  </div>
     </div>
  </div>
  <div class="style3 style7" style="background-color:#ffffff; width:auto; height:20px; padding-left:10px;">
  </div>
  <!-- InstanceBeginEditable name="EditRegion3" -->
<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  <div class="style5" style="width:auto; height:100px; padding-left:10px;">
  <?php
  $qry_dep=pg_query("select * from department order by dep_id ");
  
  
  ?>
  <table width="778" border="0" style="background-color:#EEEDCC;">
  <tr style="background-color:#D0DCA0">
    <td width="26">No.</td>
    <td width="155">dep_id</td>
    <td colspan="5">dep_name</td>
    <td width="44">&nbsp;</td>
  </tr>
  <?php
  $a=0;
  while($res=pg_fetch_array($qry_dep))
  {
   $a++;
  ?>
  <tr style="background-color:#EEF2DB">
    <td><?php echo $a; ?></td>
    <td><?php echo $res["dep_id"]; ?></td>
    <td colspan="5"><?php echo $res["dep_name"]; ?></td>
    <td><a href="detail_dep.php?iddep=<?php echo $res["dep_id"]; ?>">แก้ไข</a></td>
  </tr>
  <?php
  }
  ?>
  <tr>
    <td colspan="3" style="text-align:center;"><input type="button" onclick="javascript:history.back();" value="BACK" /></td>
    <td colspan="5" style="text-align:center;"><input type="button" value="เพิ่มผู้ใช้" onclick="parent.location='add_dep.php'" /></td>
    </tr>
</table>

 
  </div>
</div>
<!-- InstanceEndEditable -->
</div>
</body>
<!-- InstanceEnd --></html>

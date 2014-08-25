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
 
 function update_dep()
 {
   createXMLHttpRequest();
            
			var d_id = document.getElementById("id_dep").value;
			var d_name = document.getElementById("name_dep").value;
			var n_id = document.getElementById("new_dep").value;
				
            xmlHttp.open("get", "update_dep.php?did="+d_id+"&fd_name="+d_name+"&f_new_id="+n_id,true); 
			 
		
											   
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
		  		
          		   
          window.location.href = 'dep_manage.php';
						
			 
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
  <div class="style3" style="background-color:#FFCC02;; width:auto; height:20px; padding-left:10px;">AV. Leasing </div>
  <div class="style3" style="background-color:#996600; width:auto; height:20px; padding-left:10px;"></div>
  <div class="style5" style="width:auto; height:100px; padding-left:10px;">
  <?php
  $pid=pg_escape_string($_GET["iddep"]);
  
  $qry_mdep=pg_query("select * from department where dep_id='$pid' ");
  
  $res_dep=pg_fetch_array($qry_mdep);
 
 
  
  ?>
  <table width="779" border="0" style="background-color:#EEF2DB;" cellspacing="1" >
  <tr style="background-color:#D0DCA0;">
    <td width="144" style="padding-left:5px;">dep_id</td>
    <td style="padding-left:5px;">dep_name</td>
    </tr>
  <tr style="background-color:#DEDCDC"><input type="hidden" name="id_dep" id="id_dep" value="<?php echo $pid; ?>"  />
    <td style="padding-left:5px;"><input type="text" name="new_dep" id="new_dep" value="<?php echo trim($res_dep["dep_id"]); ?>" /></td>
    <td style="padding-left:5px;"><input type="text" name="name_dep" style="width:500px;" id="name_dep"value="<?php echo $res_dep["dep_name"]; ?>"  /></td>
    </tr>
  <tr>
    <td colspan="2"><input type="button" value="SAVE" onclick="update_dep()"  /><div id="divInfo"></div></td>
  </tr>
</table>
</div>
<!-- InstanceEndEditable -->
</div>
</body>
<!-- InstanceEnd --></html>

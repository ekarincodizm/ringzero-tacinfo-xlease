<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<link rel="stylesheet" href="../admin/menu/lavalamp_test.css" type="text/css" media="screen">
    <script type="text/javascript" src="../admin/menu/jquery-1.2.3.min.js"></script>
    <script type="text/javascript" src="../admin/menu/jquery.easing.min.js"></script>
    <script type="text/javascript" src="../admin/menu/jquery.lavalamp.min.js"></script>
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
<title>AV. leasing co.,ltd</title>
<script type="text/javascript" src="../admin/autocomplete.js"></script>
<link rel="stylesheet" href="../admin/autocomplete.css"  type="text/css"/>
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
<!-- TemplateBeginEditable name="doctitle" -->
<!-- TemplateEndEditable -->

<!-- TemplateBeginEditable name="head" --><!-- TemplateEndEditable -->
</head>

<body style="background-color:#ffffff; margin-top:0px;">
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
<h1 class="style4"> AV.LEASING</h1>
</div>
<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  <div class="style3" style="background-image:url(../css/bg_bar.jpg); background-repeat:no-repeat; width:800px; height:27px; ">
       <div style="float:left; width:520px;">
	   <ul class="lavaLampNoImage" id="menu">
	   
	   <?php
	   include("../config/config.php");
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
	  <form method="post" action="../admin/profile.php" name="frmsent"> 
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
  <!-- TemplateBeginEditable name="EditRegion3" -->
<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  <div class="style5" style="width:auto; height:100px; padding-left:10px;"></div>
</div>
<!-- TemplateEndEditable -->
</div>
</body>
</html>

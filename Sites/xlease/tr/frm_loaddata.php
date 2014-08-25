<?php
session_start();
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}

$iduser = $_SESSION['uid'];
$c_code=$_SESSION["session_company_code"];
//$c_code="AVL";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="file:///C|/wamp/www/av/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title><?php echo $_SESSION["session_company_name"]; ?></title>
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
<!-- InstanceBeginEditable name="head" -->
<script type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
  window.location.reload();
}
//-->
</script>
<!-- InstanceEndEditable -->
</head>

<body style="background-color:#ffffff; margin-top:0px;">
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
<h1 class="style4"><?php echo $_SESSION["session_company_name"]; ?></h1>
</div>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  <div class="style3" style="background-color:#333333; width:auto; height:20px; padding-left:10px;"><?php echo $_SESSION["session_company_name"]; ?> </div>
  <div class="style3" style="background-color:#000000; width:auto; height:20px; padding-left:10px;"></div>
  <div class="style5" style="width:auto; height:50px; padding-left:10px;">
  Load Bank data tranfers
  <button onclick="window.close()">CLOSE</button></div>
  <div>
  <form name="upfile" action="uploadfile.php" method="post" enctype="multipart/form-data">
  Upload Tranfer data <input type="file" name="file" id="file"  />
  <input type="submit" value="Upload"  />
  </form>
  </div>
  <div>
  <?php
	$dir = 'upload'."/".$c_code."/";
	$files = scandir($dir,1);
?>	
	<br />
	<table width="522" border="0" style="background-color:#EDF1DA;">
	  <tr style="background-color:#FAFDEC;">
		<td colspan="3"><div align="center">file in folder upload </div></td>
		</tr>
	  <tr style="background-color:#FAFDEC;">
	    <td width="36">No.</td>
	    <td width="392">fileName</td>
	    <td width="72">delete</td>
	    </tr>
	 
	 
    <?php
	
	foreach ($files as &$file) 
	{
	  
	 if ($file!='.' && $file!='..' )
	 {
	   
	   $n++;
	 ?>
	  <tr style="background-color:#FFFFFF">
	    <td><?php echo $n; ?></td>
	    <td><?php echo $file.'<br>'; ?></td>
	    <td><a href="Javascript:MM_openBrWindow('del.php?d_id=<?php echo $file; ?>','','width=400,height=100')">delete</a></td>
	    </tr>
	  
	 <?php
	 }
    }
	?>
	 <tr>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    </tr>
	</table>

  </div>
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>

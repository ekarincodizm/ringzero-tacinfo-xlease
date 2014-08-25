<?php
session_start();
include("../config/config.php");

//gen frm_id------//
//$sql_q=pg_query("select count(auto_id)");

// end gen frm id //
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title><?php echo $_SESSION["session_company_name"]; ?> co.,ltd</title>
 <script type="text/javascript">
  	var gFiles = 0;
	var summary;
	function addFile() 
	{
	var li = document.createElement('li');
	li.setAttribute('id', 'file-' + gFiles);
	li.innerHTML = '<select name="type_acb[]" id="typeacb"><?php 
	$qry_type=pg_query("select * from account.\"AcTable\" ");
	while($res_type=pg_fetch_array($qry_type))
	{ 
	echo  "<option value=\"$res_type[AcID]\">$res_type[AcName]</option>"; 
	}
	?></select>&nbsp;&nbsp;<select name="fm_dcr[]" id="fm_dcr"><option value="DR">&nbsp;&nbsp;Dr&nbsp;&nbsp;</option><option value="CR">&nbsp;&nbsp;Cr&nbsp;&nbsp;</option></select><button onClick="removeFile(\'file-' + gFiles + '\')">REMOVE</button>';
	document.getElementById('files-root').appendChild(li);
	gFiles++;
	
	}
	function removeFile(aId) 
	{
	var obj = document.getElementById(aId);
	obj.parentNode.removeChild(obj);
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
  <form method="post" action="process_add_fm.php" >
  <table width="779" border="0" cellpadding="3" cellspacing="1" style="background-color:#CCCCCC;">
  <tr>
    <td colspan="4" style="background-color:#DEE7BE">
	  <div align="center">เพิ่มรายการผูกสูตรทางบัญชี	    </div></td>
    </tr>
  <tr style="background-color:#F5F7E1;">
    <td width="120">formular ID </td>
    <td colspan="3"><input type="text" name="fm_id" /></td>
    </tr>
  <tr style="background-color:#F5F7E1;">
    <td>formular name </td>
    <td colspan="3"><input name="fm_name" type="text" style="width:300px;" /></td>
    </tr>
  <tr style="background-color:#F5F7E1;">
    <td>Type acb </td>
    <td colspan="3"><select name="fm_type">
	   <option value="GJ">GJ</option>
	   <option value="PC">PC</option>
	   <option value="RC">RC</option>
       </select></td>
    </tr>
  <tr>
    <td colspan="4" style="background-color:#FFFFFF;">
	<ol id="files-root">
    </ol></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="340"><input type="button" name="btnAdd" id="btnAdd" onclick="JavaScript:addFile();" value="Add" /></td>
    <td width="208"><input type="submit" value="save" /></td>
    <td width="82"></td>
  </tr>
</table>
</form>

  </div>
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>

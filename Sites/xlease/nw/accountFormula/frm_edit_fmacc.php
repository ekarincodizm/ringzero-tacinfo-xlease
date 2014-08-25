<?php
session_start();
$fsid=trim($_GET["fmID"]);
include("../../config/config.php");

//$sql_fm=pg_query("select * from account.\"FormulaID\" where fm_id='$fsid' ");
//$res_fm=pg_fetch_array($sql_fm);

//$fmmid=$res_fm["frm"];


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<!--<title><?php echo $_SESSION["session_company_name"]; ?> co.,ltd</title>-->
<title>(THCAP) ผูกสูตรทางบัญชี</title>
	 <link type="text/css" rel="stylesheet" href="act.css"></link>
	<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script language="JavaScript">
$(document).ready(function(){
	
	$("#rename").click(function(){
		$("#renamebox").show();
		$("#rename").hide();
		$("#cancle").show();
		$("#nameShow").hide();
		$("#checkRename").val("Y");
	});
	$("#cancle").click(function(){
		$("#renamebox").hide();
		$("#rename").show();
		$("#cancle").hide();
		$("#nameShow").show();
		$("#checkRename").val("N");
	});
    
});
function KeyData(count){
	$("#s_acid"+count).autocomplete({
		source: "s_acid.php",
        minLength:1
    });
}
	   var HttPRequest = false;

	   function doCallAjax(ID) {
 
	   
		  HttPRequest = false;
		  if (window.XMLHttpRequest) { // Mozilla, Safari,...
			 HttPRequest = new XMLHttpRequest();
			 if (HttPRequest.overrideMimeType) {
				HttPRequest.overrideMimeType('text/html');
			 }
		  } else if (window.ActiveXObject) { // IE
			 try {
				HttPRequest = new ActiveXObject("Msxml2.XMLHTTP");
			 } catch (e) {
				try {
				   HttPRequest = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e) {}
			 }
		  } 
		  
		  if (!HttPRequest) {
			 alert('Cannot create XMLHTTP instance');
			 return false;
		  }
	
		  var url = 'del_fm.php';
		  var pmeters = "tID="+ID;

			HttPRequest.open('POST',url,true);

			HttPRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			HttPRequest.setRequestHeader("Content-length", pmeters.length);
			HttPRequest.setRequestHeader("Connection", "close");
			HttPRequest.send(pmeters);
			
			
			HttPRequest.onreadystatechange = function()
			{

				 if(HttPRequest.readyState == 4) // Return Request
				  {
				     
					 
					 if(HttPRequest.responseText == 'Y')
					  {						
					 
					  document.getElementById("tr"+ID).style.display = 'none';
					  }
				  }				
			}

         
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
<!--<h1 class="style4"> <?php echo $_SESSION["session_company_name"]; ?></h1>-->
<h1 class="style4">(THCAP) ผูกสูตรทางบัญชี</h1>
</div>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  <!--<div class="style3" style="background-color:#333333; width:auto; height:20px; padding-left:10px;"><?php echo $_SESSION["session_company_name"]; ?> </div>-->
  <div class="style3" style="background-color:#333333; width:auto; height:20px; padding-left:10px;">(THCAP) ผูกสูตรทางบัญชี</div>
  <div class="style3" style="background-color:#000000; width:auto; height:20px; padding-left:10px;"></div>
  <div class="style5" style="width:auto; height:100px; padding-left:10px;"><br>
  <form method="post" action="process_update_fm.php">
  <input type="hidden" name="s_fmid" value="<?php echo $fsid; ?>" />
  <table width="712" border="0" cellpadding="1" cellspacing="1" style="background-color:#CCCCCC;">
  <tr style="background-color:#F5F7E1; padding:2px;">
    <td colspan="4" style="padding: 3px 3px 3px 3px;">
		<div align="center"><strong>สูตรทางบัญชี : <label id="nameShow"><?php echo $_GET["fmname"]; ?></label></strong> <label id="rename"><font color="#0000FF"><u>(Rename)</u></font></label>
			<input type="text" name="renamebox" id="renamebox" value="<?php echo $_GET["fmname"]; ?>" size="10" hidden />
			<label id="cancle" hidden><font color="#FF0000"><u>ยกเลิก</u></font></label>
			<input type="hidden" name="checkRename" id="checkRename" value="N"/>
		</div>
	</td>
    </tr>
	
  <tr style="background-color:#DEE7BE; padding:2px;" >
    <td width="113" style="padding:2px;">AcID</td>
    <td width="447">AcName</td>
    <td width="77"><div align="center">Dr-Cr</div></td>
    <td width="62"><div align="center">Delete</div></td>
  </tr>
	<?php
	$sql_fm=pg_query("select * from account.\"all_accFormulaDetails\"  
	                                  where afd_fmid='$fsid' ");
	$count=0;
	while($res_fm=pg_fetch_array($sql_fm))
	{
		$count++;
		$acc_fm=$res_fm["afd_accno"];
		$acc_autoid=$res_fm["afd_autoid"];
	  
		if($res_fm["afd_drcr"]==1)
		{
			$if_dcr="<select name=\"f_dcr[]\" style=\"width:60px;\">
			<option value=\"1\">Dr</option>
			<option value=\"2\">Cr</option>
			</select>";
		}
		else
		{
			$if_dcr="<select name=\"f_dcr[]\" style=\"width:60px;\">
			<option value=\"2\">Cr</option>
			<option value=\"1\">Dr</option>
			</select>";
		}
	  
	  //$sql_ahead=pg_query("select * from account.\"AcTable\" where \"AcID\"='$acc_fm' ");
	  $sql_ahead=pg_query("select * from account.\"V_all_accBook\" where \"accBookserial\"='$acc_fm' ");
	  $res_ahead=pg_fetch_array($sql_ahead);
	  
	?>
	<tr id="tr<?php echo $acc_autoid; ?>" style="background-color:#EDF1DA;">
		<td style="padding-left:3px;"><?php echo $res_ahead["accBookID"]; ?>
			<input type="hidden" name="s_autoID[]" value="<?php echo $acc_autoid; ?>" />
		</td>
		
		<td style="padding-left:3px;">
			<input type="text" name="s_acid[]" id="s_acid<?php echo $count; ?>" value="<?php echo $res_ahead["accBookserial"]."# ".$res_ahead["accBookID"].": ".$res_ahead["accBookName"]; ?>" size="50" onkeyup="KeyData(<?php echo $count; ?>);" onblur="KeyData(<?php echo $count; ?>);" />
		</td>
		
		<td >
			<div align="center"><?php echo $if_dcr; ?></div>
		</td>
		
		<!--<td ><div align="center"><a href="JavaScript:if(confirm('Are you delete'))doCallAjax('<?php echo $acc_autoid; ?>');">Del</a></div></td>-->
		<td >
			<div align="center"><a href="JavaScript:if(confirm('ถ้าตกลงจะลบทิ้งทันที'))doCallAjax('<?php echo $acc_autoid; ?>');">Del</a></div>
		</td>
	</tr>
   <?php
   }
   ?>
   <tr style="background-color:#DEE7BE; padding:2px;" >
    <td width="113" style="padding:2px;"></td>
    <td width="447"><div align="center"><input type="submit" value="Update"  /><input type="button" value="เพิ่มสมุดบัญชี" onclick="window.location='frm_add_formular.php?editP=Y&fsid=<?php echo $fsid; ?>'"/></div></td>
    <td colspan="2">&nbsp;</td>
   </tr>
</table>
</form>
<button onclick="window.location='frm_list_fm.php'">BACK</button>
  </div>
  
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>

<?php
session_start();
include("../../config/config.php");
$editP=pg_escape_string($_GET['editP']);


if($editP=="Y"){
	$fsid=pg_escape_string($_GET['fsid']);
	$qry_detail=pg_query("select * from account.\"all_accFormula\" where af_fmid='$fsid'");
	while($res_detail=pg_fetch_array($qry_detail)){
		$fm_name=$res_detail['af_fmname'];
		$type_acb=$res_detail['af_typeacb'];
		$useby =$res_detail['af_useformula'];	
	}
	$readonly="readonly";
	$disabled="disabled";
}
//gen frm_id------//
//$sql_q=pg_query("select count(auto_id)");

// end gen frm id //
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<!-- InstanceBeginEditable name="doctitle" -->
<!--<title><?php //echo $_SESSION["session_company_name"]; ?> co.,ltd</title>-->
<title>(THCAP) ผูกสูตรทางบัญชี</title>
<script language=javascript>
 function checkname(){
		
		$.post("checkname.php",
		{
			frname : document.getElementById("fm_name").value	
		},
		function(data){		
				if(data == 'NO'){
						document.getElementById("fm_name").style.backgroundColor ="#FF0000";
						document.getElementById("valuechk").value='0';
				}else if(data == 'YES'){
						document.getElementById("fm_name").style.backgroundColor = "#33FF33";
						document.getElementById("valuechk").value='1';
				}
		});
}
	
  	var gFiles = 0;
	var summary;
	function addFile() 
	{
	var li = document.createElement('li');
	li.setAttribute('id', 'file-' + gFiles);
	li.innerHTML = '<select name="type_acb[]" id="typeacb"><?php 
	//$qry_type=pg_query("select * from account.\"AcTable\" ");
	$qry_type=pg_query("select * from account.\"V_all_accBook\" ");
	while($res_type=pg_fetch_array($qry_type))
	{ 
		echo  "<option value=\"$res_type[accBookserial]\">$res_type[accBookID]: $res_type[accBookName]</option>";
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
	
	function validate(){
		var errormessage = "Please complete the following: \n-----------------------------------\n";
		var error = 0;
		
		if($("#fm_name").val()== "") {
			errormessage += '--> กรุณาใส่ข้อมูล formular name \n';
			error++;
		}
		if($("#valuechk").val()== "0") {
			errormessage += '--> formular name ซ้ำ! \n';
			error++;
		}
		var chk='<?php echo $editP;?>';
		if(chk==''){
		//ตรวจสอบว่ามีรายละเอียด บัญชีหรือไม่
			if(gFiles<1) {
				errormessage += '--> กรุณาพิ่มรายการผูกสูตรทางบัญชี \n';
				error++;
			}
		}
		if(error > 0){
			alert(errormessage);
			return false;
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
  <div class="style5" style="width:auto; height:100px; padding-left:10px;"><br />
  <form method="post" action="process_add_fm.php" onsubmit="return validate();">
  <table width="779" border="0" cellpadding="3" cellspacing="1" style="background-color:#CCCCCC;">
  <tr>
    <td colspan="4" style="background-color:#DEE7BE">
	  <div align="center">เพิ่มรายการผูกสูตรทางบัญชี	    </div></td>
    </tr>
  <!--<tr style="background-color:#F5F7E1;">
    <td width="120">formular ID </td>
    <td colspan="3"><input type="text" name="fm_id" /></td>
    </tr>-->
  <tr style="background-color:#F5F7E1;">
    <td>formular name </td>
    <td colspan="3">
		<input name="fm_name" id="fm_name" type="text" style="width:300px;" onkeyup="checkname();" onblur="checkname();" onchange="checkname();"  value="<?php echo $fm_name;?>" <?php echo $readonly; ?> autocomplete="off" />
		<input type="hidden" name="valuechk" id="valuechk" />
	</td>
    </tr>
  <tr style="background-color:#F5F7E1;">
    <td>Type acb </td>
    <td colspan="3"><select name="fm_type">
		<?php 
			$qry_type = pg_query("select \"GJ_typeID\" from account.\"General_Journal_Type\" order by \"GJ_typeID\" ");
			while($res_type = pg_fetch_array($qry_type)){
				$GJ_typeID = $res_type['GJ_typeID'];
		?>		
				<option value="<?php echo $GJ_typeID ?>" <?php if($type_acb==$GJ_typeID){ echo "selected";}?> ><?php echo $GJ_typeID; ?></option>";
		<?php	
			} 
		?>
		
       </select></td>
   </tr >
   <tr style="background-color:#F5F7E1;">
		<td>Use by</td>
		<td colspan="3">
			<input type="radio" name="useby" value="1" <?php if($useby=="" || $useby=="1"){ echo "checked"; }?> />auto(ใช้สูตรด้วยระบบ)
			<input type="radio" name="useby" value="0"<?php if( $useby=="0"){ echo "checked"; }?>/>manual(ใช้สูตรด้วยเจ้าหน้าที่)
		</td>
   </tr>
   <?php if($editP=="Y"){ ?>
   <tr style="background-color:#F5F7E1;">
   <td>รายละเอียด :</td>
   <td colspan="3"></td>
   </tr>
   <?php 
   //แสดงรายการ  สมุดบัญชี ของสูตรทางบัญชี   กรณีที่เปิดมาแก้ไข
	//1.ดึงข้อมูล  บัญชีทั้งหมดของูตรทางบัญชี นี้
	$sql_fm=pg_query("select * from account.\"all_accFormulaDetails\"  
	                                  where afd_fmid='$fsid' ");
	//2.แสดงข้อมูลรายละเอียดบัญชีทั้งหมดของูตรทางบัญชี นี้ โดยไม่ให้ทำการแก้ไข
		while($res_fm=pg_fetch_array($sql_fm))
		{	$acc_fm=$res_fm["afd_accno"];
			$acc_autoid=$res_fm["afd_autoid"];
			if($res_fm["afd_drcr"]==1)
			{
				$if_dcr="<select disabled style=\"width:60px;\">
				<option value=\"1\">Dr</option>
				<option value=\"2\">Cr</option>
				</select>";
			}
			else
			{
				$if_dcr="<select disabled style=\"width:60px;\">
				<option value=\"2\">Cr</option>
				<option value=\"1\">Dr</option>
				</select>";
			}
			$sql_ahead=pg_query("select * from account.\"V_all_accBook\" where \"accBookserial\"='$acc_fm'");
			$res_ahead=pg_fetch_array($sql_ahead);
		
			echo "<tr bgcolor=\"#F5F7E1\">"; 
			?>
			 <td></td><td colspan="2"><input type="text" disabled value="<?php echo $res_ahead["accBookserial"].'#'.$res_ahead["accBookID"].': '.$res_ahead["accBookName"]?>" size="85"></td>
			<?php echo "<td>$if_dcr</td>";		
			echo "</tr>";
		} 
	}	

?>
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
	<input type="hidden" name="editP" value="<?php echo $editP; ?>"/>
	<input type="hidden" name="fm_id" value="<?php echo $fsid; ?>"/>
</form>
<?php if($editP){ ?>
	<button onclick="window.location='frm_edit_fmacc.php?fmID=<?php echo $fsid;?>&fmname=<?php echo $fm_name;?>'">BACK</button>
<?php } else {?>
	<button onclick="window.location='frm_list_fm.php'">BACK</button>
<?php } ?>
  </div>
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>

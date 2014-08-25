<?php
session_start();
include("../../config/config.php");
include('class.upload.php');

if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
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
$annId=$_GET["annId"];
$typeann = $_GET["type"];

$query=pg_query("select * from \"nw_annoucement\" where \"annId\"='$annId'");
if($result=pg_fetch_array($query)){
	$annId=$result["annId"];
	$typeAnnId2=$result["typeAnnId"];
	$annTitle=str_replaceout($result["annTitle"]);
	$annContent=str_replaceout($result["annContent"]);
	$statusim=$result["statusImportance"];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>แก้ไข Annoucement</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>  
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
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
.weightfont{
	font-weight:bold
}
</style>
<script language=javascript>
function validate() {

var theMessage = "Please complete the following: \n-----------------------------------\n";
var noErrors = theMessage

if (document.form1.annTitle.value=="") {
    theMessage = theMessage + "\n -->  กรุณากรอกชื่อเรื่อง";
}
// if (document.form1.annContent.value=="") {
    // theMessage = theMessage + "\n -->  กรุณากรอกเนื้อหา";
// }

if (theMessage == noErrors) {
    return true;
}else{
    alert(theMessage);
    return false;
}

}

</script>
</head>

<body>
<div style="width:800px; height:auto; margin-left:auto; margin-right:auto;">
	<div class="style2" id="super_head" style="padding-left:10px; height:90px; width:800px;">
		<span class="style2" style="padding-left:10px; height:60px; width:800px; ">
		<div style="width:90px; float:left;"><img src="../../images/<?php echo $file_namepic; ?>" width="80" height="80" /></div>
		<div style="padding-top:20px;"><span><?php echo $_SESSION["session_company_name"]; ?></span><br /><?php echo $_SESSION["session_company_thainame"]; ?></div>
	</div>
	<div id="warppage" style="width:850px; height:auto;">
		<div id="headerpage" style="height:10px; text-align:center"></div>
		<div class="style1" id="menu" style="height:30px; padding-left:10px; padding-top:10px; padding-right:10px;">แก้ไข Annoucement<hr/></div>
		<div style="height:auto; padding-left:10px; padding-right:10px;"><br />
			<form method="post" name="form1" action="process_annouce.php" enctype="multipart/form-data">
			<table width="700" border="0" style="background-color:#EEF2DB;" cellspacing="1" align="center">
			<tr style="background-color:#D0DCA0;" align="left">
				<td>
					<table width="100%" border="0" style="background-color:#EEF2DB;" cellspacing="1" align="center">	
						<tr>
							<td width="100" height="20"></td>
							<td width="10"></td>
							<td></td>
						</tr>
						<tr height="25">
							<td align="right" class="weightfont" width="30">ประเภทประกาศ:</td>
							<td width="10">:</td>
							<td>
							<select name="typeAnnId2">
								<?php
								$querytype=pg_query("select * from \"nw_annoucetype\" where \"typeStatusUse\"='TRUE' order by \"typeAnnId\"");
								while($restype=pg_fetch_array($querytype)){
									$typeAnnId=$restype["typeAnnId"];
									$typeAnnName=$restype["typeAnnName"];
								?>
								<option value="<?php echo $typeAnnId?>" <?php if($typeAnnId2==$typeAnnId){ echo "selected"; }?>><?php echo $typeAnnName?></option>
								<?php
								}
								?>
							</select>
						</td>
						</tr>
						<tr height="25">
							<td align="right" class="weightfont">ชื่อเรื่อง</td>
							<td width="10">:</td>
							<td><input type="text" name="annTitle" size="60" value="<?php echo $annTitle?>"><input type="checkbox" name="statusImportance" value="TRUE" <?php if($statusim=='t'){ echo "checked";}?>> <b>ประกาศสำคัญ</b></td>
						</tr>
						<tr>
							<td valign="top" align="right" class="weightfont">เนื้อหา</td>
							<td valign="top" width="10">:</td>
							<td><textarea class="ckeditor" cols="100" id="annContent" name="annContent" rows="10"><?php echo $annContent?></textarea></td>
						</tr>
						<tr height="25">
							<td align="right" class="weightfont" valign="top">รูปภาพ/ไฟล์แนบ</td>
							<td width="10"valign="top">:</td>
							<td>
								<?php
								$queryfile=pg_query("select * from \"nw_annoucefile\" where \"annId\"='$annId'");
								while($res_file=pg_fetch_array($queryfile)){
									$pathfile=$res_file["pathfile"];
									echo "<a href=\"upload/$pathfile\"><u>$pathfile</u></a><br/>";
								}
								?>
							
								<input type="file" size="32" name="my_field[]" value=""><font color="red"> * ชื่อไฟล์ภาษาอังกฤษเท่านั้น</font><br>
								<input type="file" size="32" name="my_field[]" value="" /><font color="red"> * ชื่อไฟล์ภาษาอังกฤษเท่านั้น</font><br>
								<input type="file" size="32" name="my_field[]" value="" /><font color="red"> * ชื่อไฟล์ภาษาอังกฤษเท่านั้น</font><br>
								<input type="file" size="32" name="my_field[]" value="" /><font color="red"> * ชื่อไฟล์ภาษาอังกฤษเท่านั้น</font><br>
								<input type="file" size="32" name="my_field[]" value="" /><font color="red"> * ชื่อไฟล์ภาษาอังกฤษเท่านั้น</font>
							</td>
						</tr>
						<tr>
							<td height="20"></td>
							<td width="10"></td>
							<td></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="center" height="50"><input type="hidden" name="annId" value="<?php echo $annId?>"><input type="hidden" name="method" value="edit"><input type="submit" value="บันทึก" onclick="return validate()">
				
				<?php if($typeann == 'edit'){ ?>
					<input type="hidden" name="typeann" value="<?php echo $typeann ?>">	
					<input type="button" value="BACK" onclick="window.location='frm_annEdit_newbie.php'" /></td>
				<?php }else{?>
					<input type="button" value="BACK" onclick="window.location='frm_Index.php'" /></td>
				<?php } ?>	
			</tr>
			</table>
			</form>
		</div>
		<div id="footerpage"></div>
	</div>
</div>
</body>
</html>

<?php
session_start();
$_SESSION["av_iduser"];
include("../../config/config.php");
$value  = pg_escape_string($_POST['value']);
if($value == "post"){
	$ad_idmenu = pg_escape_string($_POST['ad_idmenu']);
	$a_gp = pg_escape_string($_POST['a_gp']);
}else{
	$ad_idmenu = pg_escape_string($_GET['ad_idmenu']);
	$a_gp = pg_escape_string($_GET['a_gp']);
}

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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>จัดการสิทธิแบบกลุ่ม</title>
<script type="text/javascript" src="autocomplete.js"></script>
<link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
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

<script language="Javascript">
function selectAll(select){
    with (document.form2)
    {
        var checkval = false;
        var i=0;

        for (i=0; i< elements.length; i++)
            if (elements[i].type == 'checkbox' && !elements[i].disabled)
                if (elements[i].name.substring(0, select.length) == select)
                {
                    checkval = !(elements[i].checked);    break;
                }

        for (i=0; i < elements.length; i++)
            if (elements[i].type == 'checkbox' && !elements[i].disabled)
                if (elements[i].name.substring(0, select.length) == select)
                    elements[i].checked = checkval;
    }
}
function checkdata() {
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var noErrors = theMessage

	if (document.form.ad_idmenu.value=="") {
	theMessage = theMessage + "\n -->  กรุณาเลือก Template";
	}

	// If no errors, submit the form
	if (theMessage == noErrors) {
	return true;
	}else {
	// If errors were found, show alert message
		alert(theMessage);
		return false;
	}
}
</script>
</head>

<body>
<div id="swarp" style="width:800px; height:auto; margin-left:auto; margin-right:auto;">
<div class="style2" id="super_head" style="padding-left:10px; height:90px; width:800px;"><span class="style2" style="padding-left:10px; height:60px; width:800px; "><div style="width:90px; float:left;"><img src="../../images/<?php echo $file_namepic; ?>" width="80" height="80" /></div><div style="padding-top:20px;"><span><?php echo $_SESSION["session_company_name"]; ?></span><br /><?php echo $_SESSION["session_company_thainame"]; ?></div></div>
<div id="warppage" style="width:800px; height:auto;">
<div id="headerpage" style="height:10px; text-align:center"></div>
<form name="form" method="post">
<input type="hidden" name="value" value="post">
<div class="style1" id="menu" style="height:30px; padding-left:10px; padding-top:10px; padding-right:10px;">
	เลือก Template :
	<select name="ad_idmenu" id="ad_idmenu" onChange="this.form.submit();">  
		<option value="" <?php if($ad_idmenu == ""){ echo "selected";}?>>-----เลือก-----</option><?php
		//แสดงเฉพาะเมนูที่ใช้งาน
		$qry_menu=pg_query("select * from nw_template where \"tempStatus\"='TRUE' order by \"tempID\" ");
		while($res=pg_fetch_array($qry_menu)){
		?>
        <option value="<?php echo trim($res["tempID"]);?>" <?php if($ad_idmenu == trim($res["tempID"])){ echo "selected";}?>><?php echo trim($res["tempName"]); ?></option>
        <?php
		}
		?>
    </select>
	<br/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;กลุ่มผู้ใช้ :
	<select name="a_gp" id="a_gp" onChange="this.form.submit();">
		<option value="" <?php if($a_gp == ""){ echo "selected";}?>>-----ทั้งหมด-----</option>
		<?php
		$qry_gpuser=pg_query("select dep_id , dep_name from department order by dep_name");
		while($resg=pg_fetch_array($qry_gpuser)){
		?>
		  <option value="<?php echo $resg["dep_id"]; ?>" <?php if($a_gp == $resg["dep_id"]){ echo "selected";}?>><?php echo $resg["dep_name"]; ?></option>
		<?php
		 }
		?>  
	</select>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="" onclick="window.close();">x ปิดหน้านี้</a>
  <hr />
</div>
</form>
<div id="contentpage" style="height:auto;">
<form method="post" name="form2" action="process_usermenu.php">
 <div class="style5" style="width:auto;  padding-left:10px;">
  <?php
  if($a_gp==""){
	$qry_user=pg_query("select * from \"Vfuser\" order by user_group,status_user desc");
  }else{
	$qry_user=pg_query("select * from \"Vfuser\" where \"user_group\"='$a_gp' order by user_group,status_user desc");
  } 
  ?>
  <table width="778" border="0" style="background-color:#EEEDCC;">
  <tr>
    <td colspan="7" style="text-align:center;height:50px;">
		
		<input type="submit"  value="บันทึก" onclick="return checkdata()"/>
	</td>
  </tr>
  <tr style="background-color:#D0DCA0" align="center">
    <td width="26">No.</td>
    <td width="84">ID</td>
    <td width="130">username</td>
    <td width="239">ชื่อ - นามสกุล </td>
    <td width="63">กลุ่มผู้ใช้</td>
    <td width="56">office</td>
    <td width="102"><a href="#" onclick="javascript:selectAll('cid');"><u>ทั้งหมด</u></a></td>
  </tr>
  <?php
  $a=0;
  while($res=pg_fetch_array($qry_user))
  {
   $a++;
   	$id_user = $res["id_user"];
  ?>
  <tr style="background-color:#EEF2DB">
    <td align="center"><?php echo $a; ?></td>
    <td align="center"><?php echo $res["id_user"]; ?></td>
    <td><?php echo $res["username"]; ?></td>
    <td><?php echo $res["fullname"]; ?></td>
    <td><?php echo $res["user_group"]; ?></td>
    <td align="center"><?php echo $res["office_id"]; ?></td>
	<?php
	//หาว่า user นี้ใช้งาน Template นี้หรือไม่โดยหาเมนูใน Template ก่อน
	if($ad_idmenu != ""){
		$query_menu=pg_query("select * from \"nw_templateDetail\" where \"tempID\"='$ad_idmenu'");
		$numrows_menu=pg_num_rows($query_menu);
		$nub_menu=0;
		while($res_menu=pg_fetch_array($query_menu)){
			$id_menu=$res_menu["id_menu"];
			$list_menu=pg_query("select * from f_usermenu A LEFT OUTER JOIN f_menu B 
										 on A.id_menu=B.id_menu
										 where  A.id_user='$id_user' and A.id_menu='$id_menu' and A.\"status\"='TRUE' ");
			$num_list=pg_num_rows($list_menu);
			if($num_list>0){
				$nub_menu++;
			}
		}
		if($nub_menu==$numrows_menu){
			$status_use="t";
		}else{
			$status_use="f";
		}
	}
	?>
    <td align="center"><input type="checkbox" id="cid" name="cid[]" <?php if($status_use == 't'){ echo "checked";}?> value="<?php echo $id_user;?>"></td>
  </tr>
  <?php
  }
  ?>
  <input type="hidden" name="ad_idmenu" value="<?php echo $ad_idmenu;?>">
<input type="hidden" name="a_gp" value="<?php echo $a_gp;?>">
  <tr>
    <td colspan="7" style="text-align:center;height:50px;">
		
		<input type="submit"  value="บันทึก" onclick="return checkdata()"/>
	</td>
  </tr>
</table>
</div>
</form>
<div id="footerpage"></div>
</div>
</div>
</div>
</body>
</html>

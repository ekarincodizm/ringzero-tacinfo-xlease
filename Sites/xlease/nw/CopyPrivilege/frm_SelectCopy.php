<?php
include("../../config/config.php");

$user_origin = $_POST["user_origin"];
$type_copy = $_POST["type_copy"];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>จัดการผู้ใช้</title>
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
function checkdata() {
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var noErrors = theMessage

	if (document.getElementById("type1").checked || document.getElementById("type2").checked){} else{
	theMessage = theMessage + "\n -->  กรุณาเลือกแบบการคัดลอกสิทธิ";
	}
	
	/*if (document.getElementById("cid").checked){} else{
	theMessage = theMessage + "\n -->  กรุณาเลือกพนักงาน";
	}*/

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
<form method="post" name="form1" action="Process_Copy.php">
<div id="swarp" style="width:800px; height:auto; margin-left:auto; margin-right:auto;">

<div id="warppage" style="width:800px; height:auto;">
<div id="headerpage" style="height:10px; text-align:center"></div>
<div><center><h4>เลือกแบบการคัดลอกสิทธิ์</h4></center></div>
<div id="contentpage" style="height:auto;" align="center">
<input type="radio" name="type_copy" id="type1" value="c1" <?php if($type_copy == "c1"){echo "checked=\"checked\"";} ?> >เหมือนกับสิทธิต้นฉบับ &nbsp; &nbsp;
<input type="radio" name="type_copy" id="type2" value="c2" <?php if($type_copy == "c2"){echo "checked=\"checked\"";} ?> >เพิ่มสิทธิที่ต้นฉบับมี
</div>
</div>

<div id="warppage" style="width:800px; height:auto;">
<div id="headerpage" style="height:10px; text-align:center"></div>
<div><center><h3>เลือกพนักงานที่จะรับมอบสิทธิ์</h3></center></div>
<div id="contentpage" style="height:auto;">
 
 <div class="style5" style="width:auto;  padding-left:10px;">
  <?php
  $qry_user=pg_query("select * from \"Vfuser\" order by user_group,status_user desc");
  
  
  ?>
  <table width="778" border="0" style="background-color:#EEEDCC;">
  <tr style="background-color:#D0DCA0">
    <td width="26">No.</td>
    <td width="84">ID</td>
    <td width="130">username</td>
    <td width="239">ชื่อ - นามสกุล </td>
    <td width="63">กลุ่มผู้ใช้</td>
    <td width="56">office</td>
    <td width="102">status</td>
    <td width="44">เลือก</td>
  </tr>
  <?php
  $a=0;
  while($res=pg_fetch_array($qry_user))
  {
	$user_copy = $res["id_user"];
   $a++;
  ?>
  <tr style="background-color:#EEF2DB">
    <td><?php echo $a; ?></td>
    <td><?php echo $res["id_user"]; ?></td>
    <td><?php echo $res["username"]; ?></td>
    <td><?php echo $res["fullname"]; ?></td>
    <td><?php echo $res["user_group"]; ?></td>
    <td><?php echo $res["office_id"]; ?></td>
    <td><?php 
	     if($res["status_user"]=='t')
		 {
		   echo "ใช้งานได้";
		 }
		 else
		 {
		   echo "ระงับใช้งาน";
		 } 
	    ?>    </td>
    <td align="center"><input type="checkbox" id="cid" name="cid[]" value="<?php echo $user_copy;?>"></td>
  </tr>
  <?php
  }
  ?>
  <tr>
		<input type="hidden" name="user_origin" value="<?php echo "$user_origin"; ?>">
		<td colspan="8" style="text-align:center;"><input type="submit" value="ตกลง" onclick="return checkdata()" /></td>
	</form>
    </tr>
</table>

 
 

</div>
<div id="footerpage"></div>
</div>
</div>
<form method="post" name="form2" action="frm_Index.php">
	<input type="hidden" name="id_user" value="<?php echo $user_origin; ?>">
	<input type="submit" value="BACK" />
</form>
</div>
</body>
</html>
<?php
session_start();
$_SESSION["av_iduser"];
include("../../config/config.php");
$value  = $_POST['value'];
if($value == "post"){
	$ad_annId = $_POST['ad_annId'];
	$a_gp = $_POST['a_gp'];
	
	
}else{
	$ad_annId = $_GET['ad_annId'];
	$a_gp = $_GET['a_gp'];
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
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<title>ตั้งค่าผู้รับข่าวสาร</title>
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

	if (document.form.ad_annId.value=="") {
	theMessage = theMessage + "\n -->  กรุณาเลือกประกาศ";
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

function shownewbie(){
		$('#textnewemp').show();
	
}
function hidenewbie(){
		$('#textnewemp').hide();
	
}
$(document).ready(function(){

		$("#chknewemp").click(function(){
			if($('#chknewemp') .attr( 'checked')==true){
				$('#textnewemp').show();
			}else{
				$('#textnewemp').hide();
			}
		});
});		
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
	เลือกประกาศ :
	<select name="ad_annId" id="ad_annId" onChange="this.form.submit();">  
		<option value="" <?php if($ad_annId == ""){ echo "selected";}?>>-------------------เลือก-------------------</option><?php
		//แสดงเฉพาะเมนูที่ใช้งาน
		$qry_menu=pg_query("select * from \"nw_annoucement\" where \"statusApprove\"='FALSE' order by \"keyDate\" DESC");
		while($res=pg_fetch_array($qry_menu)){
			$annId=$res["annId"];
			$annTitle=str_replaceout($res["annTitle"]);
			$keyDate=$res["keyDate"];
			$y=substr($keyDate,0,4);
			$y=$y+543;
			$m=substr($keyDate,5,2);
			$d=substr($keyDate,8,2);
			$keyDate=$d."-".$m."-".$y;
		?>
        <option value="<?php echo trim($res["annId"]);?>" <?php if($ad_annId == trim($res["annId"])){ echo "selected";}?>><?php echo $keyDate."::".trim(str_replaceout($res["annTitle"])); ?></option>
        <?php
		}
		?>
    </select>
	กลุ่มผู้ใช้ :
	<select name="a_gp" id="a_gp" onChange="this.form.submit();">
		<option value="" <?php if($a_gp == ""){ echo "selected";}?>>-----ทั้งหมด-----</option>
		<?php
		$qry_gpuser=pg_query("select * from department");
		while($resg=pg_fetch_array($qry_gpuser)){
		?>
		  <option value="<?php echo $resg["dep_id"]; ?>" <?php if($a_gp == $resg["dep_id"]){ echo "selected";}?>><?php echo $resg["dep_name"]; ?></option>
		<?php
		 }
		?>  
	</select>
	
</form>

</div>
<form method="post" name="form2" action="process_usermenu.php">
<div class="style1" id="menu" style="height:30px; padding-left:10px; padding-top:0px; padding-right:10px;">
<?php 
if($ad_annId != ""){
	$newbie_seesql=pg_query("select * from \"nw_annouceuser_newbie\" where \"annId\"='$ad_annId' and \"statusAccept\" = '0'");
		$newbie_seere=pg_fetch_array($newbie_seesql);
		$depid = $newbie_seere['dep_id'];
		if(pg_num_rows($newbie_seesql) > 0){ $chknewemp = "checked";}
}
?>


<input type="checkbox" name="chknewemp" id="chknewemp" value="1" <?php echo $chknewemp ?>>ประกาศให้พนักงานเพิ่มใหม่ทราบด้วย
<span id="textnewemp">|| ประกาศให้พนักงานใหม่ทราบในกลุ่มผู้ใช้ : 
		<select name="newempdep" id="newempdep" >
		<option value="" <?php if($newempdep == ""){ echo "selected";}?>>-----ทั้งหมด-----</option>
		<?php
		$qry_gpuser1=pg_query("select * from department");
		while($resg1=pg_fetch_array($qry_gpuser1)){
		?>
		  <option value="<?php echo $resg1["dep_id"]; ?>" <?php if($depid == $resg1["dep_id"]){echo "selected";} ?>><?php echo $resg1["dep_name"]; ?></option>
	<?php
		 }
if(pg_num_rows($newbie_seesql) > 0){echo "<script type='text/javascript'>shownewbie()</script>";}else{echo "<script type='text/javascript'>hidenewbie()</script>";}				
		?>  
		</select>
</span>

<hr />
</div>

<div id="contentpage" style="height:auto;">

 <div class="style5" style="width:auto;  padding-left:10px;">
  <?php
  if($a_gp==""){
	$qry_user=pg_query("select * from \"Vfuser\" order by user_group,status_user desc");
  }else{
	$qry_user=pg_query("select * from \"Vfuser\" where \"user_group\"='$a_gp' order by user_group,status_user desc");
  } 
  ?>
  <table width="778" border="0" style="background-color:#EEEDCC;">
  <tr style="background-color:#D0DCA0" align="center">
    <td width="26">No.</td>
    <td width="84">ID</td>
    <td width="130">username</td>
    <td width="239">ชื่อ - นามสกุล </td>
	<td width="">ชื่อเล่น</td>
    <td width="63">กลุ่มผู้ใช้</td>
    <td width="56">office</td>
    <td width=""><a href="#" onclick="javascript:selectAll('cid');"><u>ทั้งหมด</u></a></td>
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
	<td><?php echo $res["nickname"]; ?></td>
    <td><?php echo $res["user_group"]; ?></td>
    <td align="center"><?php echo $res["office_id"]; ?></td>
	<?php
	//หาว่า user นี้ใช้งานประกาศ นี้หรือไม่โดย
	if($ad_annId != ""){
		$list_menu=pg_query("select * from \"nw_annouceuser\" where \"id_user\"='$id_user' and \"annId\"='$ad_annId' and \"statusAccept\"!='0' ");
		$num_list=pg_num_rows($list_menu);
		if($num_list>0){
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
  <tr>
    <td colspan="8" style="text-align:center;height:50px;">
		<input type="hidden" name="ad_annId" value="<?php echo $ad_annId;?>">
		<input type="hidden" name="a_gp" value="<?php echo $a_gp;?>">
		<input type="submit"  value="บันทึก" onclick="return checkdata()"/><input type="button" value="BACK" onclick="window.location='frm_Index.php'" />
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

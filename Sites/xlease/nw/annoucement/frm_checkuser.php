<?php
session_start();
$_SESSION["av_iduser"];
include("../../config/config.php");
$annId  = $_GET['annId'];

if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}

$qry_menu=pg_query("select * from \"nw_annoucement\" where \"annId\"='$annId'");
if($res=pg_fetch_array($qry_menu)){
	$annId=$res["annId"];
	$annTitle=str_replaceout($res["annTitle"]);
	$keyDate=$res["keyDate"];
	$y=substr($keyDate,0,4);
	$y=$y+543;
	$m=substr($keyDate,5,2);
	$d=substr($keyDate,8,2);
	$keyDate=$d."-".$m."-".$y;
}



	$newbie = pg_query("SELECT * FROM nw_annouceuser_newbie where \"annId\" = '$annId'");
	$renewbie = pg_fetch_array($newbie);
	$rownewbie = pg_num_rows($newbie);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<title>ตรวจสอบผู้รับข่าวสาร</title>
<script type="text/javascript" src="autocomplete.js"></script>
<link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
<link type="text/css" rel="stylesheet" href="act.css"></link>  
<style type="text/css">
    #warppage
	{
	width:800px;
	margin-left:auto;
	margin-right:auto;
	
	min-height: 5em;
	background: rgb(255, 255, 255);
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
$(document).ready(function(){


<?php if($rownewbie > 0){ ?>
$('#textnewemp').show();
<?php }else{ ?>
$('#textnewemp').hide();
<?php } ?>
		$("#chknewemp").click(function(){
			if($('#chknewemp') .attr( 'checked')==true){
				$('#textnewemp').show();
			}else{
				$('#textnewemp').hide();
			}
		});
});	
</script>
<style type="text/css">
.weightfont{
	font-weight:bold
}
</style>
</head>

<body>
<div id="swarp" style="width:800px; height:auto; margin-left:auto; margin-right:auto;">
<div id="warppage" style="width:800px; height:auto;">
<div id="headerpage" style="height:5px; text-align:center"><h2>ตรวจสอบผู้รับข่าวสาร</h2></div>

<div class="style1" id="menu" style="padding-left:10px; padding-top:5px; padding-right:10px;">
	<h3>เรื่อง :<?php echo $annTitle;?></h3>
</div>
<div id="contentpage" style="height:auto;">
<form method="post" name="form2" action="process_approve.php">

<?php
	if($rownewbie > 0){
 ?>
<div class="style1" id="menu" style="height:30px; padding-left:10px; padding-top:0px; padding-right:10px;">
<input type="checkbox" name="chknewemp" id="chknewemp" value="1" <?php if($rownewbie > 0 ){ echo "checked";}?>>ประกาศให้พนักงานเพิ่มใหม่ทราบด้วย
<span id="textnewemp">|| ประกาศให้พนักงานใหม่ทราบในกลุ่มผู้ใช้ : 
		<select name="newempdep" id="newempdep" >
		<option value="" <?php if($renewbie['dep_id'] == "allemp"){ echo "selected";}?>>-----ทั้งหมด-----</option>
		<?php
		$qry_gpuser1=pg_query("select * from department");
		while($resg1=pg_fetch_array($qry_gpuser1)){
		?>
		  <option value="<?php echo $resg1["dep_id"]; ?>" <?php if($renewbie['dep_id'] == $resg1["dep_id"]){ echo "selected";}?>><?php echo $resg1["dep_name"]; ?></option>
		<?php
		 }
		?>  
		</select>
</span>

<hr />
</div>
<?php } ?>
<div class="style5" style="width:auto;  padding-left:10px;">
  <table width="778" border="0" style="background-color:#D5F2FD;" cellspacing="1">
  <tr style="background-color:#0E98DA" align="center" height="25" class="weightfont">
    <td width="26">No.</td>
    <td width="84">ID</td>
    <td>username</td>
    <td>ชื่อ - นามสกุล </td>
	<td>ชื่อเล่น</td>
    <td>กลุ่มผู้ใช้</td>
    <td>office</td>
    <td><a href="#" onclick="javascript:selectAll('cid');"><u>ทั้งหมด</u></a></td>
  </tr>
  <?php
	$a=1;
	$list_user=pg_query("select * from \"nw_annouceuser\" a
	left join \"Vfuser\" b on a.\"id_user\" = b.\"id_user\" 
	left join \"department\" c on b.\"user_group\"=c.\"dep_id\" 
	left join \"f_department\" d on b.\"user_dep\" =d.\"fdep_id\"
	where a.\"annId\"='$annId' and \"statusAccept\"!='0' order by a.\"id_user\"");
	$numrows=pg_num_rows($list_user);
	while($res=pg_fetch_array($list_user)){
		$id_user = $res["id_user"];
		$fullname = $res["fullname"];
		if($numrows>0){
			$status_use="t";
		}else{
			$status_use="f";
		}
	?>
	<tr style="background-color:#E9F8FE">
		<td align="center"><?php echo $a; ?></td>
		<td align="center"><?php echo $res["id_user"]; ?></td>
		<td><?php echo $res["username"]; ?></td>
		<td><?php echo $res["fullname"]; ?></td>
		<td><?php echo $res["nickname"]; ?></td>
		<td><?php echo $res["dep_name"]; ?></td>
		<td><?php echo $res["fdep_name"];?></td>
		<td align="center"><input type="checkbox" id="cid" name="cid[]" <?php if($status_use == 't'){ echo "checked";}?> value="<?php echo $id_user;?>"></td>
	</tr>
  <?php
		$a++;
	}
	if($numrows==0){
		echo "<tr><td colspan=8 height=50 align=center bgcolor=#FFFFFF>ไม่พบรายชื่อ</td></tr>";
	}
  ?>
  <tr height="25">
    <td colspan="8" style="text-align:center;" bgcolor="#FFFFFF">
		<input type="hidden" name="annId" value="<?php echo $annId;?>">
		<input type="hidden" name="val" value="2">
		<?php
		if($numrows!=0){
		?>
		<input type="submit"  value="อนุมัติ" onclick="return checkdata()"/>
		<?php }?>
		<input type="button" value="BACK" onclick="window.location='approve_ann.php'" />
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

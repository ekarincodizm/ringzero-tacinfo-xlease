<?php
session_start();
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../../index.php");
    exit;
}
include("../../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>แก้ไขเลขบัตรประชาชน</title>
<link type="text/css" rel="stylesheet" href="../act.css"></link>

<link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../../post/fancybox/lib/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script type="text/javascript" src="../../../post/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
<script type="text/javascript" src="../../../post/fancybox/source/jquery.fancybox.js?v=2.0.6"></script>
<link rel="stylesheet" type="text/css" href="../../../post/fancybox/source/jquery.fancybox.css?v=2.0.6" media="screen" />
<link rel="stylesheet" type="text/css" href="../../../post/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.2" />
<script type="text/javascript" src="../../../post/fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.2"></script>
<link rel="stylesheet" type="text/css" href="../../../post/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.2" />
<script type="text/javascript" src="../../../post/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.2"></script>
<script type="text/javascript" src="../../../post/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.0"></script>	
<script type="text/javascript">
$(document).ready(function() {
	$(".fancybox-effects-a").fancybox({
					minWidth: 300,
				   maxWidth: 700,
				   'height' : '600',
				   'autoScale' : true,
				   'transitionIn' : 'none',
				   'transitionOut' : 'none',
				   'type' : 'iframe'
	});
});
</script>	
	
	
	
<style type="text/css">
    .mouseOut {
    background: #708090;
    color: #FFFAFA;
    }

    .mouseOver {
    background: #FFFAFA;
    color: #000000;
    }
</style>
   
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
<script type="text/javascript">
$(document).ready(function(){

    $("#CusID").autocomplete({
        source: "s_cusmix.php",
        minLength:1
    });
});
</script>

</head>

<body style="background-color:#ffffff; margin-top:0px;" onload="setfocus();">
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
	<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
		<h1 class="style4">แก้ไขเลขบัตรประจำตัวประชาชนลูกค้า</h1>
	</div>


	<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
		<div class="style3" style="background-color:#8B636C; width:auto; height:20px; padding-left:10px;">Thaiace group</div>
		<div class="style3" style="background-color:#CD919E; width:auto; height:20px; padding-left:10px;"></div>
		<div class="style5" style="width:auto; padding:10px;"><b>ค้นหาลูกค้าที่ต้องการแก้ไขเลขบัตร</b></div>
		<form method="post" action="frm_reiden.php">
			ค้นหารหัส, ชื่อ, นามสกุล, บัตรประชาชนลูกค้า :
			<input type="text" size="80" id="CusID" name="CusID" style="height:20;"/>
			<input name="h_id" type="hidden" id="h_id" value="" />
			<input type="submit" value="NEXT" />
			<input name="button" type="button" onclick="window.close()" value="CLOSE" />
		</form>
	</div>
</div>

<?php

$strSort = $_GET["sort"];
if($strSort == "")
{
	$strSort = "date";
}

$strOrder = $_GET["order"];
if($strOrder == "")
{
	$strOrder = "DESC";
}

		$sql1 = pg_query("SELECT \"CusID\", identity_same, identity_new, edittime, app_status, id_user, 
       date, app_user, app_date,docfile FROM \"Re_indentity_cus_temp\" order by \"$strSort\" $strOrder");
	  	$strNewOrder = $strOrder == 'DESC' ? 'ASC' : 'DESC';
		$rows = pg_num_rows($sql1);

?>



<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:100px;">
<?php

if($rows==0 || empty($rows)){
	echo "<p>";
	echo "<center><h3> ยังไม่มีการขอเปลี่ยนรหัสประจำตัวประชาชนของลูกค้า </h3></center>";
	echo "<hr width=\"450\">";
}else{	
?>	
	<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
		<div class="style3" style="background-color:#8B636C; width:auto; height:20px; padding-left:10px;">รายชื่อลูกค้า รอการอนุมัติ</div>
		<div class="style5" style="width:auto; padding:10px;"><b>ตารางแก้ไขเลขบัตรประชาชน</b></div>
		<div style="width: 800;">
		<center>
		<table frame="border" width="750" bgcolor="#F5F5F5">
		<tr bgcolor="#999999">	
			<th align="center"><a href='frm_index.php?sort=CusID&order=<?php echo $strNewOrder ?>'>ชื่อลูกค้า</th>
			<th align="center"><a href='frm_index.php?sort=identity_same&order=<?php echo $strNewOrder ?>'>หมายเลขบัตรเดิม</th>
			<th align="center"><a href='frm_index.php?sort=identity_new&order=<?php echo $strNewOrder ?>'>หมายเลขบัตรใหม่</th>
			<th align="center"><a href='frm_index.php?sort=id_user&order=<?php echo $strNewOrder ?>'>พนักงานที่เปลี่ยน</th>
			<th align="center"><a href='frm_index.php?sort=date&order=<?php echo $strNewOrder ?>'>วันเวลาที่เปลี่ยน</th>
			<th align="center"><a href='frm_approve.php?sort=docfile&order=<?php echo $strNewOrder ?>'>ไฟล์แนบ</th>
			<th align="center"><a href='frm_index.php?sort=app_status&order=<?php echo $strNewOrder ?>'>สถานะการอนุมัติ</th>
		</tr>
		
<?php
	$i=0;
		while($re1 = pg_fetch_array($sql1)){
			$iduser = $re1['id_user'];
			$cusid = $re1['CusID'];
			$sql2 = pg_query("SELECT  \"A_FIRNAME\",\"A_NAME\",\"A_SIRNAME\" FROM \"Fa1\" where \"CusID\" = '$cusid'");
			$re2 = pg_fetch_array($sql2);
			
			$sql3 = pg_query("SELECT  \"fullname\" FROM \"Vfuser\" where \"id_user\" = '$iduser'");
			$re3 = pg_fetch_array($sql3);
			
			
			$cusname = $re2['A_FIRNAME']." ".$re2['A_NAME']." ".$re2['A_SIRNAME'];
			
			$i++;
			if($i%2==0){
				echo "<tr bgcolor=#FFFFFF onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFFFFF';\" align=center>";
			}else{
				echo "<tr bgcolor=#FFFFFF onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFFFFF';\" align=center>";
			}	
?>		
		
			<td align="center"><?php echo $cusname ?></td>
			<td align="center"><?php echo $re1['identity_same']; ?></td>
			<td align="center"><?php echo $re1['identity_new']; ?></td>
			<td align="center"><?php echo $re3['fullname']; ?></td>
			<td align="center"><?php echo $re1['date']; ?></td>
			<td align="center">
		<?php if($re1['docfile'] != ""){ ?>	
			<a class="fancybox-effects-a" href="<?php echo $re1['docfile']; ?>" title="<?php echo $cusname?>"><u>ไฟล์แนบ</u></a>
		<?php }else{ ?>
			-
		<?php } ?>	
			</td>
<?php if($re1['app_status'] == 1){ $status = 'รออนุมัติ';}else if($re1['app_status'] == 2){ $status = 'อนุมัติแล้ว';}else if($re1['app_status'] == 3){ $status = 'ปฎิเสธ';} ?>			
			<td align="center"><?php echo $status; ?></td>
		</tr>	
<?php } ?>				
		</table>
		
		<br><br><br>
		<div class="style3" style="background-color:#8B636C; text-align:left; width:auto; height:20px; padding-left:10px;">รายชื่อผู้มีิสิทธิทำรายการแก้ไขเลขบัตรประชาชนที่มีอยู่แล้วได้</div>
		<div class="style5" style="width:auto; padding:10px; text-align:left;"><b>ตารางรายชื่อผู้มีิสิทธิทำรายการแก้ไขเลขบัตรประชาชนที่มีอยู่แล้วได้</b></div>
		<table frame="border" width="750" bgcolor="#F5F5F5">
		<tr bgcolor="#999999">
			<th align="center"></th>
			<th align="center">คำนำหน้า</th>
			<th align="center">ชื่อ</th>
			<th align="center">นามสกุล</th>
		</tr>
		
<?php
		$qry_user = pg_query("select * from \"fuser\" where \"emplevel\" <= '1' order by \"fname\", \"lname\" ");
		
		$i=0;
		while($res_user = pg_fetch_array($qry_user))
		{
			$title_user = $res_user['title'];
			$fname_user = $res_user['fname'];
			$lname_user = $res_user['lname'];
			
			$i++;
			if($i%2==0){
				echo "<tr bgcolor=#FFFFFF onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFFFFF';\" align=center>";
			}else{
				echo "<tr bgcolor=#FFFFFF onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFFFFF';\" align=center>";
			}	
?>		
		
			<td align="center"><?php echo $i ?></td>
			<td align="left"><?php echo $title_user; ?></td>
			<td align="left"><?php echo $fname_user; ?></td>
			<td align="left"><?php echo $lname_user; ?></td>
		</tr>	
<?php } ?>				
		</table>
		
		</center>
		</div>
<?php } ?>		
	</div>
</div>
</body>
</html>

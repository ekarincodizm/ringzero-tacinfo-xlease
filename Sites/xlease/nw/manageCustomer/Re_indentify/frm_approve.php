<?php
session_start();
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../../index.php");
    exit;
}
include("../../../config/config.php");

$iduser = $_SESSION["av_iduser"];
$chksqluser = pg_query("SELECT emplevel FROM fuser where id_user='$iduser'");
$rechkuser = pg_fetch_array($chksqluser);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>อนุมัติเปลี่ยนเลขบัตรประชาชน</title>
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

function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}

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
</head>
<body style="background-color:#ffffff; margin-top:0px;" onload="setfocus();">
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
	<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
			
	</div>


	<div id="login"  style="height:95px; width:900px; text-align:left; margin-left:auto; margin-right:auto;">
		<div class="style3" style="background-color:#8B636C; width:auto; height:20px; padding-left:10px;">Thaiace group</div>
		<div class="style3" style="background-color:#CD919E; width:auto; height:25px; padding-left:10px;"></div>
		<div class="style3" style="background-color:#EEA9B8; width:auto; height:50px; text-align:center;"><font color="black" size="6px;">อนุมัติขอเปลี่ยนเลขบัตรประชาชน</font></div>
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

		$sql1 = pg_query("SELECT reiden_pk,\"CusID\", identity_same, identity_new, edittime, app_status, id_user, 
       date, app_user, app_date,docfile FROM \"Re_indentity_cus_temp\" order by \"$strSort\" $strOrder");
	  	$strNewOrder = $strOrder == 'DESC' ? 'ASC' : 'DESC';
		$rows = pg_num_rows($sql1);
if($rows==0 || empty($rows)){
	echo "<p>";
	echo "<center><h3> ไม่มีการขอเปลี่ยนรหัสประจำตัวประชาชนของลูกค้า </h3></center>";
	echo "<hr width=\"450\">";
}else{		
?>
<form name="frm" method="POST">

		<center>
<table frame="border" width="900" bgcolor="#F5F5F5">
		<tr bgcolor="#999999">	
			<th align="center"><a href='frm_approve.php?sort=CusID&order=<?php echo $strNewOrder ?>'>ชื่อลูกค้า</a></th>
			<th align="center"><a href='frm_approve.php?sort=identity_same&order=<?php echo $strNewOrder ?>'>หมายเลขบัตรเดิม</a></th>
			<th align="center"><a href='frm_approve.php?sort=identity_new&order=<?php echo $strNewOrder ?>'>หมายเลขบัตรใหม่</a></th>
			<th align="center"><a href='frm_approve.php?sort=id_user&order=<?php echo $strNewOrder ?>'>พนักงานที่เปลี่ยน</a></th>
			<th align="center"><a href='frm_approve.php?sort=date&order=<?php echo $strNewOrder ?>'>วันเวลาที่เปลี่ยน</a></th>
			<th align="center"><a href='frm_approve.php?sort=docfile&order=<?php echo $strNewOrder ?>'>ไฟล์แนบ</a></th>
			<th align="center"><a href='frm_approve.php?sort=app_status&order=<?php echo $strNewOrder ?>'>สถานะการอนุมัติ</a></th>
<?php if($rechkuser['emplevel'] <= 3){ ?>			
			<th align="center">เลือก</th>
<?php } ?>				
		</tr>
<?php
		$i = 0;
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

			<td align="left"><a style="cursor:pointer;" onclick="javascipt:popU('../../search_cusco/index.php?cusid=<?php echo $cusid; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=750');"><font color="#FF1493"><u>
			<?php echo "($cusid)"; ?></u></font></a><?php echo " ".$cusname ?></td>
			<td align="left"><?php echo $re1['identity_same']; ?></td>
			<td align="left"><?php echo $re1['identity_new']; ?></td>
			<td align="left"><?php echo $re3['fullname']; ?></td>
			<td align="center"><?php echo $re1['date']; ?></td>
<?php if($re1['app_status'] == 1){ $status = 'รออนุมัติ';}else if($re1['app_status'] == 2){ $status = 'อนุมัติแล้ว';}else if($re1['app_status'] == 3){ $status = 'ปฎิเสธ';} ?>
			<td align="center">
				<?php if($re1['docfile'] != ""){ ?>
					<a onclick="javascript:popU('<?php echo $re1['docfile']; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740')"
					style=cursor:pointer>
					<font color="#0000FF"><u>ไฟล์แนบ</u></a>
				<?php }else{ ?>
					 -
				<?php } ?>
			</td>
			<td align="center"><?php echo $status; ?></td>
	<?php if($re1['app_status'] == 1 && $rechkuser['emplevel'] <= 3){ ?>			
			<td align="center"><input type="checkbox" name="changeiden[]" value="<?php echo $re1['reiden_pk'];?>"></td>
	<?php }else{ echo "<td align=\"center\">-</td>"; } ?>				
		</tr>	
<?php } ?>	
		<tr>
			<td colspan="7" align="center"><br><hr width="600"></td>
		</tr>
<?php if($rechkuser['emplevel'] <= 3){ ?>		
		<tr>
			<td colspan="8" align="right">
				<input type="submit" value=" อนุมัติ " onclick="javascript:Asubmit(this.form);">
				<input type="submit" value=" ปฏิเสธ" onclick="javascript:Bsubmit(this.form);">
			</td>
		</tr>		
<?php } ?>		
		</table>
		
		
</center>
</form>
	
<?php } ?>
	</div>
</div>
</body>
</html>
<script type="text/javascript">
function Asubmit(frm)
{
		frm.action="process_approve_yes.php";
		frm.submit();

}

function Bsubmit(frm)
{
frm.action="process_approve_no.php";
frm.submit();
}
</script>
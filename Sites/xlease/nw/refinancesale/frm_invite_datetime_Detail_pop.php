<?php
session_start();
include("../../config/config.php");

$get_userid = $_SESSION["av_iduser"];
 $IDNO = $_GET["IDNO"];
 $inviteID = $_GET['inviteID'];

$sql1 = "SELECT \"inviteID\", \"IDNO\", \"CusID\", asset_id, \"CusTel\", \"KeyDate\", \"inviteDate\", 
       id_user, \"ActiveMatch\", invite_detail FROM refinance.invite where \"inviteID\" = '$inviteID'";
$query1 = pg_query($sql1);
$re1 = pg_fetch_array($query1);	   

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>บันทึกการชวนลูกค้า</title>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(function(){
    $("#tabs").tabs();
    $("#tabs").tabs('<?php echo $IDNO ?>');

});

function detail(num){
	$("#testdate").text(num);
	
}
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>

<style type="text/css">
.ui-tabs{
    font-family:tahoma;
    font-size:12px
}
</style>

<style type="text/css">
body {
    font-family:tahoma;
    color : #333333;
    font-size:12px;
}
.title_top{
    font-family: tahoma;
    font-size:19px;
    font-weight: bold;
    margin: 0;
    padding: 0 0 3px 0;
    text-align: right;
}
TEXTAREA,SELECT,INPUT{
	font-family: Tahoma;
	color: #3A3A3A;
}
H1 {
    font-size: 18px;
}
.title {
    text-align: center;
}
.TextTitle{
    color: #006600;
    font-size: 11px;
    font-weight: bold;
}
</style>

</head>
<body>

<div class="title_top">ข้อมูลการชวนลูกค้า</div>

<div id="tabs"> <!-- เริ่ม tabs -->
<ul>
<?php

echo "<li><a href=\"#tabs-$ii\">$IDNO</a></li>";

?>
</ul>


<div id="tabs-<?php echo $get_idno; ?>">
<div style="background-color:<?php echo $bgcolor; ?>">

<div align="right" style="padding-top:5px; padding-bottom:5px;">
<img src="icoPrint.png" border="0" width="17" height="14">&nbsp;<a href="" onclick="javascript:popU('frm_invite_datetime_Detail_print.php?IDNO=<?php echo $re1['IDNO']; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=750')" >พิมพ์ข้อมูลทั้งหมด</a>
</div>

<fieldset><legend><b>ชวนลูกค้า</b></legend>
<?php
$qry_cn=pg_query("select \"IDNO\",\"CusID\",\"full_name\",\"C_REGIS\",\"asset_id\" from \"UNContact\"  WHERE (\"IDNO\"='$IDNO')");
$res_cn=pg_fetch_array($qry_cn);

$regis = $res_cn["C_REGIS"]; 
$asset_id = $res_cn["asset_id"];
$get_cusid = $res_cn["CusID"];
?>

<div style="float:left">ชื่อ : <?php echo $res_cn["full_name"];?></div>
<div style="float:right">วันที่บันทึก : <?php echo $re1["KeyDate"];?></div>
<div style="clear:both;">&nbsp;</div>
<div style="float:left">เลขที่สัญญา : <?php echo $re1["IDNO"];?></div>
<div style="float:right">ทะเบียนรถ : <?php echo $regis  ;?> </div>
<div style="clear:both;">&nbsp;</div>

<div style="padding-top:5px;">
<form name="form1" method="post" >
<span class="TextTitle">รายละเอียดการชวนลูกค้า</span><br />
<TEXTAREA NAME="invite_detail" ROWS="6" COLS="80" readonly="true"><?php echo $re1['invite_detail']; ?></TEXTAREA><br />
<div style="clear:both;">&nbsp;</div>
<div style="float:left">เบอร์โทรศัพท์ลูกค้าที่ติดต่อ : <?php echo $re1['CusTel']; ?></div>
	<?php list($inviteD,$inviteT)=explode(" ",$re1['inviteDate']); ?>
<div style="clear:both;">&nbsp;</div>	
<div style="float:left">วันเวลาที่ติดต่อ  : <?php echo $inviteD; ?>
		<td align="left">
		
		<?php	echo "เวลา : ". $inviteT ." น. "; ?>
		</div>
</div>
<div style="clear:both;">&nbsp;</div>
</fieldset>

<fieldset><legend><b>ข้อมูลการชวนอื่นๆ</b></legend>
<div style="background-color: #ffffff; padding: 2px">
<?php
$qry_fuc1=pg_query("select * from refinance.\"invite\" WHERE (\"IDNO\"='$IDNO') and \"inviteID\" != '$inviteID' ORDER BY \"inviteDate\" DESC"); 
$numr1=pg_num_rows($qry_fuc1);

if($numr1==0){ echo "<div align=center>- ไม่พบข้อมูล -</div>"; }
while($res_fuc1=pg_fetch_array($qry_fuc1)){
	$i++;
	$qry_fun=pg_query("select fullname from \"Vfuser\" WHERE (\"id_user\"='$res_fuc1[id_user]')");
	$res_fun=pg_fetch_array($qry_fun);
?>
	<?php $HIDNO =  $res_fuc1["IDNO"]; ?>
	<?php $HINID =  $res_fuc1["inviteID"]; ?>
    <div style="background-color: #C0C0C0">
        <div style="float:left; padding:2px">User : <b><?php echo $res_fun["fullname"]; ?></b></div>
        <div style="float:right; padding:2px">วันที่เจรจา : <b><?php echo $res_fuc1["inviteDate"]; ?></b></div>
        <div style="clear:both;"></div>
    </div>
    <div style="background-color: #F0F0F0; padding:2px"><?php echo $res_fuc1["invite_detail"]; ?></div>
	<div style="background-color: #D0D0D0; padding:2px; text-align:center"><a href="frm_invite_datetime_Detail_pop.php?IDNO=<?php echo $HIDNO; ?>&inviteID=<?php echo $HINID;?>" > แสดงข้อมูล </a></div>
    <div style="background-color: #FFFFFF; clear:both; height:10px"></div>
<?php
}
?>
</div>
</fieldset>
</div>
</div>

</div>
</body>
</html>
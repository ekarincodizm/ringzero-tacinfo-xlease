<?php
session_start();
$_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
include("../../config/config.php");
?>

<title> รายงานการติดตาม </title>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
};
</script>

<center><legend><h2>ตารางการแจ้งเตือน</h2></legend></center>

<div align="center">
<div class="style5" style="width:auto; height:40px; padding-left:10px;">
  <b></b>
<?php

$strSort = pg_escape_string($_GET["sort"]);
if($strSort == "")
{
	$strSort = "comID";
}

$strOrder = pg_escape_string($_GET["order"]);
if($strOrder == "")
{
	$strOrder = "ASC";
}

$date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$strSQL = "SELECT * FROM \"ContactHistory\" order by \"$strSort\" $strOrder ";
$objQuery = pg_query($strSQL);

$strNewOrder = $strOrder == 'DESC' ? 'ASC' : 'DESC';
$nrows=pg_num_rows($objQuery);
if($nrows != 0){
?>
<p></p>

<div style="width: 1100px; height: 600px; overflow: auto;">
<table width="1000" border="1">

  <tr bgcolor="#79BCFF" height="25" >
  
    <th width="190"> <div align="center"><a href='fu_report.php?sort=con_name&order=<?php echo $strNewOrder ?>'>ชื่อบริษัท </div></th>
    <th width="190"> <div align="center"><a href='fu_report.php?sort=con_name&order=<?php echo $strNewOrder ?>'>ชื่อการสนทนา</div></th>
	<th width="150"> <div align="center"><a href='fu_report.php?sort=con_date&order=<?php echo $strNewOrder ?>'>เวลาที่สนทนา </div></th>
    <th width="150"> <div align="center"><a href='fu_report.php?sort=tag_name&order=<?php echo $strNewOrder ?>'>ชื่อการติดตาม </div></th>
	<th width="150"> <div align="center"><a href='fu_report.php?sort=tag_datetime&order=<?php echo $strNewOrder ?>'>เวลาการติดตาม</div></th>
    <th width="198"> <div align="center"><a href='fu_report.php?sort=full_name&order=<?php echo $strNewOrder ?>'>ชื่อผู้ติดต่อ</div></th>   
    <th width="190"> <div align="center"><a href='fu_report.php?sort=tag_status&order=<?php echo $strNewOrder ?>'>สถานะการติดตาม</div></th>
	 <th width="190"> <div align="center">รายละเอียดสนทนา</div></th>
	 <th width="190"> <div align="center">รายละเอียดติดตาม</div></th>
  </tr>
<?php
while($results = pg_fetch_array($objQuery))
{
$conid=$results["conID"];
$tagid=$results["tagID"];
$tagname=$results["tag_name"];
$tagtime=$results["tag_datetime"];
$status1=$results["tag_status"];
if($tagid == ""){
	$tagname = 'ไม่มีการติดตาม';
	$tagtime = 'ไม่มีการติดตาม';
	$tagidd = "";
}else{
$tagidd=$tagid;
}
if($status1 == 0){
	$status= 'รอการติดตาม';
}else if($status1 == 1){
	$status= 'เลื่อนการติดตาม';
}else if($status1 == 2){
	$status= 'เสร็จสิ้น';
}else if($status1 == 3){
	$status='ยกเลิก';
}else{
	$status='NULL';
	}?>
  <tr>
	<td><div align="center"><?php echo $results["com_name"];?></div></td>
    <td><div align="center"><?php echo $results["con_name"];?></div></td>
    <td><div align="center"><?php echo $results["con_date"];?></div></td>
    <td><div align="center"><?php echo $tagname;?></div></td>
    <td><div align="center"><?php echo $tagtime;?></div></td>
	<td><div align="center"><?php echo $results["full_name"];?></div></td>
	<td><div align="center"><?php echo $status;?></div></td>
	<td><div align="center"><input type="button" name="bt_edit" id="bt_edit" value="ดูการสนทนา" onclick="javascript:popU('fu_conversation_data.php?CONTID=<?php echo $conid ?>','toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no')"></div></td>
	<td><div align="center"><input type="button" name="bt_edit" id="bt_edit" value="ดูการติดตาม" onclick="javascript:popU('fu_tag_data.php?TAGID=<?php echo $tagidd ?>','toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no')"></div></td>
  </tr>
<?php
}
?>
<tr>
<td colspan="9" bgcolor="#79BCFF"><div align="center"></div> มีทั้งหมด <?php echo $nrows; ?> รายการ</div></td>
</tr>
</table></div>
<?php }else{ 

echo "<hr width=850>";
	echo "<center><h1>ไม่พบข้อมูล</h1></center>";
}
?>
  </div>
</div>



        </td>
    </tr>
</table>
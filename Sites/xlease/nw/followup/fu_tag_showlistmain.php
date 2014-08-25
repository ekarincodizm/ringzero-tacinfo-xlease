<?php
session_start();
$_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}

include("../../config/config.php");
$userr = $_SESSION["av_iduser"];
$date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$comID = pg_escape_string($_GET['comID']);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
};


</script>
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
</head>

<body>

<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
        
<div style="float:left">&nbsp;</div>
<div style="clear:both;"></div>
<?php 
$null = "";
$objQuery = pg_query("SELECT * from \"ContactHistory\" where \"comID\" = '$comID' and \"tagID\" is not null");
$nrows = pg_num_rows($objQuery);
$results1 = pg_fetch_array($objQuery);


if($nrows){ 
?>
<center><legend><h2>ตารางการติดตามของบริษัท   <?php echo $results1["com_name"];?> </h2></legend></center>

<div align="center">
<div class="style5" style="width:auto; height:40px; padding-left:10px;">
  <b></b>

<p></p>
<?php
$objQuery2 = pg_query("SELECT * FROM \"ContactHistory\" fcon join \"fu_conversation_emp\" fcone 
on fcon.\"conID\" = fcone.\"conID\"

where 
(fcon.\"comID\" = '$comID' and fcon.\"tagID\" is not null  and fcone.\"id_user\" = '$userr')
 OR
(fcon.\"comID\" = '$comID' and fcon.\"tagID\" is not null  and fcone.\"id_user\" = 'allemp')

order by fcon.\"tagID\" DESC");
$nrows2=pg_num_rows($objQuery2);

?>
<div style="width: 1100px; height: 600px; overflow: auto;">
<table width="900" border="0">

  <tr bgcolor="#79BCFF" height="25" >
    <th width="190"><div align="center">รหัสการติดตาม</div></th>
    <th width="190"> <div align="center">ชื่อการติดตาม </div></th> 
	<th width="150"> <div align="center">การสนทนาที่เดียวข้อง</div></th>
	<th width="190"> <div align="center">ผู้ติดต่อ</div></th>
    <th width="198"> <div align="center">รายละเอียดการติดตาม</div></th>
	<th width="198"> <div align="center">สถานะการติดตาม</div></th>
	 <th width="190"> <div align="center">แก้ไข</div></th>
  </tr>
<?php
while($results2 = pg_fetch_array($objQuery2))
{
?>
  <tr>
	
	<td bgcolor="#70CCFF" onclick="javascript:popU('fu_tag_data.php?TAGID=<?php echo $results2["tagID"]; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">
	<div align="center"><?php echo $results2["tagID"];?></div></td>
    <td><div align="center"><?php echo $results2["tag_name"];?></div></td>
	<td bgcolor="#70CCFF" onclick="javascript:popU('fu_conversation_data.php?CONTID=<?php echo $results2["conID"]; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">
	<div align="center"><?php echo $results2["con_name"];?></div></td>
    <td bgcolor="#70CCFF" onclick="javascript:popU('fu_empcontact_data.php?empID=<?php echo $results2["empconID"]; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">
	<div align="center"><div align="center"><?php echo $results2["full_name"];?></div></td>
    <td><div align="center"><?php echo $results2["tag_detail"];?></div></td>
	
<?php
$status1=$results2['tag_status'];

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
    <td><div align="center"><?php echo $status;?></div></td>

	<td bgcolor="#70CCFF" onclick="javascript:popU('fu_tag_edit.php?TAGID=<?php echo $results2["tagID"]; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">
	<div align="center"> แก้ไข </div></td>
	
	<?php } ?>

  </tr>
<tr>
<td colspan="6" bgcolor="#79BCFF"><div align="center"></div>การติดตามที่มีสิทธิ์เห็นทั้งหมด <?php echo $nrows2; ?> รายการ</div></td>
<td  bgcolor="#79BCFF"><div align="center"></div><center><input type="button" id="btnadd" name="btnadd" value="เพิ่มการติดตาม" 
onclick="javascript:popU('fu_tag_add.php?empID=i&comid=<?php echo $comID ?>','toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no')"></div></td>
</center></tr>
</table>
</div>
<?php 
}else{

echo "<hr width=850>";
	echo "<center><h1>ไม่พบข้อมูล</h1></center>";

}
?>
  </div>
</div>



        </td>
    </tr>
</table>
<div id="panel4" style="padding-top: 10px;"></div>


</body>
</html>

<?php
session_start();
$_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}

include("../../config/config.php");

$date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$conID = pg_escape_string($_GET['conID']);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<head>

<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
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


<center><legend><h2>ตารางการติดตาม </h2></legend></center>

<div align="center">
<div class="style5" style="width:auto; height:40px; padding-left:10px;">
  <b></b>

<p></p>
<?php
$objQuery1 = pg_query("SELECT * FROM \"ContactHistory\"  where \"conID\" = '$conID' order by \"tagID\"");

$objQuery2 = pg_query("SELECT * FROM \"ContactHistory\"  where \"conID\" = '$conID' order by \"tagID\" DESC");

$nrows=pg_num_rows($objQuery2);
$results3 = pg_fetch_array($objQuery1);
$tagcheck=$results3["tagID"];
if($tagcheck != ""){
?>

<div style="width: 1000px; height: 600px; overflow: auto;"><table width="900" border="0">

  <tr bgcolor="#79BCFF" height="35" >
    <th width="100" ><div align="center">รหัสการติดตาม</div></th>
    <th width="190" > <div align="center">ชื่อการติดตาม</div></th>
	<th width="190" > <div align="center">บริษัทติดตาม</div></th>	
	<th width="250" > <div align="center">รายละเอียด</div></th>
	<th width="190" > <div align="center">เวลาที่ติดตาม</div></th>
    <th width="198" > <div align="center">สถานะการติดตาม</div></th>
   
  </tr>
<?php
while($results2 = pg_fetch_array($objQuery2))
{
$status1 =$results2["tag_status"];
?>
  <tr>

	<td bgcolor="#70CCFF" onclick="javascript:popU('fu_tag_data.php?TAGID=<?php echo $results2["tagID"]; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">		
	<div align="center"><?php echo $results2["tagID"];?></div></td>
	<td><div align="center"><?php echo $results2["tag_name"];?></div></td>
	<td><div align="center"><?php echo $results2["com_name"];?></div></td>
    <td><div align="center"><?php echo $results2["tag_detail"];?></div></td>
    <td><div align="center"><?php echo $results2["tag_datetime"];?></div></td>
<?php if($status1 == 0){
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

  </tr>
<?php
}
?>
<tr>
<td colspan="6" bgcolor="#79BCFF"><div align="center"></div> มีทั้งหมด <?php echo $nrows; ?> รายการ</div></td>
</tr>
</table></div>
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
<div id="panel5" style="padding-top: 10px;"></div>


</body>
</html>

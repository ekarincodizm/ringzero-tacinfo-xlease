<?php
session_start();
$_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}

include("../../config/config.php");

$date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$empID = pg_escape_string($_GET['EMPID']);
$id_user=$_SESSION["av_iduser"];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
<?php 
$objQuery = pg_query("SELECT * FROM \"fu_empcontact\" where \"empconID\" = '$empID'");
$results1 = pg_fetch_array($objQuery);

?>

<center><legend><h2>ตารางการสนทนาของ  <?php echo $results1["empcon_name"];?> </h2></legend></center>

<div align="center">
<div class="style5" style="width:auto; height:40px; padding-left:10px;">
  <b></b>

<p></p>
<?php
$objQuery2 = pg_query("SELECT *,fcon.\"id_user\" as iduser FROM \"fu_conversation\" fcon inner join \"fu_conversation_emp\" fconemp on fcon.\"conID\" = fconemp.\"conID\"  
where (fcon.\"empconID\" = '$empID' AND fconemp.\"id_user\" = '$id_user') OR (fcon.\"empconID\" = '$empID' AND fconemp.\"id_user\" = 'allemp')
order by fcon.\"conID\"");
$nrows=pg_num_rows($objQuery2);
?>

<table width="900" border="0">

  <tr bgcolor="#79BCFF" height="25" >
    <th width="100"><div align="center">รหัสการสนทนา</div></th>
    <th width="190"> <div align="center">ชื่อการสนทนา</div></th> 
	<th width="250"> <div align="center">รายละเอียด</div></th>
	<th width="190"> <div align="center">เวลาที่สนทนา</div></th>
    <th width="198"> <div align="center">พนักงานของ thaiace ที่สนทนาด้วย</div></th>
	<th width="190"> <div align="center">จำนวนครั้งที่ติดตาม</div></th>
   
  </tr>
<?php
while($results2 = pg_fetch_array($objQuery2))
{
$user =  $results2["iduser"];
$conID =  $results2["conID"];
	

		$objQuery4 = pg_query("SELECT count(\"tagID\")as count  FROM \"fu_tag\" where \"conID\" = '$conID'" );
		$nrows4=pg_num_rows($objQuery4);

	

$objQuery1 = pg_query("SELECT * FROM \"Vfuser\" where \"id_user\" = '$user'");
$results1 = pg_fetch_array($objQuery1)



?>
  <tr>
	
	<td bgcolor="#70CCFF" onclick="javascript:popU('fu_conversation_data.php?CONTID=<?php echo $results2["conID"]; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">	
	<div align="center"><?php echo $results2["conID"];?></div></td>
    <td><div align="center"><?php echo $results2["con_name"];?></div></td>
	<td><div align="center"><?php echo $results2["con_detail"];?></div></td>
    <td><div align="center"><?php echo $results2["con_date"];?></div></td>
    <td><div align="center"><?php echo $results1["fullname"];?></div></td>
	<?php while($results4 = pg_fetch_array($objQuery4)){
	$countcon =  $results4["count"];
	if($countcon == 0)
	{
		$countcon1 = 'ไม่มีประวัติการติดตาม';
	}else{

		$countcon1 = $countcon." ".'ครั้ง';
	} ?>
	<td bgcolor="#70CCFF" onclick="javascript:popU('fu_tag_showlist.php?conID=<?php echo $results2["conID"]; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">
	<div align="center"><?php echo $countcon1 ;?></div></td>
	<?php } ?>

  </tr>
<?php
}
?>
<tr>
<td colspan="6" bgcolor="#79BCFF"><div align="center"></div> คุณมีสิทธิ์เห็นทั้งหมด <?php echo $nrows; ?> รายการ</div></td>
</tr>
</table>
<?php 

?>
  </div>
</div>



        </td>
    </tr>
</table>
<div id="panel5" style="padding-top: 10px;"></div>


</body>
</html>

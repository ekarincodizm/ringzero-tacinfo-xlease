<?php
session_start();
$_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}

include("../../config/config.php");

$date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$comID1 = pg_escape_string($_GET['COMID']);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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
<?php 
$objQuery = pg_query("SELECT * FROM \"fu_company\"  where \"comID\" = '$comID1' ");
$results1 = pg_fetch_array($objQuery);

?>

<center><legend><h2>ตารางการสนทนา บริษัท   <?php echo $results1["com_name"];?> </h2></legend></center>

<div align="center">
<div class="style5" style="width:auto; height:40px; padding-left:10px;">
  <b></b>

<p></p>
<?php
$objQuery2 = pg_query("SELECT * FROM \"fu_conversation\"  where \"comID\" = '$comID1' order by \"conID\" DESC");
$nrows=pg_num_rows($objQuery2);
?>

<table width="900" border="0">

  <tr bgcolor="#79BCFF" height="25" >
    <th width="100"><div align="center">รหัสการสนทนา</div></th>
    <th width="190"> <div align="center">ชื่อการสนทนา</div></th> 
	<th width="250"> <div align="center">รายละเอียด</div></th>
	<th width="190"> <div align="center">เวลาที่สนทนา</div></th>
	<th width="198"> <div align="center">ผู้ติดต่อ</div></th>
    <th width="198"> <div align="center">พนักงานของ thaiace ที่สนทนาด้วย</div></th>
	<th width="190"> <div align="center">จำนวนครั้งที่ติดตาม</div></th>
   
  </tr>
<?php
while($results2 = pg_fetch_array($objQuery2))
{
$user =  $results2["id_user"];
$conID =  $results2["conID"];
$EMPID = $results2["empconID"];


		$objQuery4 = pg_query("SELECT count(\"tagID\")as count  FROM \"fu_tag\" where \"conID\" = '$conID'" );
		$objQuery5 = pg_query("SELECT * from \"fu_empcontact\" where \"empconID\" = '$EMPID'");
		$objQuery6 = pg_query("SELECT * FROM \"Vfuser\" where \"id_user\" = '$user'");
		
		$results5 = pg_fetch_array($objQuery5);		
		$results6 = pg_fetch_array($objQuery6);



?>
  <tr>
	
	<td bgcolor="#70CCFF" onclick="javascript:popU('fu_conversation_data.php?CONTID=<?php echo $results2["conID"]; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">	
	<div align="center"><?php echo $results2["conID"];?></div></td>
    <td><div align="center"><?php echo $results2["con_name"];?></div></td>
	<td><div align="center"><?php echo $results2["con_detail"];?></div></td>
    <td><div align="center"><?php echo $results2["con_date"];?></div></td>
	<td bgcolor="#70CCFF" onclick="javascript:popU('fu_empcontact_data.php?empID=<?php echo $results5["empconID"]; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">	
	<div align="center"><?php echo $results5["empcon_name"];?> <?php echo $results5["empcon_lname"];?></div></td>
    <td><div align="center"><?php echo $results6["fullname"];?></div></td>
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
<td colspan="7" bgcolor="#79BCFF"><div align="center"></div> มีทั้งหมด <?php echo $nrows; ?> รายการ</div></td>
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

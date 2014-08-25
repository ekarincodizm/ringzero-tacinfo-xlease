<?php
session_start();
$_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}

include("../../config/config.php");

$date=nowDateTime();//ดึงข้อมูลวันเวลาจาก server
$comID = pg_escape_string($_GET['COMID']);

$id_user=$_SESSION["av_iduser"];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
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
		$objQuery = pg_query("SELECT * FROM \"fu_company\" where \"comID\" = '$comID'");
		$results1 = pg_fetch_array($objQuery);
		$nrows = pg_num_rows($objQuery);
		
if($nrows){ 
?>
		<center><legend><h2>ตารางการสนทนาบริษัท  <?php echo $results1["com_name"];?> </h2></legend></center>

		<div align="center">
		<div class="style5" style="width:auto; height:40px; padding-left:10px;">
		  <b></b>

		<p></p>
		<?php
			
			$objQuery2 = pg_query("SELECT * FROM \"fu_conversation\" fcon join \"fu_conversation_emp\" fconemp  on fcon.\"conID\" = fconemp.\"conID\" 
			WHERE (fcon.\"comID\" = '$comID' AND fconemp.\"id_user\" = '$id_user') OR (fcon.\"comID\" = '$comID' 
			AND fconemp.\"id_user\" = 'allemp') order by fcon.\"conID\"");
	
		
		$nrows1=pg_num_rows($objQuery2);
		if($nrows1 ==0){
		
					echo "<hr width=850>";
					echo "<center><h1>ไม่พบข้อมูล</h1></center>";
					?>
					<input type="button" id="btnadd" name="btnadd" value="เพิ่มการสนทนา" onclick="javascript:popU('fu_conversation_add.php?comid=<?php echo $comID ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')">
<?php
		}else{
?>
<div style="width: 1100px; height: 600px; overflow: auto;">
<table width="900" border="0">

  <tr bgcolor="#79BCFF" height="25" >
    <th width="100"><div align="center">รหัสการสนทนา</div></th>
    <th width="190"> <div align="center">ชื่อการสนทนา</div></th> 
	<th width="250"> <div align="center">รายละเอียด</div></th>
	<th width="190"> <div align="center">เวลาที่สนทนา</div></th>
    <th width="198"> <div align="center">ผู้ติดต่อ</div></th>
	<th width="190"> <div align="center">จำนวนครั้งที่ติดตาม</div></th>
	<th width="190"> <div align="center">แก้ไข</div></th>
	<th width="190"> <div align="center">เพิ่มการติดตาม</div></th>
   
  </tr>
<?php
while($results2 = pg_fetch_array($objQuery2))
{
$empcon =  $results2["empconID"];
$conID =  $results2["conID"];
	

		$objQuery4 = pg_query("SELECT count(\"tagID\")as count  FROM \"fu_tag\" where \"conID\" = '$conID'" );
		$nrows4=pg_num_rows($objQuery4);

	

$objQuery1 = pg_query("SELECT * FROM \"fu_empcontact\" where \"empconID\" = '$empcon'");
$results1 = pg_fetch_array($objQuery1)



?>
  <tr>
	
	<td bgcolor="#70CCFF" onclick="javascript:popU('fu_conversation_data.php?CONTID=<?php echo $results2["conID"]; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">	
	<div align="center"><?php echo $results2["conID"];?></div></td>
    <td><div align="center"><?php echo $results2["con_name"];?></div></td>
	<td><div align="center"><?php echo $results2["con_detail"];?></div></td>
    <td><div align="center"><?php echo $results2["con_date"];?></div></td>
	<td bgcolor="#70CCFF" onclick="javascript:popU('fu_empcontact_data.php?empID=<?php echo $results2["empconID"]; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">	
    <div align="center"><?php echo $results1["empcon_name"]." ".$results1["empcon_lname"];?></div></td>
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
	<td bgcolor="#70CCFF" onclick="javascript:popU('fu_conversation_edit.php?CONTID=<?php echo $results2["conID"]; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">
	<div align="center"> แก้ไข </div></td>
	<td bgcolor="#70CCFF" onclick="javascript:popU('fu_tag_add.php?CONTID=<?php echo $results2["conID"]; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">
	<div align="center"> ติดตาม </div></td>
	<?php } ?>

  </tr>
<?php
}
?>
<tr>
<td colspan="7" bgcolor="#79BCFF"><div align="center"></div> คุณมีสิทธิ์เห็นทั้งหมด <?php echo $nrows1; ?> รายการ</div></td>
<td  bgcolor="#79BCFF"><div align="center"></div><center><input type="button" id="btnadd" name="btnadd" value="เพิ่มการสนทนา" 
onclick="javascript:popU('fu_conversation_add.php?comid=<?php echo $comID ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')"></div></td>
</center></tr>
</tr>
</table>
</div>
<?php 
}
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

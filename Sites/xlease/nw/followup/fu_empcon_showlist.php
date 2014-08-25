<?php
session_start();
$_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}

include("../../config/config.php");

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

$('#btnedit').click(function(){
		var aaaa = $("#emp_names_m").val();
        var brokenstring=aaaa.split("#");
        $("#panel4").load("fu_empcontact_edit.php?empID="+ brokenstring[0]);
    });
	
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
$objQuery = pg_query("SELECT * FROM \"fu_empcontact\" where \"comID\" = '$comID'");
$nrows = pg_num_rows($objQuery);

$objQuery1 = pg_query("SELECT * FROM \"fu_company\" where \"comID\" = '$comID'");
$results1 = pg_fetch_array($objQuery1);
$nrows3 = pg_num_rows($objQuery1);

if($nrows && $nrows3){ 
?>
<center><legend><h2>ตารางผู้ติดต่อของบริษัท  <?php echo $results1["com_name"];?> </h2></legend></center>

<div align="center">
<div class="style5" style="width:auto; height:40px; padding-left:10px;">
  <b></b>

<p></p>
<?php
$objQuery2 = pg_query("SELECT * FROM \"fu_empcontact\" where \"comID\" = '$comID'  order by \"empconID\"");
$nrows2=pg_num_rows($objQuery2);

?>

<div style="width: 1000px; height: 600px; overflow: auto;"><table width="900" border="0">

  <tr bgcolor="#79BCFF" height="25" >
    <th width="190"><div align="center">รหัสผู้ติดต่อ</div></th>
    <th width="190"> <div align="center">ชื่อ-นามสกุล </div></th> 
	<th width="150"> <div align="center">ตำแหน่ง</div></th>
	<th width="190"> <div align="center">เบอร์โทรศัพท์</div></th>
    <th width="198"> <div align="center">เบอร์มือถือ</div></th>
    <th width="150"> <div align="center">E-mail</div></th>
    <th width="190"> <div align="center">ประวัติการสนทนา</div></th>
	 <th width="190"> <div align="center">แก้ไข</div></th>
  </tr>
<?php
while($results2 = pg_fetch_array($objQuery2))
{
$emppid =  $results2["empconID"];

$objQuery1 = pg_query("SELECT count(\"conID\") as count FROM \"fu_conversation\" where \"empconID\" = '$emppid'");
$nrows1=pg_num_rows($objQuery1);

?>
  <tr>
	
	<td bgcolor="#70CCFF" onclick="javascript:popU('fu_empcontact_data.php?empID=<?php echo $results2["empconID"]; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">
	<div align="center"><?php echo $results2["empconID"];?></div></td>
    <td><div align="center"><?php echo $results2["empcon_name"];?> <?php echo $results2["empcon_lname"];?></div></td>
	<td><div align="center"><?php echo $results2["empcon_position"];?></div></td>
    <td><div align="center"><?php echo $results2["empcon_phone"];?></div></td>
    <td><div align="center"><?php echo $results2["empcon_moblie"];?></div></td>
    <td><div align="center"><?php echo $results2["empcon_email"];?></div></td>
	<?php while($results1 = pg_fetch_array($objQuery1)){
	$countcon =  $results1["count"];
	if($countcon == 0)
	{
		$countcon1 = 'ไม่มีประวัติการสนทนา';
	}else{

		$countcon1 = $countcon." ".'ครั้ง';
	} ?>
	<td bgcolor="#70CCFF" onclick="javascript:popU('fu_conversation_showlist.php?EMPID=<?php echo $results2["empconID"]; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">
	<div align="center"><?php echo $countcon1 ;?></div></td>
	<td bgcolor="#70CCFF" onclick="javascript:popU('fu_empcontact_edit.php?empID=<?php echo $results2["empconID"]; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">
	<div align="center"> แก้ไข </div></td>
	<?php } ?>

  </tr>
<?php
}
?>
<tr>
<td colspan="7" bgcolor="#79BCFF"><div align="center"></div> มีทั้งหมด <?php echo $nrows; ?> รายการ</div></td>
<td  bgcolor="#79BCFF"><div align="center"></div><center><input type="button" id="btnadd" name="btnadd" value="เพิ่มผู้ติดต่อ" 
onclick="javascript:popU('fu_empcontact_edit.php?empID=i&comid=<?php echo $comID ?>','toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no')"></div></td>
</center></tr>
</table></div>
<?php 
}else if(!$nrows && $nrows3){ 


echo "<hr width=850>";
	echo "<center><h1>ไม่พบข้อมูล</h1></center>";?>
	<div align="center" ></div><center><input type="button" id="btnadd" name="btnadd" value="เพิ่มพนักงาน" onclick="javascript:popU('fu_empcontact_edit.php?empID=i&comid=<?php echo $comID ?>','toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no')"></div>
</center></tr>
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

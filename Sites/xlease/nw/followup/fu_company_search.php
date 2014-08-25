<?php
session_start();
$_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
if($_SESSION["session_company_code"]=="AVL")
{
 $file_namepic="logo_av.jpg";
}
else
{
 //$file_namepic="logo_thaiace.jpg";
}
include("../../config/config.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ค้นหาบริษัทที่ติดต่อ</title>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){

    $("#com_names_m").autocomplete({
        source: "fu_listdatacompany.php",
        minLength:2
    });

    $('#btn1').click(function(){
		var aaaa = $("#com_names_m").val();
        var brokenstring=aaaa.split("#");
        $("#panel").load("fu_company_data.php?COMID="+ brokenstring[0]);
    });
	
	$('#btn2').click(function(){
		var aaaa = $("#com_names_m").val();
        var brokenstring=aaaa.split("#");
        $("#panel").load("fu_company_edit.php?COMID="+ brokenstring[0]);
    });
	

});


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
<div id="swarp" style="width:800px; height:auto; margin-left:auto; margin-right:auto;">

<div id="warppage" style="width:800px;">
<div id="headerpage" style="height:10px; text-align:center"></div>
<div class="style1" id="menu" style="height:25px; padding-left:10px; padding-top:10px; padding-right:10px;">ค้นหาบริษัท <hr /></div>
<div id="contentpage" style="height:auto;">
<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  <div class="style5" style="width:auto; height:100px; padding-left:10px;">
  <b></b>
<!--<form name="frm1" action="fu_company_data.php?" >-->
    <input type="text" size="50" id="com_names_m" name="com_names_m" style="height:20;"/>
    <input type="button" value="ค้นหา" id="btn1"  />
	<input type="button" value="เพิ่ม" id="btn31" onclick="javascript:popU('fu_company_edit.php?COMID=i','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')"/>
   
	
	 <input name="button" type="button" onclick="window.location.href='index.php'" value="ปิด" />
<!--</form>-->
	 <div class="style5" style="width:auto; height:100px; padding-left:10px;">
    <b></b> ระบุ รหัส,ชื่อ บริษัท
	
	
</div>
  </div>
</div>


</div>
<div id="footerpage">


</div>
</div>
</div>
<div id="panel" style="padding-top: 10px;"></div>
<p>
<?php
$objQuery2 = pg_query("SELECT * FROM \"fu_company\"  order by \"runnumber\"");
$nrows2=pg_num_rows($objQuery2);

?>

<center><div style="width: 900px; height: 500px; overflow: auto;"><table width="900" border="0">

  <tr bgcolor="#79BCFF" height="25" >
    <th width="190"><div align="center">รหัสบริษัท</div></th>
    <th width="190"> <div align="center">ชื่อบริษัท </div></th> 	
	 <th width="198"> <div align="center">เบอร์มือถือ</div></th>
	<th width="190"> <div align="center">การสนทนา</div></th>
	<th width="150"> <div align="center">วันที่แก้ไขล่าสุด</div></th>
	<th width="150"> <div align="center">ผู้แก้ไขล่าสุด</div></th>
	 <th width="190"> <div align="center">แก้ไข</div></th>
  </tr>
 <?PHP while($results2 = pg_fetch_array($objQuery2))
{
$comid =  $results2["comID"];

$objQuery1 = pg_query("SELECT count(\"conID\") as count FROM \"fu_conversation\" where \"comID\" = '$comid'");
$nrows1=pg_num_rows($objQuery1);



?>
  <tr>
	
	<td bgcolor="#70CCFF" onclick="javascript:popU('fu_company_data.php?COMID=<?php echo $results2["comID"]; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">
	<div align="center"><?php echo $results2["comID"];?></div></td>
    <td><div align="center"><?php echo $results2["com_name"];?></div></td>	
    <td><div align="center"><?php echo $results2["com_phone"];?></div></td> 
	
	
	
	
	<?php while($results1 = pg_fetch_array($objQuery1)){
	$countcon =  $results1["count"];
	if($countcon == 0)
	{
		$countcon1 = 'ไม่มีประวัติการสนทนา';
	}else{

		$countcon1 = $countcon." ".'ครั้ง';
	} ?>
	<td bgcolor="#70CCFF" onclick="javascript:popU('fu_conversation_com_showlist.php?COMID=<?php echo $results2["comID"]; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">
	<div align="center"><?php echo $countcon1 ; ?></div></td>
	<td><div align="center"><?php echo $results2["com_date"];?></div></td>
	
	<?php 
	$id_user = $results2["id_user"];
	$objQuery3 = pg_query("SELECT * FROM \"fuser\" where \"id_user\" = '$id_user'");
	$results3 = pg_fetch_array($objQuery3)
	
	?>
	<td><div align="center"><?php echo $results3["fname"];?> <?php echo $results3["lname"];?></div></td>
	<td bgcolor="#70CCFF" onclick="javascript:popU('fu_company_edit.php?COMID=<?php echo $results2["comID"]; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">
	<div align="center"> แก้ไข </div></td>
	<?php } ?>

  </tr>
<?php
}
?>
</table></div></center>

</body>
</html>

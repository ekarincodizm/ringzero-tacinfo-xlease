<?php
session_start();
$_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
include("../../config/config.php");

$strSort = pg_escape_string($_GET["sort"]);
if($strSort == "")
{
	$strSort = "temID";
}

$strOrder = pg_escape_string($_GET["order"]);
if($strOrder == "")
{
	$strOrder = "ASC";
}

$idcom = pg_escape_string($_GET['comID']);
$idemp = pg_escape_string($_GET['empID']);
$reID = pg_escape_string($_GET['reID']);
if($idcom != ""){
	$id = $idcom;
	$col = 'comID';

	$sqlcom = pg_query("SELECT * FROM \"fu_company\" where \"comID\" = '$id'");
	$result = pg_fetch_array($sqlcom);
	$name = "บริษัท ".$result['com_name'];
	
}else if($idemp != ""){
	$id = $idemp;
	$col = 'empconID';
	
	$sqlemp = pg_query("SELECT * FROM \"fu_empcontact\" where \"empconID\" = '$id'");
	$result = pg_fetch_array($sqlemp);
	$name = "คุณ ".$result['empcon_name'];

}else if($reID !=""){
	$id = $reID;
	$col = pg_escape_string($_GET['col']);
	$name = pg_escape_string($_GET['name']);
}
?>

<title> ประวัติการส่งเมลล์ </title>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
};
</script>

<center><legend><h2>ประวัติการส่งเมลล์ถึง  <?php echo $name ?></h2></legend></center>

<div align="center">
<div class="style5" style="width:auto; height:40px; padding-left:10px;">
  <b></b>
<?php

$strSQL = "SELECT * FROM \"fu_mail_history\" fmh join \"fu_template\" ftem on fmh.\"temID\" = ftem.\"temID\" 
where fmh.\"$col\" = '$id' order by ftem.\"$strSort\" $strOrder ";
$objQuery = pg_query($strSQL);

$strNewOrder = $strOrder == 'DESC' ? 'ASC' : 'DESC';
$nrows=pg_num_rows($objQuery);
if($nrows != 0){
?>
<p></p>

<div style="width: 1100px; height: 600px; overflow: auto;">
<table width="1000" border="1">

  <tr bgcolor="#79BCFF" height="25" >
  
    <th width="190"> <div align="center"><a href='fu_email_history.php?sort=tem_name&order=<?php echo $strNewOrder ?>&reID=<?php echo $id ?>&col=<?php echo $col ?>&name=<?php echo $name ?>'><u>ชื่อ Template ที่ส่ง</u></div></th>
    <th width="190"> <div align="center"><a href='fu_email_history.php?sort=tem_header&order=<?php echo $strNewOrder ?>&reID=<?php echo $id ?>&col=<?php echo $col ?>&name=<?php echo $name ?>'><u>หัวเรื่องที่ส่ง</u></div></th>
	<th width="150"> <div align="center"><a href='fu_email_history.php?sort=tem_sendname&order=<?php echo $strNewOrder ?>&reID=<?php echo $id ?>&col=<?php echo $col ?>&name=<?php echo $name ?>'><u>ผู้ส่ง</u></div></th>
    <th width="150"> <div align="center">วันที่/เวลาที่ส่ง </div></th>
  </tr>
<?php
while($results = pg_fetch_array($objQuery))
{
?>
  <tr>
	<td onclick="javascript:popU('fu_email_template_data.php?temID=<?php echo $results["temID"]; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;"">	
	<div align="center"><u><?php echo $results["tem_name"];?></u></div></td>
    <td><div align="center"><?php echo $results["tem_header"];?></div></td>
    <td><div align="center"><?php echo $results["tem_sendname"];?></div></td>
	<td><div align="center"><?php echo $results["maildate"];?></div></td>
	
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
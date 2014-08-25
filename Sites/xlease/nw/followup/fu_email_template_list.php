<?php
session_start();
$_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}

include("../../config/config.php");

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

<table width="900" border="0"  cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
        
<div style="float:left">&nbsp;</div>
<div style="clear:both;"></div>
<?php 
$objQuery = pg_query("SELECT * FROM \"fu_template\" order by \"temID\"");
$nrows = pg_num_rows($objQuery);

if($nrows){ 
?>
	<center><legend><h2> รายการ Template  </h2></legend></center>

		<div align="center">
			<div class="style5" style="width:auto; height:40px; padding-left:10px;">
				<p></p>
				<div style="width: 1000px; height: 600px; overflow: auto;">
					<table width="900" border="0">
					<tr bgcolor="#79BCFF" height="25" >
						<th width="190"><div align="center">รหัส Template</div></th>
						<th width="190"> <div align="center">ชื่อ Template</div></th>
						<th width="190"> <div align="center">หัวเรื่อง</div></th> 						
						<th width="150"> <div align="center">ชื่อผู้ส่ง</div></th>
						<th width="190"> <div align="center">E-mail ผู้ส่ง</div></th>
						<th width="190"> <div align="center">แก้ไข</div></th>
						<th width="190"> <div align="center">ตัวอย่าง</div></th>
					</tr>
<?php
while($results2 = pg_fetch_array($objQuery)){
?>
					<tr>
						<td bgcolor="#70CCFF" onclick="javascript:popU('fu_email_template_data.php?temID=<?php echo $results2["temID"]; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">
							<div align="center"><?php echo $results2["temID"];?></div></td>
							<td><div align="center"><?php echo $results2["tem_name"];?></div></td>
							<td><div align="center"><?php echo $results2["tem_header"];?></div></td>
							<td><div align="center"><?php echo $results2["tem_sendname"];?></div></td>
							<td><div align="center"><?php echo $results2["tem_send_email"];?></div></td>
							<td bgcolor="#70CCFF" onclick="javascript:popU('fu_email_template_edit.php?temID=<?php echo $results2["temID"]; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">
							<div align="center"> แก้ไข </div></td>
							<td bgcolor="#70CCFF" onclick="javascript:popU('fu_email_template_demo.php?temID=<?php echo $results2["temID"]; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">
							<div align="center"> ตัวอย่าง </div></td>
<?php } ?>

					</tr>
					<tr>
						<td colspan="6" bgcolor="#79BCFF"><div align="center"></div> มีทั้งหมด <?php echo $nrows; ?> รายการ</div></td>
						<td  bgcolor="#79BCFF"><div align="center"></div><center><input type="button" id="btnadd" name="btnadd" value="เพิ่ม Template" 
							onclick="javascript:popU('fu_email_template.php','toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no')"></div></td>
					</tr></center>
					</table>
<?php 
}else if(!$nrows){ 

				echo "<hr width=850>";
				echo "<center><h1>ไม่พบข้อมูล</h1></center>";?>
				<div align="center" ></div><center><input type="button" id="btnadd" name="btnadd" value="เพิ่ม Template" onclick="javascript:popU('fu_email_template.php','toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no')"></div>			
				</center></tr>
<?php } ?>

</div></table>

</body>
</html>

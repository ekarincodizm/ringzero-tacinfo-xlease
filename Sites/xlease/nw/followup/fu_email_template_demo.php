<?php
include("../../config/config.php");
$temID = pg_escape_string($_GET['temID']);
$objQuery = pg_query("SELECT * FROM \"fu_template\" where \"temID\"='$temID'");
$results2 = pg_fetch_array($objQuery);
$detail1 = $results2['tem_detail'];
$detail = str_replaceout($detail1);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

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
<center><legend><h2> ตัวอย่าง Template  <?php echo $results2['tem_name']; ?></h2></legend></center>
 <h3>หัวเรื่อง : <?php echo $results2['tem_header']; ?></h3>
<br>
 รายละเอียด 
 <br>
<?php echo $detail ?>
<br>
ผู้ส่ง : <?php echo $results2['tem_sendname']; ?>
<br>
อีเมลล์ : <?php echo $results2['tem_send_email']; ?>

<br>
ไฟล์แนบ:
<?php
						$qry_name2 = pg_query("select * from \"fu_template\" WHERE \"temID\" = '$temID'");
						$result1=pg_fetch_array($qry_name2);						
						$ff = $result1["tem_file"];
						$file=explode("/",$ff);						
						
						for($i=0;$i<sizeof($file);$i++){
						?>							
<a href="fileupload/<?php echo $file[$i];?>" target="_blank"><u><?php echo $file[$i];?></u>
						<?php } ?>	

</body>
</html>
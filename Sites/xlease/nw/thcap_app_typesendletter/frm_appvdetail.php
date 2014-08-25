<?php 
include('../../config/config.php');
$idno=pg_escape_string($_GET["idno"]);
$query_detail= pg_query("select \"sendName\",\"addUser\",\"addStamp\" from \"thcap_letter_head_temp\" 			
				where \"status\" = '9' and \"auto_id\" ='$idno' ");
$resspec=pg_fetch_array($query_detail);
list($sendName,$addUser,$addStamp)=$resspec;
//ชื่อผู้ที่ทำรายการ
				$query_fullnameuser = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$addUser' ");
				$fullnameuser = pg_fetch_array($query_fullnameuser);
				$doerfullname=$fullnameuser["fullname"];
?>

<script type="text/javascript">
</script>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<head>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>

<body>
<div style="text-align:center"><h2>รายการชื่อประเภทของจดหมาย</h2></div>
<table align="center">
<tr><td align="right"><b>ชื่อประเภทของจดหมาย :</b></td><td> <?php echo $sendName;?></td></tr>
<tr><td align="right"><b>ผู้ที่ทำรายการ :</b></td><td> <?php echo $doerfullname;?></td></tr>
<tr><td align="right"> <b>เวลาทำรายการ :</b></td><td> <?php echo $addStamp;?></td></tr>
</table>
<form name="frm1" method="post" action="process_appvtype.php">
<div style="text-align:center;padding:20px">	
	<input type="text" name="autoid" id="autoid" value="<?php echo $idno;?>" hidden> 
	<input type="submit" name="appv" value="อนุมัติ" > 
	<input type="submit" name="unappv" value="ไม่อนุมัติ" > 
	<input type="button" onclick="window.close();" value="ปิดหน้านี้">
</form>
</div>
</body>
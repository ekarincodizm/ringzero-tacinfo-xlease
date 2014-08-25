<?php
session_start();
include("../../config/config.php");	
$id_user=$_SESSION["av_iduser"];
$revChqID=$_POST["revchqid"];
$result=$_POST["result"];
$datechoise = $_POST["keepChqDate"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<title>ระบุวันที่รับเช็ค</title>
<script language=javascript>
function checkdate(){

	if(confirm("ยืนยันการบันทึกการเก็บรักษาเช็ค")==true){
			return true;
	}else{
			return false;
	}	

};
</script>
<!-- InstanceEndEditable -->
<style type="text/css">
<!--
.style1 {
	font-family: Tahoma;
	font-size: medium;
}
.style3 {
    font-family: Tahoma;
	color: #ffffff;
	font-weight: bold;
	font-size: medium;
}
.style4 {
    font-family: Tahoma;
	color: #000000;
}
.style5 {
    font-family: Tahoma;
	color: #000000;
	font-size: medium;
}

-->
</style>

<!-- InstanceBeginEditable name="head" -->
<style type="text/css">
<!--
.style6 {
	color: #FF0000;
	font-weight: bold;
}


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

-->
</style>
<!-- InstanceEndEditable -->
</head>
<body style="background-color:#ffffff; margin-top:0px;">
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;padding:20px 0px;">
	<form method="post" name="form1" action="process_keepcheque.php">
		<input type="hidden" name="statusapp" value="1">
		<input type="hidden" name="keepChqDate" value="<?php echo $datechoise ?>">
		<div id="warppage"  style="width:80%; text-align:left; margin-left:auto; margin-right:auto;">
			<div align="center" style="padding:15px 0px 15px 0px"><font size="3px">วันที่รับเช็ค : <?php echo $datechoise ?></font></div>		
			<div align="center"><font color="red" size="3px">ข้าพเจ้าได้รับเช็คตามรายละเอียดดังกล่าวไว้เรียบร้อยแล้ว</font></div>
			<hr />
		
		<?php 
		for($z=0;$z<sizeof($revChqID);$z++){
			$qry_fr=pg_query("select * from finance.\"V_thcap_receive_cheque_chqManage\" a
								left join \"BankProfile\" b on a.\"bankOutID\"=b.\"bankID\"
								WHERE a.\"revChqID\" = '$revChqID[$z]'");
			while($res_fr=pg_fetch_array($qry_fr)){ 
				$bankChqNo=$res_fr["bankChqNo"];
				$revChqStatus=$res_fr["revChqStatus"];
				$vrevChqToCCID=$res_fr["revChqToCCID"];
				$bankName = $res_fr["bankName"]; 
				$bankChqAmt = $res_fr["bankChqAmt"];
				$bankChqDate = $res_fr["bankChqDate"]; 				
		?>	
				<input type="hidden" name="revchqid[]" value="<?php echo $revChqID[$z];?>">
				<input type="hidden" name="res[]" value="<?php echo $result[$z];?>">
				<div align="left" style="padding:0px 0px 0px 50px;">				
						<b>เลขที่เช็ค <?php echo $z+1 ?>:</b> <?php echo $bankChqNo;?>
						<b>วันที่สั่งจ่าย : </b><?php echo $bankChqDate;?>
						<b>เลขที่สัญญา : </b><?php echo $vrevChqToCCID;?>
						<b>ธนาคาร : </b><?php echo $bankName;?>
						<b>จำนวนเงิน : </b><?php echo number_format($bankChqAmt,2);?>
						<br />					
				</div>
		<?php }
		} ?>
			<div align="center" style="padding:10px 0px;"><input type="submit" value="บันทึก" style="width:100px;" onclick="return checkdate();"><input type="button" style="width:100px;" value="กลับ" onclick="parent.location='frm_keepcheque.php';"></div>
		</div>	
	</form>	
</div>
</body>
</html>

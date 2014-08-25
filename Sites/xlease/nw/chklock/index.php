<?php
set_time_limit(0);
session_start();
include("../../config/config.php");
$postback = $_POST["postback"];
$startDate = $_POST["startDate"];
$endDate = $_POST["endDate"];
$status = $_POST["status"];
if($startDate == "" and $endDate == ""){
	$startDate = nowDate();
	$endDate = nowDate();
}else{
	$startDate = $startDate;
	$endDate = $endDate;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
 <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
 <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
 <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<title>ตรวจสอบสัญญา Lock หรือ Unlock</title>
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}

$(document).ready(function(){
    $("#startDate").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        //minDate: 0,
        dateFormat: 'yy-mm-dd'
    });
	$("#endDate").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        //minDate: 0,
        dateFormat: 'yy-mm-dd'
    });
});
function checkdata() {
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var noErrors = theMessage
	
	if (document.form1.startDate.value=="") {
	theMessage = theMessage + "\n -->  กรุณาเลือกวันที่เริ่มต้น";
	}
	
	if (document.form1.endDate.value=="") {
	theMessage = theMessage + "\n -->  กรุณาเลือกวันที่สิ้นสุด";
	}

	// If no errors, submit the form
	if (theMessage == noErrors) {
	return true;
	}else {
	// If errors were found, show alert message
		alert(theMessage);
		return false;
	}
}
</script>
</head>
<body>

<div class="title_top" align="center"><h1>ตรวจสอบสัญญา Lock หรือ Unlock</h1></div>
<form method="post" name="form1" action="index.php">
<table align="center" width="400" border="0">
	<tr align="center">
		<td height="30" colspan="2">ระหว่างวันที่ 
			<input type="text" id="startDate" name="startDate" value="<?php echo $startDate; ?>" size="15" >
			ถึงวันที่ 
			<input type="text" id="endDate" name="endDate" value="<?php echo $endDate; ?>" size="15" >
		</td>
	</tr>
	<tr align="center">
		<td height="30" colspan="2">
			<input type="radio" name="status" value="1" checked <?php if($status == 1){ echo "checked"; }?>> สัญญาที่ Lock 
			<input type="radio" name="status" value="2" <?php if($status == 2){ echo "checked"; }?>>สัญญา Unlock
			<input type="radio" name="status" value="3" <?php if($status == 3){ echo "checked"; }?>>แสดงทั้งหมด
			<input type="hidden" name="postback" value="yes">
			<input type="submit" value="ค้นหา" onclick="return checkdata()">
		</td>
	</tr>
</table>
</form>
<hr width="600">
<table align="center" width="400">
	<tr>
	<?php
		if($postback=="yes")
		{
			echo "<form method=\"post\" name=\"form2\" action=\"frm_pdf.php\" target=\"_blank\">";
			echo "<td align=\"center\">";
			echo "<input type=\"hidden\" name=\"status\" value=\"$status\">";
			echo "<input type=\"hidden\" name=\"startDate\" value=\"$startDate\">";
			echo "<input type=\"hidden\" name=\"endDate\" value=\"$endDate\">";
			echo "<input type=\"submit\" value=\"พิมพ์\">";
			echo "</td>";
			echo "</form>";
		}
	?>
	</tr>
</table>
<table align="center" width="400" border="0" cellspacing="1" cellpadding="1" bgcolor="#CCCCCC">

	<tr align="center" bgcolor="#F4CEFD">
		<th height="30" width="50">ลำดับที่</th>
		<th>เลขที่สัญญา</th>
		<th>สถานะ</th>
	</tr>

	<?php 
	if($status == "" || $status == 1){
		$query = pg_query("select \"IDNO\",\"LockContact\" from  \"Fp\" where \"LockContact\" = 'TRUE' and (\"P_STDATE\" between '$startDate' and '$endDate') order by \"IDNO\"");
	}else if($status == 2){
		$query = pg_query("select \"IDNO\",\"LockContact\" from  \"Fp\" where \"LockContact\" = 'FALSE' and (\"P_STDATE\" between '$startDate' and '$endDate') order by \"IDNO\"");
	}else if($status == 3){
		$query = pg_query("select \"IDNO\",\"LockContact\" from  \"Fp\" where (\"P_STDATE\" between '$startDate' and '$endDate') order by \"LockContact\" ");
	}
	$nrows=pg_num_rows($query);
	$i=1;
	while($row = pg_fetch_array($query)) {
		$IDNO = $row["IDNO"];
		$LockContact = $row["LockContact"];
		
		if($LockContact == 't'){
			$txtlock = "LOCK";
			$color = "#FAE9FE";
		}else{
			$txtlock = "UNLOCK";
			$color = "#F4F4F4";
		}
		echo "<tr bgcolor=$color height=25><td align=center>$i</td><td align=center><a href=\"#\" onclick=\"javascript:popU('../../post/frm_viewcuspayment.php?idno_names=$IDNO&type=outstanding','$IDNO_sdasdsadsa','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\" ดูตารางการผ่อนชำระ\"><u>$IDNO</u></a></td><td align=center>$txtlock</td></tr>";
	$i++;
	} //end while
	if($nrows == 0){
		echo "<tr bgcolor=#FFFFFF height=50><td align=center colspan=3>ไม่พบข้อมูล</td></td></tr>";
	}
	?>
</table>

</body>
</html>
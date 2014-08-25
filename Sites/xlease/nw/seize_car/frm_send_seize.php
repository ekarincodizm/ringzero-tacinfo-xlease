<?php
session_start();
include("../../config/config.php");
$IDNO = $_GET['idno'];
$NTID = $_GET['ntid'];

$query=pg_query("select a.\"IDNO\",b.\"full_name\",a.\"P_STDATE\",b.\"C_CARNAME\",b.\"C_REGIS\",b.\"C_REGIS_BY\",
b.\"C_CARNUM\",c.\"gas_name\",c.\"gas_number\",c.\"car_regis\",c.\"car_regis_by\",c.\"carnum\" from \"Fp\" a
left join \"VCarregistemp\" b on a.\"IDNO\" = b.\"IDNO\"
left join \"FGas\" c on a.\"asset_id\" = c.\"GasID\"
where a.\"IDNO\" = '$IDNO'");
if($res = pg_fetch_array($query)){
	$IDNO = $res["IDNO"];
	$fullname = trim($res["full_name"]);
	$P_STDATE = $res["P_STDATE"]; //วันทำสัญญา
	$C_CARNAME = $res["C_CARNAME"]; //ยี่ห้อรถยนต์
	
	//ถังแก๊ส
	if($C_CARNAME == ""){
		$C_CARNAME = $res["gas_name"]." <b>เลขถังแก๊ส </b> ". $res["gas_number"]; //ยี่ห้อถังแก๊ส
		$C_REGIS = $res["car_regis"]; //ทะเบียนรถ
		$CAR_REGIS_BY = $res["car_regis_by"]; //จังหวัด
		$C_CARNUM = $res["carnum"]; //หมายเลขตัวถัง
	}else{
		$C_CARNAME = $res["C_CARNAME"]; //ยี่ห้อรถยนต์
		$C_REGIS = $res["C_REGIS"]; //ทะเบียน
		$C_CARNUM = $res["C_CARNUM"]; //หมายเลขตัวถัง
		$CAR_REGIS_BY = $res["C_REGIS_BY"]; //จังหวัด
	}
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="../../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"><link>
	<script type="text/javascript" src="../../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
<script type="text/javascript">
$(document).ready(function(){
    $("#authorize_user").autocomplete({
        source: "s_user.php",
        minLength:1
    });
	$("#seize_user").autocomplete({
        source: "s_user.php",
        minLength:1
    });
	$("#witness_user1").autocomplete({
        source: "s_user.php",
        minLength:1
    });
	$("#witness_user2").autocomplete({
        source: "s_user.php",
        minLength:1
    });
});

function checkdata() {
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var noErrors = theMessage

	if (document.form1.authorize_user.value=="") {
	theMessage = theMessage + "\n -->  กรุณาเลือกผู้มอบอำนาจ";
	}
	
	if (document.form1.seize_user.value=="") {
	theMessage = theMessage + "\n -->  กรุณาเลือกผู้รับมอบอำนาจ";
	}
	
	if (document.form1.witness_user1.value=="") {
	theMessage = theMessage + "\n -->  กรุณาเลือกพยานคนที่ 1";
	}
	
	if (document.form1.witness_user2.value=="") {
	theMessage = theMessage + "\n -->  กรุณาเลือกพยานคนที่ 2";
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

<fieldset><legend><B>รายละเอียดการยึดรถ</B></legend>
<table width="100%">
<tr>
    <td><b>เลขที่สัญญา</b></td>
    <td><?php echo $IDNO; ?></td>
	<td><b>ชื่อผู้เช่าซื้อ</b></td>
	<td><?php echo $fullname; ?></td>
</tr>
<tr>
    <td><b>ยี่ห้อ</b></td>
    <td><?php echo $C_CARNAME; ?></td>
	<td><b>หมายเลขทะเบียน</b></td>
	<td><?php echo $C_REGIS; ?></td>
</tr>
<tr>
    <td><b>จังหวัด</b></td>
    <td><?php echo $CAR_REGIS_BY; ?></td>
	<td><b>หมายเลขตัวถัง</b></td>
	<td><?php echo $C_CARNUM; ?></td>
</tr>
</table>
</fieldset> 
<br>
<?php
	$query_seize=pg_query("select b.\"fullname\" as \"senduser\",c.\"fullname\" as \"approveuser\",a.\"send_date\",a.\"approve_date\" from \"nw_seize_car\" a
	left join \"Vfuser\" b on a.\"send_user\" = b.\"id_user\"
	left join \"Vfuser\" c on a.\"approve_user\" = c.\"id_user\" 
	where a.\"IDNO\" = '$IDNO' and a.\"NTID\" = '$NTID'");
	if($res_seize = pg_fetch_array($query_seize)){
		$senduser=$res_seize["senduser"];
		$approveuser=$res_seize["approveuser"];
		$send_date=$res_seize["send_date"];
		$approve_date=$res_seize["approve_date"];
	}
?>
<form name="form1" method="post" action="process_send_seize.php">
<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#EDF8FE">
<tr height="25">
    <td colspan="4"></td>
</tr>
<tr height="25">
    <td><b>ผู้แจ้ง</b></td>
    <td><?php echo $senduser; ?></td>
	<td><b>วันที่แจ้ง</b></td>
	<td><?php echo $send_date; ?></td>
</tr>
<tr height="25">
    <td><b>ผู้อนุมัติ</b></td>
    <td><?php echo $approveuser; ?></td>
	<td><b>วันที่อนุมัติ</b></td>
	<td><?php echo $approve_date; ?></td>
</tr>
<tr height="25">
    <td><b>ผู้มอบอำนาจ</b></td>
    <td colspan="3"><input type="text" name="authorize_user" id="authorize_user" size="40"></td>
</tr>
<tr height="25">
    <td><b>ผู้รับมอบอำนาจ</b></td>
    <td colspan="3"><input type="text" name="seize_user" id="seize_user" size="40"> 
		<b>เป็นตัวแทน</b> 
		<select name="organize">
			<?php
				$query_organize=pg_query("select * from \"nw_organize\"");
				while($res_or=pg_fetch_array($query_organize)){
					$organizeID = $res_or["organizeID"];
					$organize_name = $res_or["organize_name"];
					echo "<option value=$organizeID>$organize_name</option>";
				}
			?>
		</select>
	</td>
</tr>
<tr>
    <td><b>พยานคนที่ 1</b></td>
    <td colspan="3"><input type="text" name="witness_user1" id="witness_user1" size="40"></td>
</tr>
<tr height="25">
    <td><b>พยานคนที่ 2</b></td>
    <td colspan="3"><input type="text" name="witness_user2" id="witness_user2" size="40"></td>
</tr>
<tr height="25">
    <td colspan="4"></td>
</tr>
<tr height="50" bgcolor="#FFFFFF">
    <td colspan="4" align="center">
		<input type="hidden" name="IDNO" value="<?php echo $IDNO; ?>">
		<input type="hidden" name="NTID" value="<?php echo $NTID; ?>">
		<input name="btnButton1" type="submit" value="บันทึก"onclick="return checkdata()" /><input name="btnButton2" type="reset" value="ยกเลิก" onclick="javascript:window.close();" />
	</td>
</tr>
</table>
</form>
</body>
</html>
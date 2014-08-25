<?php
session_start();
include("../../config/config.php");
$autoid = pg_escape_string($_GET['autoid']);
//หาข้อมูลเพื่อแสดงรายละเอียด
$qry_chkwait=pg_query("select \"IDNO\",\"NTID\",
\"seize_user_old\" ,\"authorize_user_old\" ,\"witness_user1_old\" , \"witness_user2_old\" , \"organizeID_old\",\"proxy_usersend_old\",\"proxy_datesend_old\",
\"seize_user_new\" ,\"authorize_user_new\" ,\"witness_user1_new\" ,\"witness_user2_new\",\"organizeID_new\",\"proxy_usersend_new\",\"proxy_datesend_new\"
from \"nw_seize_cancel_btseizure\" WHERE  \"status\"='9' AND \"auto_id\"='$autoid'");
$numrows=pg_num_rows($qry_chkwait);
if($numrows >0){
	
	list($IDNO,$NTID,
	$seize_user_old ,$authorize_user_old ,$witness_user1_old ,$witness_user2_old ,$organizeID_old,$proxy_usersend_old,$proxy_datesend_old,
	$seize_user_new ,$authorize_user_new ,$witness_user1_new ,$witness_user2_new,$organizeID_new,$proxy_usersend_new,$proxy_datesend_new	
	)=pg_fetch_array($qry_chkwait);

}

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
	
	$qry_proxy_usersend = pg_query("select fullname from \"Vfuser\" where id_user = '$proxy_usersend_new' ");
	$proxy_usersend = pg_fetch_result($qry_proxy_usersend ,0);
	//ข้อมูลเดิม	
	//ผู้มอบอำนาจ
	$qry_seize_user_old = pg_query("select fullname from \"Vfuser\" where id_user = '$seize_user_old' ");
	$fullname_seize_user_old = pg_fetch_result($qry_seize_user_old ,0);
	
	//ผู้รับมอบอำนาจ
	$qry_authorize_user_old = pg_query("select fullname from \"Vfuser\" where id_user = '$authorize_user_old' ");
	$fullname_authorize_user_old = pg_fetch_result($qry_authorize_user_old ,0);
	
	//เป็นตัวแทน
	$query_organize=pg_query("select \"organize_name\" from \"nw_organize\" where \"organizeID\"='$organizeID_old' ");
	$organize_old = pg_fetch_result($query_organize ,0);		
	
	//พยานคนที่ 1
	$qry_witness_user1_old = pg_query("select fullname from \"Vfuser\" where id_user = '$witness_user1_old' ");
	$witness_user1_old = pg_fetch_result($qry_witness_user1_old ,0);
	
	//พยานคนที่ 2
	$qry_witness_user2_old = pg_query("select fullname from \"Vfuser\" where id_user = '$witness_user2_old' ");
	$witness_user2_old = pg_fetch_result($qry_witness_user2_old ,0);
	
	//ข้อมูลใหม่
	//ผู้มอบอำนาจ
	$qry_seize_user_new = pg_query("select fullname from \"Vfuser\" where id_user = '$seize_user_new' ");
	$fullname_seize_user_new = pg_fetch_result($qry_seize_user_new ,0);
	
	//ผู้รับมอบอำนาจ
	$qry_authorize_user_new = pg_query("select fullname from \"Vfuser\" where id_user = '$authorize_user_new' ");
	$fullname_authorize_user_new = pg_fetch_result($qry_authorize_user_new ,0);
	
	//เป็นตัวแทน
	$query_organize_new=pg_query("select \"organize_name\" from \"nw_organize\" where \"organizeID\"='$organizeID_new' ");
	$organize_new = pg_fetch_result($query_organize_new ,0);		
	
	//พยานคนที่ 1
	$qry_witness_user1_new = pg_query("select fullname from \"Vfuser\" where id_user = '$witness_user1_new' ");
	$witness_user1_new = pg_fetch_result($qry_witness_user1_new ,0);
	
	//พยานคนที่ 2
	$qry_witness_user2_new = pg_query("select fullname from \"Vfuser\" where id_user = '$witness_user2_new' ");
	$witness_user2_new = pg_fetch_result($qry_witness_user2_new ,0);
	
	
?>
<form name="form1" method="post" action="process_appv.php">

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
    <td><b>ผู้ขอยกเลิก</b></td>
    <td><?php echo $proxy_usersend; ?></td>
	<td><b>วันที่ขอยกเลิก</b></td>
	<td><?php echo $proxy_datesend_new; ?></td>
</tr>
</table>
<br>
<fieldset><legend><B>ข้อมูลเดิม</B></legend>
<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#FFCC99">
<tr height="25">
    <td><b>ผู้มอบอำนาจ</b></td>
    <td colspan="3"><input type="text"  size="40" value="<?php echo $fullname_seize_user_old;?>" readonly></td>
</tr>
<tr height="25">
    <td><b>ผู้รับมอบอำนาจ</b></td>
    <td colspan="3"><input type="text"  size="40" value="<?php echo $fullname_authorize_user_old;?>" readonly > 
		<b>เป็นตัวแทน</b><input type="text"  size="40" value="<?php echo $organize_old;?>" readonly>
	</td>
</tr>
<tr>
    <td><b>พยานคนที่ 1</b></td>
    <td colspan="3"><input type="text"  size="40" value="<?php echo $witness_user1_old;?>" readonly></td>
</tr>
<tr height="25">
    <td><b>พยานคนที่ 2</b></td>
    <td colspan="3"><input type="text"  size="40" value="<?php echo $witness_user2_old;?>" readonly></td>
</tr>
<tr height="25">
    <td colspan="4"></td>
</tr>
</table>
</fieldset> 
<br>
<fieldset><legend><B>ข้อมูลที่ขอยกเลิก</B></legend>
<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#99FF99">
<tr height="25">
    <td><b>ผู้มอบอำนาจ</b></td>
    <td colspan="3"><input type="text"  size="40" value="<?php echo $fullname_seize_user_new;?>" readonly></td>
</tr>
<tr height="25">
    <td><b>ผู้รับมอบอำนาจ</b></td>
    <td colspan="3"><input type="text"  size="40" value="<?php echo $fullname_authorize_user_new;?>" readonly > 
		<b>เป็นตัวแทน</b><input type="text"  size="40" value="<?php echo $organize_new;?>" readonly>
	</td>
</tr>
<tr>
    <td><b>พยานคนที่ 1</b></td>
    <td colspan="3"><input type="text"  size="40" value="<?php echo $witness_user1_new;?>" readonly></td>
</tr>
<tr height="25">
    <td><b>พยานคนที่ 2</b></td>
    <td colspan="3"><input type="text"  size="40" value="<?php echo $witness_user2_new;?>" readonly></td>
</tr>
<tr height="25">
    <td colspan="4"></td>
</tr>
<tr height="50" bgcolor="#FFFFFF">
    <td colspan="4" align="center">
		<input type="hidden" name="autoid" value="<?php echo $autoid; ?>">
		<input name="btnappv" type="submit" value="อนุมัติ"  />
		<input name="btnunappv" type="submit" value="ไม่อนุมัติ" />
		<input name="btnButton3" type="reset" value="ปิด" onclick="javascript:window.close();" />
	</td>
</tr>
</table>
</fieldset> 
</form>
</body>
</html>
<?php
session_start();
include("../../config/config.php");

$get_userid = $_SESSION["av_iduser"];
$get_idno = $_GET["idno"];
$get_cusid = $_GET["cusid"];
$search_top = $get_idno;


$qry_top=pg_query("select * from \"Fp\" WHERE \"IDNO\"='$get_idno'");
$res_top=pg_fetch_array($qry_top);
$CusID=$res_top["CusID"];
$P_TransferIDNO=$res_top["P_TransferIDNO"];
$arr_idno[$get_idno]=$CusID;


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>บันทึกการชวนลูกค้า</title>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(function(){
    $("#tabs").tabs();
    $("#tabs").tabs('select', '<?php echo $_SESSION["ses_idno"]; ?>');

});

$(document).ready(function(){
    $("#datepicker").datepicker({
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

	if (document.form1.invite_detail.value=="") {
	theMessage = theMessage + "\n -->  กรุณากรอกรายละเอียดการชวน";
	}
	
	if (document.form1.CusTel.value=="") {
	theMessage = theMessage + "\n -->  กรุณากรอกเบอร์โทรศัพท์ลูกค้าที่ติดต่อ";
	}
	
	if (document.form1.datepicker.value=="") {
	theMessage = theMessage + "\n -->  กรุณาเลือกวันที่ติดต่อ";
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

<style type="text/css">
.ui-tabs{
    font-family:tahoma;
    font-size:12px
}
</style>

<style type="text/css">
body {
    font-family:tahoma;
    color : #333333;
    font-size:12px;
}
.title_top{
    font-family: tahoma;
    font-size:19px;
    font-weight: bold;
    margin: 0;
    padding: 0 0 3px 0;
    text-align: right;
}
TEXTAREA,SELECT,INPUT{
	font-family: Tahoma;
	color: #3A3A3A;
}
H1 {
    font-size: 18px;
}
.title {
    text-align: center;
}
.TextTitle{
    color: #006600;
    font-size: 11px;
    font-weight: bold;
}
</style>

</head>
<body>

<div class="title_top">บันทึกการชวนลูกค้า</div>

<div id="tabs"> <!-- เริ่ม tabs -->
<ul>
<?php
//สร้าง list รายการ โอนสิทธิ์
foreach($arr_idno as $ii => $v){
    if(empty($ii)){
        continue;
    }
    echo "<li><a href=\"#tabs-$ii\">$ii</a></li>";
}
?>
</ul>

<?php
foreach($arr_idno as $i => $v){
    if(empty($i)){
        continue;
    }
    
    $get_cusid = $v;
    $get_idno = $i;
    
    //กำหนดสี ให้กับข้อมูลล่าสุด
    if($_SESSION["ses_idno"] == $get_idno){
        $bgcolor = "#FFFFFF";
    }else{
        $bgcolor = "#FFFFFF";
    }
    //จบ กำหนดสี
?>

<div id="tabs-<?php echo $get_idno; ?>">
<div style="background-color:<?php echo $bgcolor; ?>">

<div align="right" style="padding-top:5px; padding-bottom:5px;">
<img src="icoPrint.png" border="0" width="17" height="14">&nbsp;<a href="invite_print.php?idno=<?php echo $get_idno; ?>&scusid=<?php echo $get_cusid; ?>" target="_blank">พิมพ์ข้อมูลทั้งหมด</a>
</div>

<fieldset><legend><b>ชวนลูกค้า</b></legend>
<?php
$qry_cn=pg_query("select \"IDNO\",\"CusID\",\"full_name\",\"C_REGIS\",\"asset_id\" from \"UNContact\"  WHERE (\"IDNO\"='$get_idno')");
$res_cn=pg_fetch_array($qry_cn);

$regis = $res_cn["C_REGIS"]; 
$asset_id = $res_cn["asset_id"];
$get_cusid = $res_cn["CusID"];
?>

<div style="float:left">ชื่อ : <?php echo $res_cn["full_name"]; ?></div>
<div style="float:right">วันที่บันทึก : <?php echo date('d-m-Y'); ?></div>
<div style="clear:both;">&nbsp;</div>
<div style="float:left">เลขที่สัญญา : <?php echo $res_cn["IDNO"]; ?></div>
<div style="float:right">ทะเบียนรถ : <?php echo $regis; ?></div>
<div style="clear:both;">&nbsp;</div>

<div style="padding-top:5px;">
<form name="form1" method="post" action="process_invite.php">
<span class="TextTitle">รายละเอียดการชวนลูกค้า</span><br />
<TEXTAREA NAME="invite_detail" ROWS="6" COLS="80"></TEXTAREA><br />
<table align="left">
	<tr><td height="30">เบอร์โทรศัพท์ลูกค้าที่ติดต่อ :</td><td align="left"><input type="text" name="CusTel" size="30"></td></tr>
	<tr><td height="30">วันเวลาที่ติดต่อ  :</td>
		<td>
			<input type="text" id="datepicker" name="datepicker" value="<?php echo nowDate(); ?>" size="15" style="text-align: center;" >
			เวลา : 
			<select name="invite_h" id="invite_h" > <!--ชั่งโมง -->
		    <?php 
			for($i=0;$i<24;$i++){
				if($i<10)$i='0'.$i;		 
		        echo "<option value=\"$i\">$i</option>";
			}
			?>
			</select>
			:
			<select name="invite_m" id="invite_m" > <!--นาที -->
		    <?php 
			for($i=0;$i<60;$i++){
				if($i<10)$i='0'.$i;		 
		        echo "<option value=\"$i\">$i</option>";
			}
			?>
			</select> 
			:
			<select name="invite_s" id="invite_s" > <!--วินาที -->
		    <?php 
			for($i=0;$i<60;$i++){
				if($i<10)$i='0'.$i;		 
		        echo "<option value=\"$i\">$i</option>";
			}
			?>
			</select> น.
		</td>
	</tr>
	<tr><td height="50" colspan="2">
		<INPUT TYPE="submit" VALUE="  บันทึก  " onclick="return checkdata()"><INPUT TYPE="reset" VALUE="  ยกเลิก  ">
		<INPUT TYPE="hidden" NAME="userid" VALUE="<?php echo "$get_userid"; ?>">
		<INPUT TYPE="hidden" NAME="u_idno" VALUE="<?php echo "$get_idno"; ?>">
		<INPUT TYPE="hidden" NAME="asset_id" VALUE="<?php echo "$asset_id"; ?>">
		<INPUT TYPE="hidden" NAME="u_cusid" VALUE="<?php echo "$get_cusid"; ?>">
		</td>
	</tr>
</table>


</form>
</div>
</fieldset>

<fieldset><legend><b>ข้อมูลการชวน</b></legend>
<div style="background-color: #ffffff; padding: 2px">
<?php
$qry_fuc=pg_query("select * from refinance.\"invite\" WHERE (\"IDNO\"='$get_idno') ORDER BY \"inviteDate\" DESC"); 
$numr=pg_num_rows($qry_fuc);

if($numr==0){ echo "<div align=center>- ไม่พบข้อมูล -</div>"; }
while($res_fuc=pg_fetch_array($qry_fuc)){
	$qry_fun=pg_query("select fullname from \"Vfuser\" WHERE (\"id_user\"='$res_fuc[id_user]')");
	$res_fun=pg_fetch_array($qry_fun);
?>
    <div style="background-color: #C0C0C0">
        <div style="float:left; padding:2px">User : <b><?php echo $res_fun["fullname"]; ?></b></div>
        <div style="float:right; padding:2px">วันที่เจรจา : <b><?php echo $res_fuc["inviteDate"]; ?></b></div>
        <div style="clear:both;"></div>
    </div>
    <div style="background-color: #F0F0F0; padding:2px"><?php echo $res_fuc["invite_detail"]; ?></div>
    <div style="background-color: #FFFFFF; clear:both; height:10px"></div>
<?php
}
?>
</div>
</fieldset>

</div>
</div>

<?php
}
?>

</div>

</body>
</html>
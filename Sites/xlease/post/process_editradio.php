<?php
session_start();
include "../config/config.php";

$CarID = $_POST["CarID"];
$RadioID = $_POST["RadioID"];

$add_user=$_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>จ่ายค่าปรับฝ่าฝืนสัญญาณไฟจราจร</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">  
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
	<link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script type="text/javascript">

$(document).ready(function(){
    $('#btn2').click(function(){	
		idno = document.getElementById('idno2').value;
		idtfpen = document.getElementById('idtfpen2').value;
        $("#panel").load("frm_add_nt_traf.php?idno2="+idno+"&idtfpen="+idtfpen+"&type=nf1");
    });
});
</script>
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
<div style="float:left"><input type="button" value="กลับ" onclick="window.location='frm_radio.php'"></div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
<div style="clear:both; padding-bottom: 10px;"></div>        

<fieldset><legend><B>แก้ไขรหัสวิทยุ</B></legend>

<div class="ui-widget" align="center" style="padding:50px;">
<?php
pg_query("BEGIN WORK");
$status = 0;

//ตรวจสอบก่อนว่าเลขที่สัญญานใดใช้รถคันนี้ แล้วให้ไป insert ในประวัติด้วย
$qrycarnow=pg_query("select \"IDNO\" from \"Fp\" a
left join \"Fc\" b on a.asset_id=b.\"CarID\" where \"CarID\"='$CarID' order by \"P_STDATE\" DESC limit 1");
$rescarnow=pg_fetch_array($qrycarnow);
list($idnonow)=$rescarnow;

$ins = "UPDATE \"Fc\" SET \"RadioID\"='$RadioID' WHERE \"CarID\"='$CarID'";
if($result1=pg_query($ins)){
    
}else{
    $status += 1;
}

//นำประวัติในตาราง Carregis_temp มา insert 
$in_carregis="insert into \"Carregis_temp\" (\"IDNO\", \"C_REGIS\", \"C_CARNAME\", \"C_YEAR\", \"C_REGIS_BY\", 
	\"C_COLOR\", \"C_CARNUM\", \"C_MARNUM\", \"C_Milage\", \"C_TAX_ExpDate\", 
	\"C_TAX_MON\", \"C_StartDate\", \"CarID\", \"keyUser\", \"keyStamp\", \"C_CAR_CC\", 
	\"RadioID\", \"CarType\",fc_type,fc_brand,fc_model,fc_category,fc_newcar,fc_gas,type_in_act) 
select 
	\"IDNO\",\"C_REGIS\", \"C_CARNAME\", \"C_YEAR\", \"C_REGIS_BY\",
	\"C_COLOR\", \"C_CARNUM\", \"C_MARNUM\", \"C_Milage\", \"C_TAX_ExpDate\",
	\"C_TAX_MON\", \"C_StartDate\", \"CarID\", '$add_user', '$add_date', \"C_CAR_CC\", 
	'$RadioID',\"CarType\",fc_type,fc_brand,fc_model,fc_category,fc_newcar,fc_gas,type_in_act from \"Carregis_temp\" where \"IDNO\"='$idnonow' order by auto_id DESC limit 1";
if($result_carregis=pg_query($in_carregis)){
}else{
	$status++;
}


if($status == 0){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$add_user', '(TAL) แก้ไขข้อมูลวิทยุ', '$add_date')");
	//ACTIONLOG---
    pg_query("COMMIT");
    echo "บันทึกข้อมูลเรียบร้อยแล้ว";
	
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง <hr>$ins";
}
?>
</div>

 </fieldset>

        </td>
    </tr>
</table>
<div id="panel" style="padding-top:20px;">

</div>
</body>
</html>
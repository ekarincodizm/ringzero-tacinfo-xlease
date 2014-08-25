<?php
include("../../config/config.php");

$id_user = $_SESSION["av_iduser"]; // id ของ user ที่กำลังใช้งานอยู่ในขณะนั้น

$qry_array = pg_query("select \"TCS_Search\" from thcap_favorite where id_user = '$id_user' and \"TCS_Search\" is not null ");
$already_fav = pg_num_rows($qry_array);
if($already_fav>0)
{
	$res_array = pg_fetch_result($qry_array ,0);
	$qyr_Fav = pg_query("select ta_array1d_popularity('$res_array','2')");
	$res_Fav = pg_fetch_result($qyr_Fav,0);
	
	switch ($res_Fav)
	{
		case 1:
			$check0 = "checked";
			break;
		case 2:
			$check1 = "checked";
			break;
		case 3:
			$check2 = "checked";
			break;
		default:
			$check0 = "checked";
			break;
	}
}
else
{
	$check0 = "checked";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) Create งานยึด</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script type="text/javascript">
function SubmitForm(){
	var criteria;
	if(document.getElementById("criteria0").checked){
		criteria = "Default";	
	} else if (document.getElementById("criteria1").checked){
		criteria = "Asset10";
	} else if (document.getElementById("criteria2").checked){
		criteria = "PrimaryCus";
	} 
	return criteria; 
}
function KeyData(){
	var Cr = SubmitForm();
	$("#CONID").autocomplete({
		source: "s_contractID_asset.php?criteria="+Cr,
        minLength:1
    });
}

$(document).ready(function(){
	$('#btn1').click(function(){
		var Cr = SubmitForm(); 
		
		window.location.href="frm_seize_asset.php?ConID="+ $("#CONID").val()+"&criteria="+Cr;
    });
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
</script>

<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>

<script language="JavaScript">
<!--
function windowOpen() {
var
myWindow=window.open('search2.php','windowRef','width=600,height=400');
if (!myWindow.opener) myWindow.opener = self;
}
//--></script>
    
<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}

.odd{
    background-color:#EDF8FE;
    font-size:12px
}
.even{
    background-color:#D5EFFD;
    font-size:12px
}
</style>
    
</head>
<body>
<form name="form1" id="form1" method="post" action="">
<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			<div style="text-align:center;padding-bottom: 10px;"><h2>(THCAP) Create งานยึด</h2></div>

			<fieldset><legend><B>ค้นหาข้อมูล</B></legend>

			<div class="ui-widget" align="center">
			
			<div style="margin:0">
				<label><b>ค้นหาโดย:</b></label>
				<input type="radio" name="criteria" id="criteria0" value="1" onchange="SubmitForm();"<?php echo $check0; ?>>เลขที่สัญญา, ชื่อ-สกุล, บัตรประจำตัว 
				<input type="radio" name="criteria" id="criteria1" value="2" onchange="SubmitForm();"<?php echo $check1; ?>>เลขทะเบียนรถ, เลขตัวถัง
				<input type="radio" name="criteria" id="criteria2" value="3" onchange="SubmitForm();"<?php echo $check2; ?>>แสดงเฉพาะชื่อผู้กู้หลัก 
			<div>
			<div style="margin:0">
				<input type="text" name="CONID" id="CONID" value="" size="70" onkeyup="KeyData();" onblur="KeyData();" />
				<input type="button" id="btn1" value="ค้นหา" />
			</div>

			<div id="panel" style="padding-top: 20px;"></div>

			</div>

			 </fieldset>

        </td>
    </tr>
</table>
</form>
<br>

<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			<?php
			include("frm_list_wait_create.php");
			?>
		</td>
    </tr>
</table>
</body>
</html>
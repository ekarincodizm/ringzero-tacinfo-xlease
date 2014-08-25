<?php
session_start();
set_time_limit(0);
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}

include("../../config/config.php");
$currentDate=nowDate();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
	<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
    
<script language=javascript>
$(document).ready(function(){
	$("#startDate").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
	$("#endDate").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
	
	$("#showdiv1").show();
	$("#showdiv2").hide();
    
    $(".static_class1").click(function(){
        if($(this).val()=="1"){
            $("#showdiv1").show();
			$("#showdiv2").hide();	
        }else if($(this).val()=="2"){
			$("#showdiv1").hide();
			$("#showdiv2").show();
			document.getElementById("s_idno").value="";
        }
    });
	
	$("#s_idno").autocomplete({
        source: "s_idno.php",
        minLength:1
    });
	
	$("#submitButton").click(function(){
        if(document.getElementById("condition1").checked){
			if( $('#s_idno').val() == "" ){
				alert('กรุณาระบุเลขที่สัญญา !');
				$('#s_idno').focus();
				return false;
			}
		}else if(document.getElementById("condition2").checked){
			if( $('#startDate').val() > $('#endDate').val() ){
				alert('วันที่เริ่มต้นต้องน้อยกว่าวันที่สิ้นสุด !');
				return false;
			}
		}
	});
});

</script>
</head>
<body>
<?php
if($startDate==""){
	$startDate=$currentDate;
}
if($endDate==""){
	$endDate=$currentDate;
}
?>
<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div class="header"><h1>รายการ NT ทั้งหมด</h1></div>
		<fieldset><legend><B>เงื่อนไขการค้นหา</B></legend>
			<form method="post" name="form1" action="frm_show.php">
			<table width="400" border="0" cellspacing="0" cellpadding="0" align="center">
				<tr>
					<td width="100" valign="top" height="30"><input type="radio" class="static_class1" name="condition" id="condition1" value="1" checked>เลขที่สัญญา</td>
					<td valign="top" height="30">
						<div id="showdiv1">
							<table cellpadding="3" cellspacing="0" border="0" width="100%">
							<tr>						
								<td><input type="text" name="s_idno" id="s_idno"></td>						
							</tr>
							</table>
						</div>
					</td>
				</tr>
				<tr>
					<td valign="top" height="30"><input type="radio" class="static_class1" name="condition" id="condition2" value="2">ทั้งหมด</td>
					<td valign="top" height="30">
						<div id="showdiv2">
							<table cellpadding="3" cellspacing="0" border="0" width="100%">
							<tr>						
								<td>
								ตั้งแต่ <input type="text" id="startDate" name="startDate" value="<?php echo $startDate;?>" size="15" style="text-align: center;">
								ถึง <input type="text" id="endDate" name="endDate" value="<?php echo $endDate;?>" size="15" style="text-align: center;">
								</td>						
							</tr>
							</table>
						</div>
					</td>
				</tr>
				<tr height="50"><td valign="top" align="center" colspan="2"><hr><input type="submit" value="ค้นหา" id="submitButton"></td></tr>
			</table>
			</form>
		</fieldset>
	</td>
</tr>
</table>
</body>
</html>
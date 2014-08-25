<?php
session_start();
include("../../config/config.php");
include("../function/emplevel.php");
$id_user = $_SESSION["av_iduser"];

$emplevel=emplevel($id_user);
$currentdate=nowDate();
$year=substr($currentdate,0,4);
$month=substr($currentdate,5,2);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>รายได้พิเศษของฉัน</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	<script src="../../jqueryui/js/number.js" type="text/javascript"></script>
<script language=javascript>
$(document).ready(function(){
	$('#btn1').click(function(){
		if($('#year').val()==""){
			alert("กรุณาระบุปีที่ต้องการแสดงรายงาน");
			$('#year').focus();
		}else{
			if($('#search1').is(':checked')){
				$('#panel').load('frm_ShowAll.php?year='+$('#year').val());
			}else if($('#search2').is(':checked')){
				$('#panel').load('frm_ShowMy.php?year='+$('#year').val());
			}
		}
		
		
	});
 
})
</script>

</head>
<body>
<form name="form1" method="post" action="frm_report.php">
<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>
			<div class="wrapper" style="width:800px;">
				<div align="center"><h2>รายได้พิเศษของฉัน</h2></div>
				<fieldset><legend><B>เงื่อนไขการแสดงรายงาน</B></legend>
					<div style="padding:20px;">
						<table width="600" border="0"  align="center">
						<tr>
							<td height="50" align="left" bgcolor="#CCFFCC">
							<fieldset><legend>เลือกปีที่แสดงรายงาน</legend>
							<div align="center">
							ปี ค.ศ. <input type="text" name="year" id="year" size="10" maxlength="4" value="<?php echo date('Y');?>" style="text-align:center;" onKeyPress="checknumber2(event)">
							</div>
							</fieldset>
							</td>
						</tr>
						<tr>
							<td height="50" align="center">
							<?php
							if($emplevel <= 1){
							?>
							<input type="radio" name="typesearch" id="search1" value="1" checked>แสดงของทุกคนที่ได้รับ 
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<?php } ?>
							<input type="radio" name="typesearch" id="search2" value="2" <?php if($emplevel>1) echo "checked"; ?>> แสดงเฉพาะของตนเอง </td>	
						</tr>
						<tr><td align="center"><br><input type="button" value="  OK  " id="btn1">&nbsp;<input type="button" value="CLOSE" onclick="javascript:window.close();"></td></tr>
						</table>
					</div>
				</fieldset>
			</div>
			<div id="panel" style="padding-top: 10px;"></div>
        </td>
    </tr>
</table>          
</form>
</body>
</html>
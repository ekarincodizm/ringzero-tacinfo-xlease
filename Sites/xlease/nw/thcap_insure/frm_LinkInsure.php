<?php
session_start();
include("../../config/config.php");

$method=$_GET["method"];
$id=$_GET["auto_id"];
if($method=="noapp"){
	$upchk="UPDATE thcap_insure_checkchip
	SET \"statusApp\"='4' WHERE auto_id=$id";
	if($reschk=pg_query($upchk)){
	}else{
		$status++;
	}
}



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) ประกันอัคคีภัย</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script language=javascript>
$(document).ready(function(){  
	$("#contractID").autocomplete({
        source: "s_contract2.php",
        minLength:2
    }); 
	
	 $('#btn1').click(function(){
        $("#panel").load("frm_showLinkInsure.php?contractID="+ $("#contractID").val());
    });
});

</script>
</head>
<body>
<style type="text/css">
	A:link {
		COLOR: #FF3366; TEXT-DECORATION: underline;
	}
	A:visited {
		COLOR: #0000FF; TEXT-DECORATION: underline;
	}
	A:hover {
		COLOR: #ff6600; TEXT-DECORATION: underline;
	} 
</style>
<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>
			<div class="wrapper" style="width:800px;">
				<div align="center"><h2>เพิ่มกรมธรรม์</h2></div>
				<div align="right"><input type="button" value="CLOSE" onclick="javascript:window.close();"></div>
				<fieldset><legend><B>เลือกเลขที่สัญญาที่ต้องการเชื่อม</B></legend>
					<div style="padding-top:20px;">
						<table width="100%" border="0"  align="center">
						<tr>
							<td align="center">
								<b>เลขที่สัญญา, เลขที่กรมธรรม์ : </b> 
								<input type="text" name="contractID" id="contractID" size="40"><input type="button" value="  ค้นหา  " id="btn1">
							</td>
						</tr>
						<tr><td align="center" colspan="2"><br>&nbsp;</td></tr>
						</table>
					</div>
				</fieldset><br>
			</div>	
        </td>
    </tr>
</table>  
<!-- แสดงรายการที่รออนุมัติ -->
<div id="panel"></div>        

</body>
</html>
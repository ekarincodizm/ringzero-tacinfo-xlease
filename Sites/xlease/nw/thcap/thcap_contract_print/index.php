<?php
include("../../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../../index.php");
    exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>(THCAP) พิมพ์สัญญา</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="../act.css"></link>
<link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>   
<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}

$(document).ready(function(){
	
 $("#conidserh").autocomplete({
        //source: "list_con.php",
		source: "s_contract.php",
        minLength:1
    });
	
 $('#schbut').click(function(){
		var aaaa = $("#conidserh").val();
		if(aaaa!=""){
			$("#panel").load("frm_button.php?conidserh="+aaaa);
		}else{		
			alert(" ระบุเลขที่สัญญาที่ต้องการด้วยครับ ");
		}
		
    });
	

});


</script>
<style type="text/css">
    #warppage
	{
	width:800px;
	margin-left:auto;
	margin-right:auto;
	
	min-height: 5em;
	background: rgb(255, 255, 255);
	padding: 5px;
	border: rgb(128, 128, 128) solid 0.5px;
	border-radius: .625em;
	-moz-border-radius: .625em;
	-webkit-border-radius: .625em;
	}
.style1 {
	font-size: small;
	font-weight: bold;
}
.style2 {
	font-size: medium;
	font-weight: bold;
}
</style>
</head>
<div style="margin-top:1px" ></div>
<body bgcolor="#F5F5F5">

<form name="frm" action="frm_data.php" method="post">
<table width="800" border="0" cellspacing="0" cellpadding="0"  align="center" bgcolor="#F5F5F5">
		<tr>
			<td  align="center" height="25px">
				<h1><b>(THCAP) พิมพ์สัญญา</b><h1>
			</td>
		</tr>
		<tr>
			<td  align="center" height="20px">
				<div id="warppage" style="width:600px;">										
							<div style="height:1px; width:100%; margin-top:17px;">
								<table width="100%" border="0" cellspacing="0" cellpadding="0"  align="left">
									<tr>
										<td width="80"></td>
										<td>
											<font color="gray">เลขที่สัญญา,เลขบัตรประชาชน,ชื่อลูกค้า</font>
										</td>
									</tr>	
									<tr>
										<td></td>
										<td>										
											<input type="text" name="conidserh" id="conidserh" size="50">
											<input type="button" name="schbut" id="schbut" value="ค้นหา" style="height:25px; width:70px;"/>
											<input name="button" type="button" onclick="window.close();" value="ปิด" style="height:25px; width:70px;"/>
										</td>
									</tr>	
								</table>	
							</div>						
				</div>
			</td>
		</tr>
</table>
</form>
<table width="700"  cellspacing="0" cellpadding="0"  align="center" >
		<tr>
			<td id="panel" style="padding-top: 5px;"></td>
		</tr>			
</table>
	

</body>
</html>
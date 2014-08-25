<?php
include("../../config/config.php");

$COID2 = $_POST["COID"];
?>

<head>
<title>อนุมัติสัญญาวิทยุ (ลูกค้านอก)</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="autocomplete.js"></script>
<link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<style type="text/css">
    #warppage
	{
	width:800px;
	margin-left:auto;
	margin-right:auto;
	
	min-height: 5em;
	background: rgb(240, 240, 240);
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
<script language=javascript>
function validate() {

var theMessage = "Please complete the following: \n-----------------------------------\n";
var noErrors = theMessage

if (document.form1.ContractStatus.value=="") {
    //theMessage = theMessage + "\n -->  กรุณาเลือกรายการ";
}
else if(document.form1.ContractStatus.value=="8" && document.form1.Remask.value==""){
	theMessage = theMessage + "\n -->  กรุณาใส่หมายเหตุด้วย ในกรณีที่ยกเลิก";
}

if (theMessage == noErrors) {
    return true;
}else{
    alert(theMessage);
    return false;
}

}
</script>
</head>

<body>
<div style="width:800px; height:auto; margin-left:auto; margin-right:auto;">
	<div id="warppage" style="width:800px; height:auto;">
		<div id="headerpage" style="height:10px; text-align:center"></div>
		<div class="style1" id="menu" style="height:30px; padding-left:10px; padding-top:10px; padding-right:10px;">อนุมัติสัญญาวิทยุ (ลูกค้านอก)<hr/></div>
		<div style="height:auto; padding-left:10px; padding-right:10px;"><br />
			<center><table BGCOLOR="#54FF9F">
			<?php
				$query=pg_query("select * from public.\"RadioContract\" where \"COID\" = '$COID2'");
						while($result=pg_fetch_array($query)){
							echo "<form method=post name=form1 action=PappvAddRadioContract.php>";
							$COID = $result["COID"];
							$RadioNum = $result["RadioNum"];
							$RadioCar = $result["RadioCar"];
							$RadioRelationID = $result["RadioRelationID"];
							}
							
								$query_rid=pg_query("select * from public.\"GroupCus_Active\" where \"GroupCusID\" = '$RadioRelationID' ");
								$numrow=pg_num_rows($query_rid);
								if($numrow==1)
								{
									while($result2=pg_fetch_array($query_rid)){
									$CusID = $result2["CusID"];
									}
										$query_name=pg_query("select * from public.\"Fa1\" where \"CusID\" = '$CusID' ");
											while($result3=pg_fetch_array($query_name)){
											$A_FIRNAME = $result3["A_FIRNAME"];
											$A_NAME = $result3["A_NAME"];
											$A_SIRNAME = $result3["A_SIRNAME"];
											}
								}
								else
								{
									$A_FIRNAME = "";
									$A_NAME = "";
									$A_SIRNAME = "";
								}
								
								echo "<tr><td align=\"right\">เลขที่สัญญาวิทยุ : </td><td> $COID2</td></tr>";
								echo "<tr><td align=\"right\">รหัสวิทยุ : </td><td> $RadioNum</td></tr>";
								echo "<tr><td align=\"right\">ทะเบียนรถ : </td><td> $RadioCar</td></tr>";
								echo "<tr><td align=\"right\">ชื่อ - นามสกุล : </td><td> $A_FIRNAME $A_NAME $A_SIRNAME</td></tr>";
								echo "<tr><td align=\"right\">กรุณาเลือกรายการ : </td><td>";
								echo "<select name=\"ContractStatus\">";
								echo "<option value=1>อนุมัติ</option>";
								echo "<option value=8>ยกเลิก</option>";
								echo "</select></td></tr>";
								//echo "<input type=\"radio\" name=\"ContractStatus\" value=\"1\">อนุมัติ<br>";
								//echo "<input type=\"radio\" name=\"ContractStatus\" value=\"8\">ยกเลิก</td></tr>";
								echo "<tr><td align=\"right\">หมายเหตุ : </td><td><textarea name=\"Remask\"></textarea></td></tr></table></center><br>";
								echo "<input type=\"hidden\" name=\"COID3\" value=\"$COID2\">";
								echo "<center><input type=\"submit\" name=\"select\" value=\" ยืนยัน \" onclick=\"return validate()\"> <input type=\"button\" value=\" ปิด\" onclick=\"window.location='appvAddRadioContract.php'\"></center>";
								echo "</form>";
			?>
			<!-- </table></center><br> -->
		</div>
		<div id="footerpage"></div>
	</div>
</div>
</body>
</html>
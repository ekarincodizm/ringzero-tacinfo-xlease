<?php
include("../../config/config.php");
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
<script language="JavaScript" type="text/JavaScript">
function MM_openBrWindow(theURL,winName,features) { //v2.0
window.open(theURL,winName,features);
}
</script>
</head>

<body>
<div style="width:auto; height:auto; margin-left:auto; margin-right:auto;">
	<div id="warppage" style="width:1000px; height:auto;">
		<div id="headerpage" style="height:10px; text-align:center"></div>
		<div class="style1" id="menu" style="height:30px; padding-left:10px; padding-top:10px; padding-right:10px;">อนุมัติสัญญาวิทยุ (ลูกค้านอก)<hr/></div>
		<div style="height:auto; padding-left:10px; padding-right:10px;"><br />
			<center><table>
			<!-- BORDER="1" BORDERCOLOR="#0000FF" -->
			<tr align=center bgcolor=#00BFFF><td><b>เลขที่สัญญาวิทยุ</b></td><td><b>รหัสวิทยุ</b></td><td><b>ทะเบียนรถยนต์</b></td><td><b>ชื่อ  - นามสกุล</b></td><td><b>ทำรายการอนุมัติ</b></td></tr>
			<?php
				$query=pg_query("select * from public.\"RadioContract\" where \"ContractStatus\" = '0' order by \"COID\" ");
				$i=0;
						while($result=pg_fetch_array($query)){
							echo "<form method=post name=form1 action=SelectAppvRadioContract.php>";
							$COID = $result["COID"];
							$RadioNum = $result["RadioNum"];
							$RadioCar = $result["RadioCar"];
							$RadioRelationID = $result["RadioRelationID"];
							/*$ContractStatus = $result["ContractStatus"];
							
							if($ContractStatus==0){$ContractStatus="อยู่ในระหว่างรออนุมัติสัญญา";}
							if($ContractStatus==1){$ContractStatus="สัญญาปกติ";}
							if($ContractStatus==2){$ContractStatus="อยู่ระหว่างขอระงับสัญญาณ";}
							if($ContractStatus==3){$ContractStatus="สัญญาณถูกระงับชั่วคราว";}
							if($ContractStatus==4){$ContractStatus="อยู่ระหว่างรอปลดการระงับสัญญาณ";}
							if($ContractStatus==8){$ContractStatus="สัญญาถูกยกเลิก (ไม่เคยเป็น Active)";}
							if($ContractStatus==9){$ContractStatus="ปิดสัญญาแล้ว";}*/
							
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
								if($i%2==0)
								{
								echo "<tr align=center bgcolor=#FFCCC5>";
								}
								else
								{
								echo "<tr align=center bgcolor=#FFFF99>";
								}
								echo "<td align=center>$COID</td>";
								echo "<td>$RadioNum</td>";
								echo "<td align=center>$RadioCar</td>";
								echo "<td align=left>$A_FIRNAME $A_NAME $A_SIRNAME</td>";
								echo "<input type=\"hidden\" name=\"COID\" value=\"$COID\">";
								echo "<td><input type=\"submit\" value=\"ทำรายการอนุมัติ\" /></td></tr>";
								//echo "<td><a href=\"vRadioContract.php\" name=\"s_idno_t\" text=\"$COID\">test</a></td></tr>";
								//echo "<td><a href=\"javascript:MM_openBrWindow('vRadioContract.php?name=$s_idno_t?id=a003','','width=100,height=100')\">test</a></td></tr>";
								echo "</form>";
						$i++;
						}
			?>
			</table></center><br>
		</div>		
	</div>
</div>
<div style="padding-top:20px;">
	<?php 
		$frmlimit = 't';
		include("frm_history.php"); 
	?>
</div>
</body>
</html>
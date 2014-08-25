<?php
include("../../config/config.php");

$coid = $_GET["s_idno_t"];
if($coid==""){
	$coid = $_POST["s_idno_t"];
}
$radionum = $_POST["s_radionum"];
$radiocar = $_POST["s_radiocar"];
$name = $_POST["s_name"];
$sirname = $_POST["s_sirname"];

$coid_hit = $coid;
$radionum_hit = $radionum;
$radiocar_hit = $radiocar;
$name_hit = $name;
$sirname_hit = $sirname;

$pagehit = $_POST["pagehit"];

$f_sql=pg_query("select * from public.\"RadioContract\" where \"COID\" = '$coid' or \"COID\" = '$radionum' or \"COID\" = '$radiocar' or \"COID\" = '$name' or \"COID\" = '$sirname' ");
$rownum=pg_num_rows($f_sql);
if($rownum==0)
{
	echo "<form method=\"post\" name=\"form1\" action=\"SvRadioContract.php\">";
	echo "<center><h1><b>ไม่พบข้อมูล!  <b></h1><h2>กรุณาทำรายการใหม่</h2></center><br>";
	echo "<input type=\"hidden\" name=\"coid2\" value=\"$coid\">";
	echo "<input type=\"hidden\" name=\"radionum2\" value=\"$radionum\">";
	echo "<input type=\"hidden\" name=\"radiocar2\" value=\"$radiocar\">";
	echo "<input type=\"hidden\" name=\"name2\" value=\"$name\">";
	echo "<input type=\"hidden\" name=\"sirname2\" value=\"$sirname\">";
	echo "<center><input type=\"submit\" value=\"    กลับ    \"></center>";
	echo "</form>";
}
else if($rownum > 1)
{
	echo "<form method=\"post\" name=\"form1\" action=\"SvRadioContract.php\">";
	echo "<center><h1><b>ผิดพลาด!  <b></h1><h2>กรุณาทำรายการใหม่</h2></center><br>";
	echo "<input type=\"hidden\" name=\"coid2\" value=\"$coid\">";
	echo "<input type=\"hidden\" name=\"radionum2\" value=\"$radionum\">";
	echo "<input type=\"hidden\" name=\"radiocar2\" value=\"$radiocar\">";
	echo "<input type=\"hidden\" name=\"name2\" value=\"$name\">";
	echo "<input type=\"hidden\" name=\"sirname2\" value=\"$sirname\">";
	echo "<center><input type=\"submit\" value=\"    กลับ    \"></center>";
	echo "</form>";
}
else
{
?>
<html>
<head>
<title>รายละเอียดสัญญาวิทยุ (ลูกค้านอก)</title>
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
.style3
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
</style>
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</head>

<body>
<div style="width:800px; height:auto; margin-left:auto; margin-right:auto;">
	<div id="warppage" style="width:800px; height:auto;">
		<div id="headerpage" style="height:10px; text-align:center"></div>
		<div class="style1" id="menu" style="height:30px; padding-left:10px; padding-top:10px; padding-right:10px;">รายละเอียดสัญญาวิทยุ (ลูกค้านอก)<hr/></div>
		<div style="height:auto; padding-left:10px; padding-right:10px;"><br />
		<form method="post" name="form1" action="SvRadioContract.php">
			<?php
			$L_sql=pg_query("select * from public.\"RadioContract\" where \"COID\" = '$coid' or \"COID\" = '$radionum' or \"COID\" = '$radiocar' or \"COID\" = '$name' or \"COID\" = '$sirname'");
			$rowtest=pg_num_rows($L_sql);
			if($rowtest==1)
			{
			while($result=pg_fetch_array($L_sql)){
							$COID=$result["COID"];
							$RadioNum=$result["RadioNum"];
							$RadioCar=$result["RadioCar"];
							$RadioRelationID=$result["RadioRelationID"];
							$ContractStatus=$result["ContractStatus"];
							}
			if($ContractStatus==0){$ContractStatus="อยู่ในระหว่างรออนุมัติสัญญา";}
			if($ContractStatus==1){$ContractStatus="สัญญาปกติ";}
			if($ContractStatus==2){$ContractStatus="อยู่ระหว่างขอระงับสัญญาณ";}
			if($ContractStatus==3){$ContractStatus="สัญญาณถูกระงับชั่วคราว";}
			if($ContractStatus==4){$ContractStatus="อยู่ระหว่างรอปลดการระงับสัญญาณ";}
			if($ContractStatus==8){$ContractStatus="สัญญาถูกยกเลิก (ไม่เคยเป็น Active)";}
			if($ContractStatus==9){$ContractStatus="ปิดสัญญาแล้ว";}
			
			$sql_name=pg_query("select \"Fa1\".\"A_FIRNAME\" , \"Fa1\".\"A_NAME\" , \"Fa1\".\"A_SIRNAME\" from public.\"GroupCus_Active\" , public.\"Fa1\" where \"GroupCus_Active\".\"CusID\" = \"Fa1\".\"CusID\" and \"GroupCus_Active\".\"GroupCusID\" = '$RadioRelationID'");
			while($resultV2=pg_fetch_array($sql_name)){
							$V_A_FIRNAME=$resultV2["A_FIRNAME"];
							$V_A_NAME=$resultV2["A_NAME"];
							$V_A_SIRNAME=$resultV2["A_SIRNAME"];
							}
			
			echo "<center><table>";
			echo "<tr><td align=\"right\"><h3><b>สถานะของสัญญา : </b></h3></td><td align=\"left\"><h3><b>$ContractStatus</b></h3></td></tr>";
			echo "<tr><td align=\"right\">รหัสสัญญาวิทยุ : </td><td align=\"left\">$COID</td></tr>";
			echo "<tr><td align=\"right\">รหัสวิทยุ : </td><td align=\"left\">$RadioNum</td></tr>";
			echo "<tr><td align=\"right\">ทะเบียนรถยนต์ : </td><td align=\"left\">$RadioCar</td></tr>";
			echo "<tr><td align=\"right\">ลูกค้า : </td><td align=\"left\">$V_A_FIRNAME $V_A_NAME $V_A_SIRNAME</td></tr></table></center>";
			?>
			<br><center><input type="button" value="    ปิด    " onclick="window.close();">  <input type="button" value="กลับ" onclick="window.location='SvRadioContract.php'"></center>
			</form>
			<?php
			}
			else
			{
				echo "<form method=\"post\" name=\"form4\" action=\"SvRadioContract.php\">";
				echo "<center><h1><b>ผิดพลาด!  <b></h1><h2>กรุณาทำรายการใหม่</h2></center><br>";
				echo "<input type=\"hidden\" name=\"coid2\" value=\"$coid\">";
				echo "<input type=\"hidden\" name=\"radionum2\" value=\"$radionum\">";
				echo "<input type=\"hidden\" name=\"radiocar2\" value=\"$radiocar\">";
				echo "<input type=\"hidden\" name=\"name2\" value=\"$name\">";
				echo "<input type=\"hidden\" name=\"sirname2\" value=\"$sirname\">";
				echo "<center><input type=\"submit\" value=\"    กลับ    \"></center>";
				echo "</form>";
			}
			?>
		</div>
		<div id="footerpage"></div>
	</div>
</div><br>

<!-- //////////////////////////////////// -->

<div style="width:900px; height:auto; margin-left:auto; margin-right:auto;">
	<div id="warppage" style="width:900px; height:auto;">
		<div id="headerpage" style="height:10px; text-align:center"></div>
		
		<form method="post" name="form2" action="vRadioContract.php">
		<?php
		echo "<input type=\"hidden\" name=\"pagehit\" value=\"1\">";
		echo "<input type=\"hidden\" name=\"s_radionum\" value=\"$radionum_hit\">";
		echo "<input type=\"hidden\" name=\"s_idno_t\" value=\"$coid_hit\">";
		echo "<input type=\"hidden\" name=\"s_radiocar\" value=\"$radiocar_hit\">";
		echo "<input type=\"hidden\" name=\"s_name\" value=\"$name_hit\">";
		echo "<input type=\"hidden\" name=\"s_sirname\" value=\"$sirname_hit\">";
		?>
		<center><input type="submit" value="แสดงประวัติการเปลี่ยนแปลงของสัญญา"></center>
		</form>
		
		<?php
		if($pagehit=="1")
		{
			$sql_hit=pg_query("select * from public.\"RadioContract_Bin\" where \"COID\" = '$COID' order by \"DoerStamp\" DESC , \"RadioRelationID\" DESC , \"ContractStatus\" DESC ");
			$row_hit=pg_num_rows($sql_hit);
			if($row_hit > 0)
			{
		?>
		
			<div class="style1" id="menu" style="height:30px; padding-left:10px; padding-top:10px; padding-right:10px;">ประวัติการเปลี่ยนแปลงของข้อมูลสัญญา<hr/></div>
				<div style="height:auto; padding-left:10px; padding-right:10px;"><br>
				
				<?php
				echo "<certer><table border=\"0\" cellSpacing=\"1\" cellPadding=\"3\" align=\"center\" bgcolor=\"#D0D0D0\">";
				echo "<tr bgcolor=\"#79BCFF\"><th>ครั้งที่</th><th>ผู้ทำรายการ</th><th>วันเวลาทำรายการ</th><th>ผู้อนุมัติรายการ</th><th>วันเวลาอนุมัติรายการ</th><th>ผลการแก้ไข</th><th>ดูข้อมูล</th></tr>";
				
				//$sql_hit=pg_query("select * from public.\"RadioContract_Bin\" where \"COID\" = '$COID' order by \"DoerStamp\" DESC , \"RadioRelationID\" DESC , \"ContractStatus\" DESC ");
				//$row_hit=pg_num_rows($sql_hit);
				$i = $row_hit;
					while($result_hit=pg_fetch_array($sql_hit))
					{
						$DoerID=$result_hit["DoerID"];
						$DoerStamp=$result_hit["DoerStamp"];
						$AppvID=$result_hit["AppvID"];
						$AppvStamp=$result_hit["AppvStamp"];
						$COID=$result_hit["COID"];
						$RadioNum=$result_hit["RadioNum"];
						$RadioCar=$result_hit["RadioCar"];
						$RadioRelationID=$result_hit["RadioRelationID"];
						$ContractStatus=$result_hit["ContractStatus"];
						$ContractStatus_hit=$ContractStatus;
							if($ContractStatus==0){$ContractStatus="อยู่ในระหว่างรออนุมัติสัญญา";}
							if($ContractStatus==1){$ContractStatus="สัญญาปกติ";}
							if($ContractStatus==2){$ContractStatus="อยู่ระหว่างขอระงับสัญญาณ";}
							if($ContractStatus==3){$ContractStatus="สัญญาณถูกระงับชั่วคราว";}
							if($ContractStatus==4){$ContractStatus="อยู่ระหว่างรอปลดการระงับสัญญาณ";}
							if($ContractStatus==8){$ContractStatus="สัญญาถูกยกเลิก (ไม่เคยเป็น Active)";}
							if($ContractStatus==9){$ContractStatus="ปิดสัญญาแล้ว";}
						$i--;
						
						$sql_user=pg_query("select * from public.\"fuser\" where \"id_user\" = '$DoerID'");
						while($result_user=pg_fetch_array($sql_user))
						{
							$fname_doer=$result_user["fname"];
						}
						
						$sql_appv=pg_query("select * from public.\"fuser\" where \"id_user\" = '$AppvID'");
						while($result_appv=pg_fetch_array($sql_appv))
						{
							$fname_appv=$result_appv["fname"];
						}
						
						echo "<tr bgcolor=\"#F5F5DC\"><td align=\"center\">$i</td><td align=\"center\">$fname_doer</td><td align=\"center\">$DoerStamp</td><td align=\"center\">$fname_appv</td><td align=\"center\">$AppvStamp</td><td align=\"center\">$ContractStatus</td>
							<td align=\"center\"><a href=\"#\" onclick=\"javascript:popU('detail_radio.php?cid=$COID&rn=$RadioNum&rc=$RadioCar&rri=$RadioRelationID&cs=$ContractStatus_hit&i=$i','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=400')\"><u>ดูข้อมูล</u></a></td></tr>";
					}
					
				echo "</table></center><br>";
			}
			else
			{
			?>
				<div style="height:auto; padding-left:10px; padding-right:10px;"><br>
			<?php
				echo "<center><h2><b>ไม่มีประวัติการเปลี่ยนแปลงของสัญญา</b></h2></center><br>";
			}
		}
				?>
				
				</div>
	<div id="footerpage"></div>
</div>
</div>

<!-- //////////////////////////////////// -->

</body>
</html>
<?php	
}
?>
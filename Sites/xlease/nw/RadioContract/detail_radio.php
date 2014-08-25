<?php
include("../../config/config.php");

$coid = $_GET["cid"];
$radionum = $_GET["rn"];
$radiocar = $_GET["rc"];
$rri = $_GET["rri"];
$cs = $_GET["cs"];
$i = $_GET["i"];

?>

<div style="width:450px; height:auto; margin-left:auto; margin-right:auto;">
	<div id="warppage" style="width:450px; height:auto;">
		<div id="headerpage" style="height:10px; text-align:center"></div>
		<div class="style1" id="menu" style="height:30px; padding-left:10px; padding-top:10px; padding-right:10px;">รายละเอียดประวัติการเปลี่ยนแปลงของสัญญา ครั้งที่ <?php echo $i;?><hr/></div>
		<div style="height:auto; padding-left:10px; padding-right:10px;"><br />
		<form method="post" name="form1" action="SvRadioContract.php">
			<?php
			$L_sql=pg_query("select * from public.\"RadioContract_Bin\" where \"COID\" = '$coid' and \"RadioNum\" = '$radionum' and \"RadioCar\" = '$radiocar' and \"RadioRelationID\" = '$rri' and \"ContractStatus\" = '$cs'");
			$rowtest=pg_num_rows($L_sql);
			if($rowtest==1)
			{
			while($result=pg_fetch_array($L_sql)){
							$COID=$result["COID"];
							$RadioNum=$result["RadioNum"];
							$RadioCar=$result["RadioCar"];
							$RadioRelationID=$result["RadioRelationID"];
							$ContractStatus=$result["ContractStatus"];
							$DoerID=$result["DoerID"];
							$DoerStamp=$result["DoerStamp"];
							$AppvID=$result["AppvID"];
							$AppvStamp=$result["AppvStamp"];
							}
			if($ContractStatus==0){$ContractStatus="อยู่ในระหว่างรออนุมัติสัญญา";}
			if($ContractStatus==1){$ContractStatus="สัญญาปกติ";}
			if($ContractStatus==2){$ContractStatus="อยู่ระหว่างขอระงับสัญญาณ";}
			if($ContractStatus==3){$ContractStatus="สัญญาณถูกระงับชั่วคราว";}
			if($ContractStatus==4){$ContractStatus="อยู่ระหว่างรอปลดการระงับสัญญาณ";}
			if($ContractStatus==8){$ContractStatus="สัญญาถูกยกเลิก (ไม่เคยเป็น Active)";}
			if($ContractStatus==9){$ContractStatus="ปิดสัญญาแล้ว";}
			
			$sql_name=pg_query("select \"Fa1\".\"A_FIRNAME\" , \"Fa1\".\"A_NAME\" , \"Fa1\".\"A_SIRNAME\" from public.\"GroupCus_Bin\" , public.\"Fa1\" where \"GroupCus_Bin\".\"CusID\" = \"Fa1\".\"CusID\" and \"GroupCus_Bin\".\"GroupCusID\" = '$rri'");
			while($resultV2=pg_fetch_array($sql_name)){
							$V_A_FIRNAME=$resultV2["A_FIRNAME"];
							$V_A_NAME=$resultV2["A_NAME"];
							$V_A_SIRNAME=$resultV2["A_SIRNAME"];
							}
			if($V_A_NAME=="")
			{
				$sql_name2=pg_query("select \"Fa1\".\"A_FIRNAME\" , \"Fa1\".\"A_NAME\" , \"Fa1\".\"A_SIRNAME\" from public.\"GroupCus_Active\" , public.\"Fa1\" where \"GroupCus_Active\".\"CusID\" = \"Fa1\".\"CusID\" and \"GroupCus_Active\".\"GroupCusID\" = '$rri'");
				while($resultV3=pg_fetch_array($sql_name2)){
							$V_A_FIRNAME=$resultV3["A_FIRNAME"];
							$V_A_NAME=$resultV3["A_NAME"];
							$V_A_SIRNAME=$resultV3["A_SIRNAME"];
							}
			}
			
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
			
			echo "<center><table>";
			echo "<tr><td align=\"right\">สถานะของสัญญา : </td><td align=\"left\">$ContractStatus</td></tr>";
			echo "<tr><td align=\"right\">รหัสสัญญาวิทยุ : </td><td align=\"left\">$COID</td></tr>";
			echo "<tr><td align=\"right\">รหัสวิทยุ : </td><td align=\"left\">$RadioNum</td></tr>";
			echo "<tr><td align=\"right\">ทะเบียนรถยนต์ : </td><td align=\"left\">$RadioCar</td></tr>";
			echo "<tr><td align=\"right\">ลูกค้า : </td><td align=\"left\">$V_A_FIRNAME $V_A_NAME $V_A_SIRNAME</td></tr>";
			echo "<tr><td align=\"right\">ผู้ทำรายการ : </td><td align=\"left\">$fname_doer</td></tr>";
			echo "<tr><td align=\"right\">วันเวลาทำรายการ : </td><td align=\"left\">$DoerStamp</td></tr>";
			echo "<tr><td align=\"right\">ผู้อนุมัติ : </td><td align=\"left\">$fname_appv</td></tr>";
			echo "<tr><td align=\"right\">วันเวลาอนุมัติ : </td><td align=\"left\">$AppvStamp</td></tr></table></center>";
			}
			?>
			<br><br><center><input type="button" value="    ปิด    " onclick="window.close();"></center>
		</div>
	</div>
</div>

<?php
/*
	while($result=pg_fetch_array($L_sql))
	{
		$COID=$result["COID"];
		$RadioNum=$result["RadioNum"];
		$RadioCar=$result["RadioCar"];
		$RadioRelationID=$result["RadioRelationID"];
		$ContractStatus=$result["ContractStatus"];
	}
	*/
?>
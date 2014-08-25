<?php
include("../../config/config.php");

set_time_limit(60);

$host="172.16.2.5";
$dbmysqlname="ta_tal_1r4_dev";
$dbuser="ta_auto";
$dbpass="ta_auto";
$connect_db = mysql_connect($host,$dbuser,$dbpass)or die ("Cannot connect to MySQL Database");

// เชื่อมต่อกับฐานข้อมูลของ DATABASE MySql
mysql_select_db($dbmysqlname,$connect_db);
mysql_query("SET NAMES 'UTF8'");

?>

<style type="text/css">
.odd{
    background-color:#FFFFFF;
    font-size:12px
}
.even{
    background-color:#F0F0F0;
    font-size:12px
}
</style>


<div align="right">
	<form action="frm_pdf.php" method="post" name="form1" target="_blank">
		<input type="submit" id="printL" value="พิมพ์">
	</form>
</div>

<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold; text-align:center" valign="top" bgcolor="#5E99CC">
      <!-- <td>รหัสการชำระค่าเข้าร่วม</td> -->
      <td>เลขทะเบียนรถ</td>
      <!-- <td>รหัสสัญญา</td>
	  <td>รหัสชุดลูกค้า</td>
      <td>ชื่อ-นามสกุลลูกค้า</td> -->
	</tr>

<?php
$t = 0;
$irow = 0;
$sql_origin = mysql_query("SELECT distinct car_license , ta_join_pm_id , contract_id , cpro_id , cpro_name FROM ta_join_main_bin order by car_license");
$numrow_origin = mysql_num_rows($sql_origin);
while($res=mysql_fetch_array($sql_origin)) // ค้นหาข้อมูลทั้งหมดในตาราง  ta_join_main_bin
{
	$ta_join_pm_id = $res["ta_join_pm_id"];
	$car_license = $res["car_license"];
	$contract_id = $res["contract_id"];
	$cpro_id = $res["cpro_id"];
	$cpro_name = $res["cpro_name"];

	$sql_main = mysql_query("SELECT * FROM ta_join_main
							where car_license = '$car_license' ");
	$numrow_main = mysql_num_rows($sql_main);
	if($numrow_main == 0) // ถ้าตาราง ta_join_main_bin มีข้อมูล แต่ตาราง ta_join_main ไม่มีข้อมูลให้แสดงออกมา
	{
		$t++;
		$irow+=1;
		if($irow%2==0)
		{
			echo "<tr class=\"odd\">";
		}
		else
		{
			echo "<tr class=\"even\">";
		}
?>
			<!-- <td align="center"><?php //echo "$ta_join_pm_id"; ?></td> -->
			<td align="center"><?php echo "$car_license"; ?></td>
			<!-- <td align="center"><?php //echo "$contract_id"; ?></td>
			<td align="center"><?php //echo "$cpro_id"; ?></td>
			<td align="center"><?php //echo "$cpro_name"; ?></td> -->
		</tr>
<?php
	}
}


$sql_three = mysql_query("SELECT distinct car_license , ta_join_payment_id , contract_id , cpro_id , cpro_name FROM ta_join_payment order by car_license");
while($res_3 = mysql_fetch_array($sql_three)) // ค้นหาข้อมูลทั้งหมดในตาราง  ta_join_payment
{
	$ta_join_pm_id_three = $res_3["ta_join_payment_id"];
	$car_license_three = $res_3["car_license"];
	$contract_id_three = $res_3["contract_id"];
	$cpro_id_three = $res_3["cpro_id"];
	$cpro_name_three = $res_3["cpro_name"];
	
	$sql_one = mysql_query("SELECT * FROM ta_join_main_bin
							where car_license = '$car_license_three' ");
	$numrow_one = mysql_num_rows($sql_one);
	if($numrow_one > 0) // เช็คก่อนว่าในตาราง ta_join_payment มีข้อมูลเหมือนในตาราง ta_join_main_bin ถ้ามีแสดงว่าเคยทดสอบไปแล้วไม่ต้องทดสอบอีก
	{}
	else // ถ้าไม่มีให้ไปทดสอบความถูกต้องที่ตาราง ta_join_main
	{
		$sql_main = mysql_query("SELECT * FROM ta_join_main
							where car_license = '$car_license_three' ");
		$numrow_main = mysql_num_rows($sql_main);
		if($numrow_main == 0) // ถ้าตาราง ta_join_payment มีข้อมูล แต่ตาราง ta_join_main ไม่มีข้อมูลให้แสดงออกมา
		{
			$t++;
			$irow+=1;
			if($irow%2==0)
			{
				echo "<tr class=\"odd\">";
			}
			else
			{
				echo "<tr class=\"even\">";
			}
?>
				<!-- <td align="center"><?php //echo "$ta_join_pm_id_three"; ?></td> -->
				<td align="center"><?php echo "$car_license_three"; ?></td>
				<!-- <td align="center"><?php //echo "$contract_id_three"; ?></td>
				<td align="center"><?php //echo "$cpro_id_three"; ?></td>
				<td align="center"><?php //echo "$cpro_name_three"; ?></td> -->
			</tr>
<?php
		}
	}
}


$sql_four = mysql_query("SELECT distinct car_license , ta_join_payment_id , contract_id , cpro_id , cpro_name FROM ta_join_payment_bin order by car_license");
while($res_4 = mysql_fetch_array($sql_four)) // ค้นหาข้อมูลทั้งหมดในตาราง  ta_join_payment_bin
{
	$ta_join_pm_id_four = $res_4["ta_join_payment_id"];
	$car_license_four = $res_4["car_license"];
	$contract_id_four = $res_4["contract_id"];
	$cpro_id_four = $res_4["cpro_id"];
	$cpro_name_four = $res_4["cpro_name"];
	
	$sql_one = mysql_query("SELECT * FROM ta_join_main_bin
							where car_license = '$car_license_four' ");
	$numrow_one = mysql_num_rows($sql_one);
	if($numrow_one > 0) // เช็คก่อนว่าในตาราง ta_join_payment_bin มีข้อมูลเหมือนในตาราง ta_join_main_bin ถ้ามีแสดงว่าเคยทดสอบไปแล้วไม่ต้องทดสอบอีก
	{}
	else // ถ้าไม่มีให้ไปทดสอบความถูกต้องที่ตาราง ta_join_main
	{
		$sql_two = mysql_query("SELECT * FROM ta_join_payment
							where car_license = '$car_license_four' ");
		$numrow_two = mysql_num_rows($sql_two);
		if($numrow_two > 0) // เช็คก่อนว่าในตาราง ta_join_payment_bin มีข้อมูลเหมือนในตาราง ta_join_payment ถ้ามีแสดงว่าเคยทดสอบไปแล้วไม่ต้องทดสอบอีก
		{}
		else
		{
			$sql_main = mysql_query("SELECT * FROM ta_join_main
									where car_license = '$car_license_four' ");
			$numrow_main = mysql_num_rows($sql_main);
			if($numrow_main == 0) // ถ้าตาราง ta_join_payment มีข้อมูล แต่ตาราง ta_join_main ไม่มีข้อมูลให้แสดงออกมา
			{
				$t++;
				$irow+=1;
				if($irow%2==0)
				{
					echo "<tr class=\"odd\">";
				}
				else
				{
					echo "<tr class=\"even\">";
				}
?>
					<!-- <td align="center"><?php //echo "$ta_join_pm_id_four"; ?></td> -->
					<td align="center"><?php echo "$car_license_four"; ?></td>
					<!-- <td align="center"><?php //echo "$contract_id_four"; ?></td>
					<td align="center"><?php //echo "$cpro_id_four"; ?></td>
					<td align="center"><?php //echo "$cpro_name_four"; ?></td> -->
				</tr>
<?php
			}
		}
	}
}
	
echo "จำนวนทะเบียนรถที่ผิดปกติ จำนวน : $t ทะเบียน";
?>
</table>
<?php
session_start();
include("../../config/config.php");
 ?>
 
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 
<?php
									
$value=$_GET['value'];
$brand=$_GET['brand'];
$z = 1;

if($value == 'down'){

//ชื่อรุ่น	
	$sqlname = pg_query("select distinct \"brand\" from \"Fp_package\" where \"numtest\" = '$brand'");
	$name = pg_fetch_array($sqlname);
//แสดงข้อมูล
	$down= pg_query("select distinct \"down_payment\" from \"Fp_package\" where \"numtest\" = '$brand' order by \"down_payment\" DESC ");
	$row = pg_num_rows($down);
	
	
	
	if($row == 0){
	}else{
?>
		<form name="frm" action="package_edit_down_query.php" method="POST">
		<!--Hidden data-->
		<input type="hidden" name="numtest" id="numtest" value="<?php echo $brand; ?>">
			<table width="900" border="0" cellspacing="0" cellpadding="0"  align="center">
				<tr>
					<td align="center">
					<legend><h2><B> แก้ไข รถยนต์  <?php echo $name['brand']; ?></B></h2></legend>
						<div align="center">
						<div class="style5" style="width:auto; height:40px; padding-left:10px;">			
							<table width="500" cellSpacing="0" frame="border" cellPadding="0" >
							   <tr bgcolor="#FFCCAA">
									<td align="center" colspan="3">
										ราคาดาวน์
									</td>
								 </tr>
								 <tr>
									<td></td>
								 </tr>
							   <?php while($downcar = pg_fetch_array($down)){ ?>								 
								 <tr>
									<td width="250" align="right">
										<?php echo $z.". " ;?>
									</td>
									<td width="200" align="center">
										<input type="text" name="down[]" id="down" value="<?php echo $downcar['down_payment'];?>">
										<input type="hidden" name="downsame[]" id="downsame[]" value="<?php echo $downcar['down_payment'];?>">
									</td>
									<td width="250">บาท</td>
								 </tr>
							   <?php $z++; } ?>
								 <tr>
									<td align="center" colspan="3" bgcolor="#FFCCAA">
										<input type="submit" value="บันทึก"> <input type="button" value="ยกเลิก" onclick="window.close()">
									</td>
									
								 </tr>
							</table>
						</div>
						</div>
					</td>
				</tr>
			</table>	
		</form>

<?php
	
	}
					 
}else if($value == 'period'){

	$period = $_GET['period'];
	
	//แสดงชื่อรุ่น	
	$sqlname = pg_query("select distinct \"brand\",\"price_not_accessory\" from \"Fp_package\" where \"numtest\" = '$brand'");
	$name = pg_fetch_array($sqlname);
	
	//แสดงข้อมูล
	$periodsql = pg_query("select distinct \"period\",\"down_payment\" from \"Fp_package\" where \"numtest\" = '$brand' and \"month_payment\" = '$period' order by \"down_payment\" DESC ");
	$row = pg_num_rows($periodsql);
	
	
	if($row == 0){
	
	}else{
?>
		<form name="frm" action="package_edit_period_query.php" method="POST">
		<!--Hidden data-->
		<input type="hidden" name="numtest" id="numtest" value="<?php echo $brand; ?>">
		<input type="hidden" name="time" id="time" value="<?php echo $period; ?>">
			<table width="900" border="0" cellspacing="0" cellpadding="0"  align="center">
				<tr>
					<td align="center">
					<legend><h2><B> แก้ไข รถยนต์  <?php echo $name['brand']; ?></B></h2></legend>
						<div align="center">
						<div class="style5" style="width:auto; height:40px; padding-left:10px;">			
							<table width="500" cellSpacing="0" border="0" cellPadding="0" >
							    <tr bgcolor="#FFCCAA">
									<td align="center" colspan="4">
										ราคาต่องวด ทั้งหมด <?php echo $period ?> งวด
									</td>
								 </tr >
								  <!--<tr bgcolor="#FFCCAA">
									<td align="center" colspan="4">
										ราคารถยนต์ <?php //echo $pricecar = number_format($name['price_not_accessory']);?> บาท
									</td>
								 </tr>-->
								 <tr><td></td></tr>
							   <?php while($period_payment = pg_fetch_array($periodsql)){ ?>								 
								 <tr>
									<td width="250" align="right">
										ดาวน์ <?php echo $periodperice = number_format($period_payment['down_payment']);?>
										<input type="hidden" name="down[]" id="down[]" value="<?php echo $period_payment['down_payment'];?>">
									</td>
									<td width="250" align="right">
										จ่ายงวดละ
									</td>
									<td width="200" align="center">
										<input type="text" name="period[]" id="period" value="<?php echo $period_payment['period'];?>">
										<input type="hidden" name="periodsame[]" id="periodsame[]" value="<?php echo $period_payment['period'];?>">
									</td>
									<td width="250">บาท</td>
								 </tr>
							   <?php $z++; } ?>
								 <tr>
									<td align="center" colspan="4" bgcolor="#FFCCAA">
										<input type="submit" value="บันทึก"> <input type="button" value="ยกเลิก" onclick="window.close()">
									</td>
									
								 </tr>
							</table>
						</div>
						</div>
					</td>
				</tr>
			</table>	
		</form>

<?php
	
	}

}
									
?>
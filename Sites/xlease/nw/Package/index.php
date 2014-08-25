<?php
session_start();
include("../../config/config.php");
									



	$checkbrand = pg_query("select distinct \"numtest\" from \"Fp_package\"");
	$row = pg_num_rows($checkbrand);
					 

									
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>- ซื้อรถ -</title>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
};
</script>
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
</head>

<body bgcolor="#EEF2F7">
<form name="frm">

	<table width="900" border="0" cellspacing="0" cellpadding="0"  align="center">
		<tr>
			<td align="center">
			<legend><h2><B> ราคาและรายละเอียดของรถยนต์แบบ Package </B></h2></legend>
				<div align="center">
				<div class="style5" style="width:auto; height:40px; padding-left:10px;">			
					<table width="810" cellSpacing="0" frame="border" cellPadding="0" >
						
	
	<?php	for($i=1;$i<=$row;$i++){ ?>
							
						<tr bgcolor="#FFCCFF">
							<td align="center" style="height:30px;">
							
							<?php	$brand = pg_query("select distinct \"brand\",\"price_not_accessory\" from \"Fp_package\" where \"numtest\" = $i ");
									$brandname = pg_fetch_array($brand);
											
											
											
							?>	
							
							
								<h3><?php echo $brandname['brand'];?></h3>
							</td>							
							<td align="center" rowspan="2">
								<table width="650" cellSpacing="0" border="1" cellPadding="0" >
									<tr bgcolor="#66CCFF">			
											<th width="250"> <div align="center">เงินดาวน์</div></th>
											<th width="150"> <div align="center">24เดือน</div></th>
											<th width="150"> <div align="center">30เดือน</div></th>
											<th width="150"> <div align="center">36เดือน</div></th>
											<th width="150"> <div align="center">42เดือน</div></th>
											<th width="150"> <div align="center">48เดือน</div></th>
											<th width="150"> <div align="center">54เดือน</div></th>
											<th width="150"> <div align="center">60เดือน</div></th>	
											<th width="150"> <div align="center">66เดือน</div></th>
									</tr>
								<!-- เงินดาวน์ ---------------------------------------------------------->											
									<?php	$objQuery = pg_query("select distinct \"down_payment\" from \"Fp_package\" where \"numtest\" = $i  order by  \"down_payment\" DESC");
											while($objResuut = pg_fetch_array($objQuery))
											{ 
											$down = $objResuut["down_payment"];
											
									?>		
									<tr bgcolor="#EEF2F7">
		
											<td align="center"><?php echo $down;?></td>
								<!-- 24 งวด ---------------------------------------------------------->			
									<?php	$objQuery24 = pg_query("select \"period\" from \"Fp_package\" where \"numtest\" = $i and \"month_payment\" = 24 and \"down_payment\" = '$down' order by  \"down_payment\" DESC");
											$objResuut24 = pg_fetch_array($objQuery24);
											$price24 = number_format($objResuut24['period']);
									?>	
											<td align="center"><?php echo $price24;?></td>
								<!-- 30 งวด ---------------------------------------------------------->	
									<?php	$objQuery30 = pg_query("select \"period\" from \"Fp_package\" where \"numtest\" = $i and \"month_payment\" = 30 and \"down_payment\" = '$down' order by  \"down_payment\" DESC");
											$objResuut30 = pg_fetch_array($objQuery30);
											$price30 = number_format($objResuut30['period']);
									?>	
											<td align="center"><?php echo $price30;?></td>
								<!-- 36 งวด ---------------------------------------------------------->
									<?php	$objQuery36 = pg_query("select \"period\" from \"Fp_package\" where \"numtest\" = $i and \"month_payment\" = 36 and \"down_payment\" = '$down' order by  \"down_payment\" DESC");
											$objResuut36 = pg_fetch_array($objQuery36);
											$price36 = number_format($objResuut36['period']);
									?>	
											<td align="center"><?php echo $price36;?></td>
								<!-- 42 งวด ---------------------------------------------------------->
									<?php	$objQuery42 = pg_query("select \"period\" from \"Fp_package\" where \"numtest\" = $i and \"month_payment\" = 42 and \"down_payment\" = '$down' order by  \"down_payment\" DESC");
											$objResuut42 = pg_fetch_array($objQuery42);
											$price42 = number_format($objResuut42['period']);
									?>	
											<td align="center"><?php echo $price42;?></td>
								<!-- 48 งวด ---------------------------------------------------------->
									<?php	$objQuery48 = pg_query("select \"period\" from \"Fp_package\" where \"numtest\" = $i and \"month_payment\" = 48 and \"down_payment\" = '$down' order by  \"down_payment\" DESC");
											$objResuut48 = pg_fetch_array($objQuery48);
											$price48 = number_format($objResuut48['period']);
									?>	
											<td align="center"><?php echo $price48;?></td>									
								<!-- 54 งวด ---------------------------------------------------------->
									<?php	$objQuery54 = pg_query("select \"period\" from \"Fp_package\" where \"numtest\" = $i and \"month_payment\" = 54 and \"down_payment\" = '$down' order by  \"down_payment\" DESC");
											$objResuut54 = pg_fetch_array($objQuery54);
											$price54 = number_format($objResuut54['period']);
									?>	
											<td align="center"><?php echo $price54;?></td>
								<!-- 60 งวด ---------------------------------------------------------->
									<?php	$objQuery60 = pg_query("select \"period\" from \"Fp_package\" where \"numtest\" = $i and \"month_payment\" = 60 and \"down_payment\" = '$down' order by  \"down_payment\" DESC");
											$objResuut60 = pg_fetch_array($objQuery60);
											$price60 = number_format($objResuut60['period']);
									?>
											<td align="center"><?php echo $price60;?></td>
								<!-- 66 งวด ---------------------------------------------------------->
									<?php	$objQuery66 = pg_query("select \"period\" from \"Fp_package\" where \"numtest\" = $i and \"month_payment\" = 66 and \"down_payment\" = '$down' order by  \"down_payment\" DESC");
											$objResuut66 = pg_fetch_array($objQuery66);
											$price66 = number_format($objResuut66['period']);
									?>	
											<td align="center"><?php echo $price66;?></td>
									</tr>
									<?php	} ?>
									<tr bgcolor="#66AAFF">			
											<td align="center"><input type="button" value="แก้ไข" style="width:50px; height:20px" onclick="javascript:popU('package_edit.php?value=down&brand=<?php echo  $i?>')"></td>
											<td align="center"><input type="button" value="แก้ไข" style="width:50px; height:20px" onclick="javascript:popU('package_edit.php?value=period&brand=<?php echo  $i?>&period=24')"></td>
											<td align="center"><input type="button" value="แก้ไข" style="width:50px; height:20px" onclick="javascript:popU('package_edit.php?value=period&brand=<?php echo  $i?>&period=30')"></td>
											<td align="center"><input type="button" value="แก้ไข" style="width:50px; height:20px" onclick="javascript:popU('package_edit.php?value=period&brand=<?php echo  $i?>&period=36')"></td>
											<td align="center"><input type="button" value="แก้ไข" style="width:50px; height:20px" onclick="javascript:popU('package_edit.php?value=period&brand=<?php echo  $i?>&period=42')"></td>
											<td align="center"><input type="button" value="แก้ไข" style="width:50px; height:20px" onclick="javascript:popU('package_edit.php?value=period&brand=<?php echo  $i?>&period=48')"></td>
											<td align="center"><input type="button" value="แก้ไข" style="width:50px; height:20px" onclick="javascript:popU('package_edit.php?value=period&brand=<?php echo  $i?>&period=54')"></td>
											<td align="center"><input type="button" value="แก้ไข" style="width:50px; height:20px" onclick="javascript:popU('package_edit.php?value=period&brand=<?php echo  $i?>&period=60')"></td>
											<td align="center"><input type="button" value="แก้ไข" style="width:50px; height:20px" onclick="javascript:popU('package_edit.php?value=period&brand=<?php echo  $i?>&period=66')"></td>
									</tr>
										
								</table>	
							</td>
						</tr>
						<tr bgcolor="#CCCCFF">
							<td align="center" style="height:30px;">
								<h3>ราคา : <?php $pricecar = number_format($brandname['price_not_accessory']);
								
								echo $pricecar;?></h3>
							</td>
							
						</tr>
						<tr bgcolor="#CCCCFF">
							<td colspan="2">
								<br>
							</td>
							
							
						</tr>
						
				<?php } ?>		
					</table>
					
					<table width="900" cellSpacing="0"  cellPadding="0" >
					<tr>
							<td><br></td>
							<td><br></td>
							<td><br></td>
					</tr>
					<tr>
						<td align="right">
							<input type="button" value="เพิ่ม" style="width:250px; height:80px" onclick="javascript:popU('package_add.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=500')">
						</td>
						<td>
							<center><h4>Thaiace Capital</h4></center>
							<hr width="200">
						</td>
						<td align="left">
							<input type="button" value="รายการ อนุมัติ" style="width:250px; height:80px" onclick="javascript:popU('package_approve_list.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=560')">
						</td>
					</tr>
					<tr>
							<td><br></td>
							<td><br></td>
							<td><br></td>
					</tr>
					</table>
					
				</div>
				</div>				
			</td>
		</tr>						
	</table>

				

</form>
<div id="panel1" style="padding-top: 10px;"></div>
</html>

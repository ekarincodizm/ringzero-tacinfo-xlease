<?php
session_start();
include("../../config/config.php");
																	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>- อนุมัติ Package -</title>
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
<form name="frm" method="POST">

	<table width="900" border="0" cellspacing="0" cellpadding="0"  align="center">
		<tr>
			<td align="center">
				<table width="950" cellSpacing="0" border="0" cellPadding="0" >
					<tr>
						<td align="center">

<?php 
						$app = pg_query("select * from \"approve_Fp_package\"  where \"period\" is not null and status = 0 order by \"appfpackID\" DESC");
						$rows = pg_num_rows($app);
					?>
		
					<legend><h4><B> แก้ไข Package รอการอนุมัติเงินค่างวด </B></h4></legend>
					<?php 	if($rows != 0){ ?>
					<table width="700" cellSpacing="0" border="1" cellPadding="0" >
						<tr bgcolor="#66CCFF">	
						
					
								<th width="350"> <div align="center">ยี่ห้อ/รุ่น</div></th>
								<th width="150"> <div align="center">จำนวนงวด</div></th>
								<th width="250"> <div align="center">ค่างวดเก่า</div></th>
								<th width="250"> <div align="center">เปลี่ยนค่างวดเป็น</div></th>
								<th width="250"> <div align="center">วันที่ขอเปลี่ยน</div></th>
								<th width="250"> <div align="center">สถานะการอนุมัติ</div></th>
								<th width="250"> <div align="center">เลือก</div></th>
							   
						</tr>	
						<?php	while($approve = pg_fetch_array($app)){ 
								$numtest = $approve['numtest'];
						
								$status = $approve['status'];
						
								if($status == 0){
									$statusdetail = 'รอการอนุมัติ';
									?><tr bgcolor="#FFFF66"><?php
								}else{
									?><tr><?php
								}
						
						
						
						
						
						
								$app1 = pg_query("select * from \"Fp_package\"  where \"numtest\" = '$numtest'");
								$approve1 = pg_fetch_array($app1)
					?>
								<td><div align="center"><?php echo $approve1['brand'];?></div></td>
								<td><div align="center"><?php echo $approve['month'];?></div></td>
								<td><div align="center"><?php echo $approve['period_same'];?></div></td>
								<td><div align="center"><?php echo $approve['period'];?></div></td>
								<td><div align="center"><?php echo $approve['date'];?></div></td>					
								<td><div align="center"><?php echo $statusdetail;?></div></td>
								<td><div align="center"><input type="checkbox" name="checkdown[]" value="<?php echo $approve["appfpackID"]; ?>"></div></td>
								</tr>
					<?php			
								}
						
						 ?>
						
					</table>
					<table width="700" cellSpacing="0" border="0" cellPadding="0" >	
									<tr>			
										<td colspan="5"></td>
										<td><div align="right">
										<input type="submit" name="bt_ok" id="bt_ok" value="อนุมัติ" onclick="javascript:Esubmit(this.form);">
										<input type="button" name="bt_no" id="bt_no" value="ปฎิเสธ" onclick="javascript:Fsubmit(this.form);"></div></td>
			
									</tr>
					</table>
					<?php }else{ echo "<center> ยังไม่มีข้อมูลขออนุมัติ </center>"; } ?>
</form>								
<form name="frm2" method="POST">

				
				<table width="950" cellSpacing="0" border="0" cellPadding="0" >
					<tr>
						<td align="center">
					
				<?php 
						$app3 = pg_query("select * from \"approve_Fp_package\"  where \"down_payment\" is not null and \"status\" = 0 order by \"appfpackID\" DESC");
						$rows3 = pg_num_rows($app3);
					?>
					
					<legend><h4><B> แก้ไข Package รอการอนุมัติเงินดาวน์ </B></h4></legend>
					<?php 	if($rows3 != 0){ ?>
					<table width="700" cellSpacing="0" border="1" cellPadding="0" >
						<tr bgcolor="#66CCFF">	
						
					
								<th width="350"> <div align="center">รหัส Package</div></th>
								<th width="350"> <div align="center">ยี่ห้อ/รุ่น</div></th>
								<th width="150"> <div align="center">เงินดาวน์เก่า</div></th>
								<th width="150"> <div align="center">เงินดาวน์ใหม่</div></th>
								<th width="250"> <div align="center">วันที่ขอเปลี่ยน</div></th>
								<th width="250"> <div align="center">สถานะ</div></th>
								<th width="250"> <div align="center">เลือก</div></th>
								
						</tr>	
						<?php	while($approve3 = pg_fetch_array($app3)){ 
								$numtest = $approve3['numtest'];
						

						$status = $approve3['status'];
						
						if($status == 0){
							$statusdetail = 'รอการอนุมัติ';
							?><tr bgcolor="#FFFF66"><?php
						}else{
							?><tr><?php
						}


						$app4 = pg_query("select * from \"Fp_package\"  where \"numtest\" = '$numtest'");
							$approve4 = pg_fetch_array($app4)
					?>
								<td><div align="center"><?php echo $approve3['fpackID'];?></div></td>
								<td><div align="center"><?php echo $approve4['brand'];?></div></td>
								<td><div align="center"><?php echo $approve3['down_payment_same'];?></div></td>
								<td><div align="center"><?php echo $approve3['down_payment'];?></div></td>
							
								<td><div align="center"><?php echo $approve3['date'];?></div></td>						
								<td><div align="center"><?php echo $statusdetail;?></div></td>
								<td><div align="center"><input type="checkbox" name="checkdown[]" value="<?php echo $approve3["appfpackID"]; ?>"></div></td>
					
						</tr>	
					<?php	} ?>
					</table>
					<table width="700" cellSpacing="0" border="0" cellPadding="0" >
						<tr>	
								<td><div align="right"><input type="submit" name="bt_ok" id="bt_ok" value="อนุมัติ" onclick="javascript:Csubmit(this.form);">
								<input type="button" name="bt_no" id="bt_no" value="ปฎิเสธ" onclick="javascript:Dsubmit(this.form);"></div></td>
		
						</tr>
					</table>
			<?php }else{ echo "<center> ยังไม่มีข้อมูลขออนุมัติ </center>"; }?>
					</td>
					</tr>
				</table>
</form>				
<form name="frm3" method="POST">			
				
				<?php 
						$apppackage = pg_query("select * from \"approve_Fp_package_add\" WHERE \"status\" = 0  order by \"appfpackaddID\" DESC");
						$rows4 = pg_num_rows($apppackage);
				?>
				
				
				<table width="950" cellSpacing="0" border="0" cellPadding="0" >
					<tr>
						<td align="center">
							<legend><h4><B> เพิ่ม Package รอการอนุมัติ </B></h4></legend>
							<?php 	if($rows4 != 0){ ?>
							
							<table width="900" cellSpacing="0" border="1" cellPadding="0" >
							<tr bgcolor="#66CCFF">	
						
					
								<th width="350"> <div align="center">รหัสการอนุมัติ Package</div></th>
								<th width="350"> <div align="center">ยี่ห้อ/รุ่น</div></th>
								<th width="350"> <div align="center">ราคา (ไม่รวมอุปกรณ์)</div></th>
								<th width="350"> <div align="center">ราคา</div></th>
								<th width="350"> <div align="center">ดาวน์</div></th>
								<th width="350"> <div align="center">จำนวนงวด</div></th>
								<th width="350"> <div align="center">ค่างวด</div></th>
								<th width="250"> <div align="center">วันที่ขอเปลี่ยน</div></th>
								<th width="250"> <div align="center">สถานะการอนุมัติ</div></th>
								<th width="250"> <div align="center">เลือก</div></th>
								
						</tr>	
						<?php	while($apppack = pg_fetch_array($apppackage)){ 
								$fpackID = $apppack['fpackID'];
								$status = $apppack['status'];
						
						if($status == 0){
							$statusdetail = 'รอการอนุมัติ';
							?><tr bgcolor="#FFFF66"><?php
						}else{
							?><tr><?php
						}
						
												
						
						$apppackage1 = pg_query("select * from \"temp_Fp_package\"  where \"fpackID\" = '$fpackID'");
							$apppack1 = pg_fetch_array($apppackage1)
					?>
								<td><div align="center"><?php echo $apppack['appfpackaddID'];?></div></td>			
								<td><div align="center"><?php echo $apppack1['brand'];?></div></td>
								<td><div align="center"><?php echo $apppack1['price_not_accessory'];?></div></td>
								<td><div align="center"><?php echo $apppack1['price_accessory'];?></div></td>
								<td><div align="center"><?php echo $apppack1['down_payment'];?></div></td>
								<td><div align="center"><?php echo $apppack1['month_payment'];?></div></td>
								<td><div align="center"><?php echo $apppack1['period'];?></div></td>
								<td><div align="center"><?php echo $apppack['date'];?></div></td>						
								<td><div align="center"><?php echo $statusdetail;?></div></td>
								<td><div align="center"><input type="checkbox" name="checkname[]" id="checkname[]" value="<?php echo $apppack['appfpackaddID'];?>"></div></td>
								<input type="hidden" name="checkfpackID[]" value="<?php echo $fpackID ?>">
								</tr>
					<?php } ?>	
				</table>
				
				
				<table width="900" cellSpacing="0" border="0" cellPadding="0" >
						<tr>
								<td><div align="right"><input type="submit" name="bt_ok" id="bt_ok" value="อนุมัติ"  onclick="javascript:Asubmit(this.form);">
								<input type="submit" name="bt_no" id="bt_no" value="ปฎิเสธ" onclick="javascript:Bsubmit(this.form);"></div></td>	
						</tr>
				</table>
				<?php }else{ echo "<center> ยังไม่มีข้อมูลขออนุมัติ </center>"; }?>
			</form>	
			
			</td>
		</tr>						
	</table>



<script type="text/javascript">
function Asubmit(frm)
{
frm.action="approve_query_add.php";
frm.submit();
}

function Bsubmit(frm)
{
frm.action="approve_reason_add.php";
frm.submit();
}

function Csubmit(frm)
{
frm.action="approve_query.php?type=down";
frm.submit();
}

function Dsubmit(frm)
{
frm.action="approve_reason.php?type=down";
frm.submit();
}

function Esubmit(frm)
{
frm.action="approve_query.php?type=period";
frm.submit();
}

function Fsubmit(frm)
{
frm.action="approve_reason.php?type=period";
frm.submit();
}
</script>

	</body>
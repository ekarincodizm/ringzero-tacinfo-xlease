<?php 

include("../../config/config.php");

?>

					
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>-  -</title>
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

				<table width="950" cellSpacing="0" border="0" cellPadding="0" >
					<tr>
						<td align="center">
					
					<?php
						$app = pg_query("select * from \"approve_Fp_package\"  where \"period\" is not null order by \"appfpackID\" DESC");
						$rows = pg_num_rows($app);
					?>
					<div style="width: 900px; height: 300px; overflow: auto;">
					<legend><h4><B> แก้ไข ราคา ค่างวดของ Package </B></h4></legend>
					<table width="800" cellSpacing="0" border="1" cellPadding="0" >
						<tr bgcolor="#66CCFF">	
						<?php 	if($rows != 0){ ?>
					
								<th width="350"> <div align="center">ยี่ห้อ/รุ่น</div></th>
								<th width="150"> <div align="center">จำนวนงวด</div></th>
								<th width="150"> <div align="center">ค่างวดเก่า</div></th>
								<th width="250"> <div align="center">เปลี่ยนค่างวดเป็น</div></th>
								<th width="250"> <div align="center">วันที่ขอเปลี่ยน</div></th>
								<th width="250"> <div align="center">สถานะการอนุมัติ</div></th>
								<th> <div align="center"></div></th>
						</tr>	
						<?php	while($approve = pg_fetch_array($app)){ 
								$numtest = $approve['numtest'];
						
								$status = $approve['status'];
											
									if($status == 0){
										$statusdetail = 'รอการอนุมัติ';
										?><tr bgcolor="#FFFF66"><?php
									}else if($status == 1){
										$statusdetail = 'อนุมัติแล้ว';
										?><tr bgcolor="#66FF99"><?php
									}else if($status == 2){			
										$statusdetail = 'ปฎิเสธ';
										?><tr bgcolor="#FF9999"><?php
									}else{
										?><tr><?php
									}


							$app1 = pg_query("select * from \"Fp_package\"  where \"numtest\" = '$numtest' ");
							$approve1 = pg_fetch_array($app1)
						?>
								<td><div align="center"><?php echo $approve1['brand'];?></div></td>
								<td><div align="center"><?php echo $approve['month'];?></div></td>
								<td><div align="center"><?php echo $approve['period_same'];?></div></td>
								<td><div align="center"><?php echo $approve['period'];?></div></td>
								<td><div align="center"><?php echo $approve['date'];?></div></td>							
								<td><div align="center"><?php echo $statusdetail;?></div></td>
						<?php	if($approve['status'] ==2){ ?>
										
								<td><div align="center"><textarea rows="2" cols="20">เหตุผล : <?php echo $approve['reason'];?></textarea></div></td>		
						<?php		}else{ ?>

								<td><div align="center">----------------------</div></td>
						<?php } 
						
								}
							}
						 ?>		
						</tr>
					</table>
					</div>
				  </td>
				</tr>						
			</table>
					
					
			<table width="950" cellSpacing="0" border="0" cellPadding="0" >
				<tr>
					<td align="center">
					<?php 
						$app3 = pg_query("select * from \"approve_Fp_package\"  where \"down_payment\" is not null order by \"appfpackID\" DESC");
						$rows3 = pg_num_rows($app3);
					?>
					
					<legend><h4><B> แก้ไข เงินดาวน์ ของ Package</B></h4></legend>
					<div style="width: 900px; height: 300px; overflow: auto;">
						<table width="800" cellSpacing="0" border="1" cellPadding="0" >
							<tr bgcolor="#66CCFF">	
							<?php 	if($rows3 != 0){ ?>
						
									<th width="350"> <div align="center">รหัส Package</div></th>
									<th width="350"> <div align="center">ยี่ห้อ/รุ่น</div></th>
									<th width="150"> <div align="center">เงินดาวน์</div></th>
									<th width="150"> <div align="center">เงินดาวน์ที่เปลี่ยนใหม่</div></th>
									<th width="250"> <div align="center">วันที่ขอเปลี่ยน</div></th>
									<th width="250"> <div align="center">สถานะการอนุมัติ</div></th>
									<th> <div align="center"></div></th>
							</tr>	
								<?php	while($approve3 = pg_fetch_array($app3)){ 
										$numtest = $approve3['numtest'];
								
										$status = $approve3['status'];
											
											if($status == 0){
												$statusdetail = 'รอการอนุมัติ';
												?><tr bgcolor="#FFFF66"><?php
											}else if($status == 1){
												$statusdetail = 'อนุมัติแล้ว';
												?><tr bgcolor="#66FF99"><?php
											}else if($status == 2){			
											$statusdetail = 'ปฎิเสธ';
												?><tr bgcolor="#FF9999"><?php
											}else{
												?><tr><?php
											}

								$app4 = pg_query("select * from \"Fp_package\"  where \"numtest\" = '$numtest'");
									$approve4 = pg_fetch_array($app4)
									
																
							?>
								<td><div align="center"><?php echo $approve3['fpackID'];?></div></td>
								<td><div align="center"><?php echo $approve4['brand'];?></div></td>
								<td><div align="center"><?php echo $e = number_format($approve3['down_payment']);?></div></td>
								<td><div align="center"><?php echo $d = number_format($approve3['down_payment_same']);?></div></td>
								<td><div align="center"><?php echo $approve3['date'];?></div></td>								
								<td><div align="center"><?php echo $statusdetail;?></div></td>
								
								
								<?php	if($approve3['status'] ==2){ ?>
										
								<td><div align="center"><textarea rows="2" cols="20">เหตุผล : <?php echo $approve3['reason'];?></textarea></div></td>		
						<?php		}else{ ?>

								<td><div align="center">----------------------</div></td>
						<?php } 
							}
						}?>		
						</tr>
					</table>
					</div>
				  </td>
				</tr>						
			</table>
							
					<?php 
						$apppackage = pg_query("select * from \"approve_Fp_package_add\" order by \"appfpackaddID\" DESC");
						$rows4 = pg_num_rows($apppackage);
				?>
				
				
				<table width="950" cellSpacing="0" border="0" cellPadding="0" >
					<tr>
						<td align="center">
							<legend><h4><B> เพิ่ม Package ใหม่ </B></h4></legend>
							<div style="width: 1000px; height: 300px; overflow: auto;">
								<table width="900" cellSpacing="0" border="1" cellPadding="0" >
							<tr bgcolor="#66CCFF">	
						<?php 	if($rows4 != 0){ ?>
					
								<th width="350"> <div align="center">รหัสการอนุมัติ Package</div></th>
								<th width="350"> <div align="center">ยี่ห้อ/รุ่น</div></th>
								<th width="350"> <div align="center">ราคา (ไม่รวมอุปกรณ์)</div></th>
								<th width="350"> <div align="center">ราคา</div></th>
								<th width="350"> <div align="center">ดาวน์</div></th>
								<th width="350"> <div align="center">จำนวนงวด</div></th>
								<th width="350"> <div align="center">ค่างวด</div></th>
								<th width="250"> <div align="center">วันที่ขอเปลี่ยน</div></th>
								<th width="250"> <div align="center">สถานะการอนุมัติ</div></th>
								<th width="250" colspan="2"> <div align="center"></div></th>
								
						</tr>	
						<?php	while($apppack = pg_fetch_array($apppackage)){ 
								$fpackID = $apppack['fpackID'];
								$status = $apppack['status'];
						
						if($status == 0){
							$statusdetail = 'รอการอนุมัติ';
							?><tr bgcolor="#FFFF66"><?php
						}else if($status == 1){
							$statusdetail = 'อนุมัติแล้ว';
							?><tr bgcolor="#66FF99"><?php
						}else if($status == 2){			
						$statusdetail = 'ปฎิเสธ';
							?><tr bgcolor="#FF9999"><?php
						}else{
							?><tr><?php
						}
						
												
						
						$apppackage1 = pg_query("select * from \"temp_Fp_package\"  where \"fpackID\" = '$fpackID'");
							$apppack1 = pg_fetch_array($apppackage1)
					?>
								<td><div align="center"><?php echo $apppack['appfpackaddID'];?></div></td>			
								<td><div align="center"><?php echo $apppack1['brand'];?></div></td>
								<td><div align="center"><?php echo $a = number_format($apppack1['price_not_accessory']);?></div></td>
								<td><div align="center"><?php echo $b = number_format($apppack1['price_accessory']);?></div></td>
								<td><div align="center"><?php echo $c = number_format($apppack1['down_payment']);?></div></td>
								<td><div align="center"><?php echo $apppack1['month_payment'];?></div></td>
								<td><div align="center"><?php echo $d = number_format($apppack1['period']);?></div></td>
								<td><div align="center"><?php echo $apppack['date'];?></div></td>						
								<td><div align="center"><?php echo $statusdetail;?></div></td>
								
				<?php  if($status == 2){ ?>
								<td><div align="center"><textarea rows="2" cols="30" readonly="true">เหตุผล : <?php echo $apppack['reason'];?></textarea></div></td>
						<?php }else{ 	?>								
								<td colspan="2" align="center">--------------</td>						
						<?php	
								}										
							}
						}?>								
					
				</table>
			</div>
		  </td>
		</tr>						
	</table>	
					
					
					
					
					
					
					
					
					
					
			</td>
		</tr>						
	</table>
</body>				
<?php
session_start();
include("../../config/config.php");
																	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>- อนุมัติ ใบค่าธรรมเนียม -</title>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
};

function Yes(){	
	document.frm.check.value = 'Yes';	
}

function No(){	
	document.frm.check.value = 'No';	
}
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
<form name="frm" action="approve_yes_query.php" method="POST">
<input type="hidden" name="check" id="check">
	<table width="900" border="0" cellspacing="0" cellpadding="0"  align="center">
		<tr>
			<td align="center">
				<table width="950" cellSpacing="0" border="0" cellPadding="0" >
					<tr>
						<td align="center">

<?php 
				
						

						$app = pg_query("select * from \"approve_thcap_mg_3dreceipt\"  where \"status\" = 0 order by \"appreceiptID\" DESC");
						$rows = pg_num_rows($app);

						
						
					?>
		
					<legend><h4><B>  ใบค่าธรรมเนียมเงินกู้  </B></h4></legend>
					
					<table width="1000" cellSpacing="0" border="1" cellPadding="0" >
						<tr bgcolor="#66CCFF">	
						<?php 	if($rows != 0){ ?>
					
								<th width="5%"> <div align="center">รหัส</div></th>
								<th width="10%"> <div align="center">วันที่</div></th>
								<th width="10%"> <div align="center">ใบค่าธรรมเนียม</div></th>
								<th width="23%"> <div align="center">ชื่อลูกค้า</div></th>						
								<th width="7%"> <div align="center">รายการ<br>ทั้งหมด</div></th>
								<th width="10%"> <div align="center">รวมเป็นเงิน</div></th>
								<th width="23	%"> <div align="center">ผู้รับเงิน</div></th>
								<th width="10%"> <div align="center">สถานะ</div></th>
								<th width="5%"> <div align="center">เลือก</div></th>
								
						</tr>	
						
						<?php	while($approve = pg_fetch_array($app)){ 
								
						
						$threceiptID = $approve['threceiptID'];
						$checklistsql = pg_query("select * from \"temp_thcap_mg_3dreceipt\"  where \"threceiptID\" = '$threceiptID'");
						$checklistre = pg_fetch_array($checklistsql);
						
						
						$count = 0;
						$summoney = 0;
						if($checklistre['list1'] != ""){
							$count++;
						}
						if($checklistre['list2'] != ""){
							$count++;
						}
						if($checklistre['list3'] != ""){
							$count++;
						}
						if($checklistre['list4'] != ""){
							$count++;
						}
						if($checklistre['list5'] != ""){
							$count++;
						}
						if($checklistre['list6'] != ""){
							$count++;
						}
						if($checklistre['list7'] != ""){
							$count++;
						}
						if($checklistre['list8'] != ""){
							$count++;
						}
						if($checklistre['list9'] != ""){
							$count++;
						}
						if($checklistre['list10'] != ""){
							$count++;
						}
						
						if($checklistre['money1'] != ""){
							$summoney = $summoney + $checklistre['money1'];
						}
						if($checklistre['money2'] != ""){
							$summoney = $summoney + $checklistre['money2'];
						}
						if($checklistre['money3'] != ""){
							$summoney = $summoney + $checklistre['money3'];
						}
						if($checklistre['money4'] != ""){
							$summoney = $summoney + $checklistre['money4'];
						}
						if($checklistre['money5'] != ""){
							$summoney = $summoney + $checklistre['money5'];
						}
						if($checklistre['money6'] != ""){
							$summoney = $summoney + $checklistre['money6'];
						}
						if($checklistre['money7'] != ""){
							$summoney = $summoney + $checklistre['money7'];
						}
						if($checklistre['money8'] != ""){
							$summoney = $summoney + $checklistre['money8'];
						}
						if($checklistre['money9'] != ""){
							$summoney = $summoney + $checklistre['money9'];
						}
						if($checklistre['money10'] != ""){
							$summoney = $summoney + $checklistre['money10'];
						}
						
					$id_user = $checklistre['id_user'];	
						
					$qry_name=pg_query("select * from \"Vfuser\" WHERE \"id_user\" = '$id_user'");
					$result=pg_fetch_array($qry_name); 
					$thaiacename = $result["fullname"];
											
						
						
						
								$status = $approve['status'];
						
								if($status == 0){
									$statusdetail = 'รอการอนุมัติ';
									?><tr bgcolor="#FFFF66"><?php
								}	
					?>
								<td><div align="center"><?php echo $approve['appreceiptID'];?></div></td>
								<td><div align="center"><?php echo $approve['date'];?></div></td>
								<td><div align="center"><?php echo $approve['threceiptID'];?></div></td>
								<td><div align="center"><?php echo $checklistre['cusname'];?></div></td>
								<td onclick="javascript:popU('listdetail.php?id=<?php echo $approve["appreceiptID"]; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" >							
								<div align="center"><u><?php echo $count;?></u></div></td>	
								<td><div align="center"><?php echo $summoney = number_format($summoney,2);?></div></td>	
								<td><div align="center"><?php echo $thaiacename;?></div></td>								
								<td><div align="center"><?php echo $statusdetail;?></div></td>
								<td><div align="center"><input type="checkbox" name="yes[]" value="<?php echo $approve['appreceiptID'];?>"></div></td>
						
						<?php	
								}
							
						?>
								
						</tr>						
					</table>
			</td>
		</tr>
		<tr>
			<td><br></td>
		</tr>
		<tr>
			<td align="right"> <input type="submit" value=" อนุมัติ " onclick="Yes()" style="width:150px;height:30px"> <input type="submit" value=" ปฎิเสธ " onclick="No()" style="width:150px;height:30px"></td>
		</tr>	
	</table>
	<?php }else{ ?>
	
			<center><h1> ยังไม่มีรายการรออนุมัติ </h1></center>
			
	<?php							
	}
	?>			
	</form>
	</body>
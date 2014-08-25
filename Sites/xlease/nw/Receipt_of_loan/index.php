<?php
session_start();
$_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
include("../../config/config.php");

						  
						  
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>-- ใบรับเงิน --</title>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">

function popU(U,N,T) {
    newWindow = window.open(U, N, T);
};

$(document).ready(function(){

    $("#idsearch").autocomplete({
        source: "Contractlist.php",
        minLength:1
    });
	
	 $("#search_pdf").autocomplete({
        source: "receiptlist.php",
        minLength:1
    });

});


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

<body>
<form name="frm" action="frm_receipt.php" method="POST">
<table width="600" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td align="center">
			<h1> ค่าธรรมเนียมเงินกู้  </h1>
		</td>
	</tr>
	<tr>
		<td>
		<fieldset><legend><b>เพิ่มใบรับเงินค่าธรรมเนียมเงินกู้</b></legend>
			<table width="400" border="0" cellspacing="0" cellpadding="0" align="center">
				<tr>
					<td>
						<input type="text" name="idsearch" id="idsearch" size="60">
					</td>
					<td>
						<input type="submit" value=" เพิ่มใบรับเงิน " style="width:100px">
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<font color="gray" size="2pt">*เลขที่สัญญา , ชื่อลูกค้า / บริษัท </font>
					</td>
				</tr>	
			</table>
		</fieldset>		
		</td>
	</tr>	
</table>
</form>
<form name="frm2" action="pdf.php" method="POST">
<table width="600" border="0" cellspacing="0" cellpadding="0" align="center">
	
	<tr>
		<td align="center">
			<br><br>
		</td>
	</tr>
	<tr>
		<td align="center"><hr width="350"></td>
	</tr>
	<tr>
		<td align="center">
			<br><br>
		</td>
	</tr>
	<tr>
		<td>
		<fieldset><legend><b>ค้นหาใบรับเงินค่าธรรมเนียมเงินกู้</b></legend>
			<table width="400" border="0" cellspacing="0" cellpadding="0" align="center">
				<tr>
					<td>
						<input type="text" name="search_pdf" id="search_pdf" size="60">
					</td>
					<td>
						<input type="submit" value=" ค้นหา " style="width:100px">
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<font color="gray" size="2pt">*เลขที่ใบรับเงิน </font>
					</td>
				</tr>	
			</table>
		</fieldset>		
		</td>
	</tr>	
</table>
</form>
<?php 
				
						

						$app = pg_query("select * from \"approve_thcap_mg_3dreceipt\"  where \"status\" = 0 order by \"appreceiptID\" DESC");
						$rows = pg_num_rows($app);

						
						
					?>
		<?php 	if($rows != 0){ ?>
					<center><legend><h4><B>  ใบค่าธรรมเนียมเงินกู้ที่รออนุมัติ </B></h4></legend></center>
					
					<table width="1000" cellSpacing="0" border="1" cellPadding="0" align="center">
						<tr bgcolor="#66CCFF">	
						
					
								<th width="5%"> <div align="center">รหัส</div></th>
								<th width="10%"> <div align="center">วันที่</div></th>
								<th width="10%"> <div align="center">ใบค่าธรรมเนียม</div></th>
								<th width="23%"> <div align="center">ชื่อลูกค้า</div></th>						
								<th width="7%"> <div align="center">รายการ<br>ทั้งหมด</div></th>
								<th width="10%"> <div align="center">รวมเป็นเงิน</div></th>
								<th width="23	%"> <div align="center">ผู้รับเงิน</div></th>
								<th width="10%"> <div align="center">สถานะ</div></th>
								
								
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
								<td><div align="center"><?php echo $count;?></div></td>	
								<td><div align="center"><?php echo $summoney = number_format($summoney,2);?></div></td>	
								<td><div align="center"><?php echo $thaiacename;?></div></td>								
								<td><div align="center"><?php echo $statusdetail;?></div></td>
								
						
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
		
	</table>
	<?php }else{ ?>
	
			<center><h1> ยังไม่มีรายการรออนุมัติ </h1></center>
			
	<?php							
	}
	?>			

</body>
</html>

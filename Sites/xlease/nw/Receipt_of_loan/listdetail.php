<?php
session_start();
include("../../config/config.php");
$id = $_GET['id'];																	
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
<form name="frm" action="approve_yes_query.php" method="POST">
<input type="hidden" name="check" id="check">
	<table width="900" border="0" cellspacing="0" cellpadding="0"  align="center">
		<tr>
			<td align="center">
				

<?php 			
						$app = pg_query("select * from \"approve_thcap_mg_3dreceipt\"  where \"appreceiptID\" = '$id'");
						$rows = pg_num_rows($app);
						$approve = pg_fetch_array($app); 
						$threceiptID = $approve['threceiptID'];						
					?>
		
					<legend><h3><B>  รายละเอียด รายการ ของใบค่าธรรมเนียม </B></h3></legend>
					<legend><h4><B>--- <?php echo $id; ?> ---</B></h4></legend>
					<table width="500" cellSpacing="0" border="1" cellPadding="2" >
						<tr bgcolor="#66CCFF">
								<th>รายการที่ </th>
								<th> ชื่อรายการ </th>
								<th> จำนวนเงิน </th>
						
						</tr>
					
	<?php					
				
								
						
						
						$checklistsql = pg_query("select * from \"temp_thcap_mg_3dreceipt\"  where \"threceiptID\" = '$threceiptID'");
						$checklistre = pg_fetch_array($checklistsql);
						
						
						$count = 0;						
						if($checklistre['list1'] != ""){					
							$count++;
							$list[] = $checklistre['listdetail1'];
							$money[] = $checklistre['money1'];
						}
						if($checklistre['list2'] != ""){
							$count++;
							$list[] = $checklistre['listdetail2'];
							$money[] = $checklistre['money2'];
						}
						if($checklistre['list3'] != ""){
							$count++;
							$list[] = $checklistre['listdetail3'];
							$money[] = $checklistre['money3'];
						}
						if($checklistre['list4'] != ""){
							$count++;
							$list[] = $checklistre['listdetail4'];
							$money[] = $checklistre['money4'];
						}
						if($checklistre['list5'] != ""){
							$count++;
							$list[] = $checklistre['listdetail5'];
							$money[] = $checklistre['money5'];
						}
						if($checklistre['list6'] != ""){
							$count++;
							$list[] = $checklistre['listdetail6'];
							$money[] = $checklistre['money6'];
						}
						if($checklistre['list7'] != ""){
							$count++;
							$list[] = $checklistre['listdetail7'];
							$money[] = $checklistre['money7'];
						}
						if($checklistre['list8'] != ""){
							$count++;
							$list[] = $checklistre['listdetail8'];
							$money[] = $checklistre['money8'];
						}
						if($checklistre['list9'] != ""){
							$count++;
							$list[] = $checklistre['listdetail9'];
							$money[] = $checklistre['money9'];
						}
						if($checklistre['list10'] != ""){
							$count++;
							$list[] = $checklistre['listdetail10'];
							$money[] = $checklistre['money10'];
						}
					
						for($i=0;$i<$count;$i++){
					?>
						<tr>	
								<td><div align="center"><?php echo $i+1;?></div></td>
								<td><div align="center"><?php echo $list[$i];?></div></td>
								<td><div align="center"><?php echo $money[$i];?></div></td>
						</tr>		
						
					<?php	
						
						}
							
					?>
								
											
					</table>
			</td>
		</tr>	
	</table>
		
	</form>
	</body>
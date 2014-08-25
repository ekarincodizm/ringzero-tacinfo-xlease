<?php
include("../../config/config.php");

$strSort = $_GET["sort"];
$ta = $_GET["ta"];
if($strSort == "")
{
	$strSort = "appsecurID";
}
if($ta == "")
{
	$ta = "asd";
}
$strOrder = $_GET["order"];
if($strOrder == "")
{
	$strOrder = "ASC";
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>- ขออนุมัติ เพิ่มรายการประเมินหลักทรัพย์ -</title>
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

	<table width="900" border="0" cellspacing="0" cellpadding="0"  align="center">
		<tr>
			<td>
			<legend><h2><B>ตารางรายการเพิ่มการประเมินหลักทรัพย์</B></h2></legend>
				<div align="center">
				<div class="style5" style="width:auto; height:40px; padding-left:10px;">

		<?php
				$strSQL = "
				select * from \"approve_securities_detail\" asd
				left join \"nw_securities\" ns on ns.\"securID\" = asd.\"securID\"
				left join \"temp_securities_detail\" tsd on tsd.\"securdeID\" = asd.\"securdeID\"
				left join \"Fa1\" fa on fa.\"CusID\" = tsd.\"CusID\"
				order by $ta.\"$strSort\" $strOrder ";
				$objQuery = pg_query($strSQL);
				$nrows=pg_num_rows($objQuery);
				$strNewOrder = $strOrder == 'DESC' ? 'ASC' : 'DESC';
				
					
				if($nrows != 0){
		?>
				<div style="width: 1000px; height: 600px; overflow: auto;">
						<table width="900" frame="BORDER" cellSpacing="1" cellPadding="2">
							
							<tr bgcolor="#79BCFF" height="25" >
								
								<th width="190"> <div align="center"><a href='approve.php?sort=appsecurID&order=<?php echo $strNewOrder ?>'><u>รหัสการประเมินหลักทรัพย์</u> </div></th>
								<th width="190"> <div align="center"><a href='approve.php?sort=numDeed&ta=ns&order=<?php echo $strNewOrder ?>'><u>เลขที่โฉนด</u></div></th>
								<th width="190"> <div align="center"><a href='approve.php?sort=A_NAME&ta=fa&order=<?php echo $strNewOrder ?>'><u>ชื่อลูกค้า</div></th>
								<th width="190"> <div align="center"><a href='approve.php?sort=status&order=<?php echo $strNewOrder ?>'><u>สถานะการอนุมัติ </u></div></th>
								<th width="190"> <div align="center"><a href='approve.php?sort=date&order=<?php echo $strNewOrder ?>'><u>วันที่ขออนุมัติ</u></div></th>
								<th width="150"> <div align="center"><a href='approve.php?sort=id_user&order=<?php echo $strNewOrder ?>'><u>ผู้ขออนุมัติ</u></div></th>
								<th width="59"> <div align="center">ยีนยัน</div></th>
								<th width="59"> <div align="center">ปฎิเสธ</div></th>
							</tr>
<?php
					while($results = pg_fetch_array($objQuery)){
							
							$id_user = $results["id_user"];
							$status = $results["status"];
						if($status == 0){
							$statusdetail = 'รอการอนุมัติ';
							?><tr bgcolor="#FFFF66"><?php
						}else if($status == 1){
							$statusdetail = 'อนุมัติแล้ว';
							?><tr bgcolor="#66FF99"><?php
						}else if($status == 2){			
						$statusdetail = 'ปฎิเสธ';
							?><tr bgcolor="#FF9999"><?php
						}else if($status == 3){			
						$statusdetail = 'ปฎิเสธและดำเนินการแก้ไขแล้ว';
							?><tr bgcolor="#FF9999"><?php
						}else if($status == 4){			
						$statusdetail = 'ข้อมูลที่ถูกแก้ไข รอการอนุมัติ';
							?><tr bgcolor="#FFFF66"><?php
						}else if($status == 5){			
						$statusdetail = 'ปฎิเสธ  ข้อมูลที่ถูกแก้ไข';
							?><tr bgcolor="#FF9999"><?php
						}else{
							?><tr><?php
						}
								
?>	
								
								<td  onclick="javascript:popU('approve_estimate_data.php?securdeID=<?php echo $results["securdeID"]; ?>&check=admin','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">	
								<div align="center"><u><?php echo $results["securdeID"];?></u></div></td>
								<td><div align="center"><?php echo $results['numDeed']; ;?></div></td>
								<td><div align="center"><?php echo $results['A_NAME']." ".$results['A_SIRNAME'] ;?></div></td>
								<td><div align="center"><?php echo $statusdetail ;?></div></td>
								<td><div align="center"><?php echo $results["date"];?></div></td>
		
	
<?php
					$strSQL3 = "SELECT * FROM \"fuser\" where \"id_user\" = '$id_user'";
					$objQuery3 = pg_query($strSQL3);
					$results3 = pg_fetch_array($objQuery3);
	
?>
								<td><div align="center"><?php echo $results3["fname"]." ".$results3["fname"];?></div></td>
<?php 					if($status == 0){ ?>
						
									<!--td><div align="center">
									<input type="button" name="bt_ok" id="bt_ok" value="อนุมัติ" onclick="parent.location.href='approve_query.php?Check=agree&appsecurID=<?php echo $results["appsecurID"]; ?>&securdeID=<?php echo $results["securdeID"]; ?>'"></div></td>
									<td><div align="center">
									<input type="button" name="bt_pass" id="bt_pass" value="ปฎิเสธ" onclick="parent.location.href='approve_reason.php?Check=del&appsecurID=<?php echo $results["appsecurID"]; ?>&securdeID=<?php echo $results["securdeID"]; ?>'"></div></td-->
									<form method="post" action="approve_query.php">
										<input type="hidden" name="appsecurID" id="appsecurID" value="<?php echo $results["appsecurID"]; ?>">
										<input type="hidden" name="securdeID" id="securdeID" value="<?php echo $results["securdeID"]; ?>">
										<td><div align="center">
										<input name="appv" type="submit" value="อนุมัติ" />
										</div></td>
									</form>
									<form method="post" action="approve_reason.php">
										<input type="hidden" name="appsecurID" id="appsecurID" value="<?php echo $results["appsecurID"]; ?>">
										<input type="hidden" name="securdeID" id="securdeID" value="<?php echo $results["securdeID"]; ?>">
										<td><div align="center">
										<input name="unappv" type="submit" value="ปฎิเสธ" />
										</div></td>
									</form>
<?php					}else if($status == 4){ ?>
						
									<!--td><div align="center">
									<input type="button" name="bt_ok" id="bt_ok" value="อนุมัติ" onclick="parent.location.href='approve_edit_query.php?Check=agree&appsecurID=<?php echo $results["appsecurID"]; ?>&securdeID=<?php echo $results["securdeID"]; ?>'"></div></td>
									<td><div align="center">
									<input type="button" name="bt_pass" id="bt_pass" value="ปฎิเสธ" onclick="parent.location.href='approve_reason.php?Check=delup&appsecurID=<?php echo $results["appsecurID"]; ?>&securdeID=<?php echo $results["securdeID"]; ?>'"></div></td-->

									<form method="post" action="approve_edit_query.php">
										<input type="hidden" name="appsecurID" id="appsecurID" value="<?php echo $results["appsecurID"]; ?>">
										<input type="hidden" name="securdeID" id="securdeID" value="<?php echo $results["securdeID"]; ?>">
										<td><div align="center">
										<input name="appv" type="submit" value="อนุมัติ" />
										</div></td>
									</form>
									<form method="post" action="approve_reason.php">
										<input type="hidden" name="appsecurID" id="appsecurID" value="<?php echo $results["appsecurID"]; ?>">
										<input type="hidden" name="securdeID" id="securdeID" value="<?php echo $results["securdeID"]; ?>">
										<input type="hidden" name="delup" id="delup" value="delup">
										<td><div align="center">
										<input name="unappv" type="submit" value="ปฎิเสธ" />
										</div></td>
									</form>
<?php					}else{		?>
								<td colspan="2" align="center"> ---------------- </td>
								</tr>
<?php
						}
					}
?>
							<tr>
									<td bgcolor="#79BCFF" colspan="11"><div align="center"></div> มีทั้งหมด <?php echo $nrows; ?> รายการ</div></td>
							</tr>
						
						</table>

<?php 
}else{ 
	echo "<hr width=850>";
	echo "<center><h1>ไม่พบข้อมูล</h1></center>";
}
?>
				</div>
				</div>
			
			</td>
		</tr>

	</table>

<div id="panel1" style="padding-top: 10px;"></div>
</html>

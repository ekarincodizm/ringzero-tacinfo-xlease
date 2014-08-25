<?php
session_start();
$_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
if($_SESSION["session_company_code"]=="AVL")
{
 $file_namepic="logo_av.jpg";
}
else
{
 $file_namepic="logo_thaiace.jpg";
}
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>ข้อมูลสินทรัพย์</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){

    $("#sname").autocomplete({
        source: "s_mix.php",
        minLength:2
    });

    

});

function popU(U,N,T) {
	var U = "approve_estimate_data.php?check=user&sname="+$("#sname").val();
	var T = "toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560";
    newWindow = window.open(U, N, T);
}

function popeakte(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
    
<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}

.odd{
    background-color:#EDF8FE;
    font-size:12px
}
.even{
    background-color:#D5EFFD;
    font-size:12px
}
</style>
    
</head>
<body>

<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
<form name="from" method="get">		

	<!--Hidden-->
	<input type="hidden" name="check" value="user" action="">
			<div style="clear:both; padding: 10px;text-align:center;"><h2>เพิ่มการประเมินข้อมูลสินทรัพย์</h2></div>
			<div style="text-align:right;"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
			<fieldset><legend><B>ค้นหา</B></legend>
				<div class="ui-widget" align="center">
					<div style="margin:0;padding-bottom:10px;">
						<b>ค้นจากเลขที่โฉนด, เลขที่ดิน, ชื่ออาคารชุด, ทะเบียนอาคารชุด, ตำบล/อำเภอ</b><br>
						<input id="sname" name="sname" size="60" />&nbsp;
						<input type="button" id="btn1" value="ค้นหา" onclick="javascript:popU();" />
					</div>
				</div>
			</fieldset>
</form>		
        </td>
    </tr>
	<tr>
		<td>
			

		<?php
				$strSQL = "
				select * from \"approve_securities_detail\" asd
				left join \"nw_securities\" ns on ns.\"securID\" = asd.\"securID\"
				left join \"temp_securities_detail\" tsd on tsd.\"securdeID\" = asd.\"securdeID\"
				left join \"Fa1\" fa on fa.\"CusID\" = tsd.\"CusID\"
				
				where asd.\"status\" !=3
				order by $ta.\"$strSort\" $strOrder 

				";
				$objQuery = pg_query($strSQL);
				$nrows=pg_num_rows($objQuery);
				$strNewOrder = $strOrder == 'DESC' ? 'ASC' : 'DESC';
				if($nrows != 0){
		?>
					<div style="width: 900px; height: 600px; overflow: auto;">
						<table width="900" frame="BORDER" cellSpacing="1" cellPadding="2">
							<tr bgcolor="#66CCFF" height="25">
								<td colspan="9"><h3><center> ประวัติการขออนุมัติ </center></h3></td>
							</tr>
							<tr bgcolor="#66DDFF" height="25" >
								<th width="100"> <div align="center"><a href='index.php?sort=appsecurID&order=<?php echo $strNewOrder ?>'><u><font color="black">ขออนุมัติครั้งที่ </u></font></div></th>
								<th width="100"> <div align="center"><a href='index.php?sort=securID&order=<?php echo $strNewOrder ?>'><u>รหัสหลักทรัพย์ </u></div></th>
								<th width="150"> <div align="center"><a href='index.php?sort=numDeed&ta=ns&order=<?php echo $strNewOrder ?>'><u>เลขที่โฉนด </u></div></th>
								<th width="190"> <div align="center"><a href='approve.php?sort=A_NAME&ta=fa&order=<?php echo $strNewOrder ?>'><u>ชื่อลูกค้า</div></th>
								<th width="200"> <div align="center"><a href='index.php?sort=status&order=<?php echo $strNewOrder ?>'><u>สถานะการอนุมัติ </u></div></th>
								<th width="150"> <div align="center"><a href='index.php?sort=date&order=<?php echo $strNewOrder ?>'><u>วันที่ขออนุมัติ</u></div></th>
								<th width="250"> <div align="center"><a href='index.php?sort=id_user&order=<?php echo $strNewOrder ?>'><u><font color="black">ผู้ขออนุมัติ</u></font></div></th>
								<th width="59" colspan="3"> <div align="center"></div></th>
								
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
								<td><div align="center"><?php echo $results["appsecurID"] ;?></div></td>
								<td  onclick="javascript:popeakte('approve_estimate_data.php?securdeID=<?php echo $results["securdeID"]; ?>&check=admin','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">	
								<div align="center"><u><?php echo $results["securID"];?></u></div></td>									
								<td><div align="center"><?php echo $results["numDeed"] ;?></div></td>
								<td><div align="center"><?php echo $results['A_NAME']." ".$results['A_SIRNAME'] ;?></div></td>
								<td><div align="center"><?php echo $statusdetail ;?></div></td>
								<td><div align="center"><?php echo $results["date"];?></div></td>
		
	
<?php
					$strSQL3 = "SELECT * FROM \"fuser\" where \"id_user\" = '$id_user'";
					$objQuery3 = pg_query($strSQL3);
					$results3 = pg_fetch_array($objQuery3);
	
?>
								<td><div align="center"><?php echo $results3["fname"]." ".$results3["fname"];?></div></td>
<?php 					if($status == 2){ ?>
						
									<td><textarea rows="2" cols="20" readonly><?php echo $results["reason"];?></textarea>
									<td><div align="center"><input type="button" name="bt_edit" id="bt_edit" value="แก้ไข" style="height:50px;" onclick="javascript:popeakte('approve_estimate_edit.php?securID=<?php echo $results["securID"]?>&appsecurID=<?php echo $results["appsecurID"]?>&securdeID=<?php echo $results["securdeID"];?>','','toolbar=yes,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no')"></div></td>
		
<?php					}else if($status == 5){ ?>
						
									<td><textarea rows="2" cols="25" readonly><?php echo $results["reason"];?></textarea>
									<td><div align="center"><input type="button" name="bt_edit" id="bt_edit" value="แก้ไข" style="height:50px;" onclick="javascript:popeakte('approve_estimate_edit.php?securID=<?php echo $results["securID"]?>&appsecurID=<?php echo $results["appsecurID"]?>&editup=editup&securdeID=<?php echo $results["securdeID"];?>','','toolbar=yes,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no')"></div></td>
		
<?php					}else{		?>
								<td colspan="2" align="center"> ---------------- </td>
								</tr>
<?php
						}
					}
?>
							<tr>
									<td bgcolor="#66DDFF" colspan="11"><div align="center"></div> มีทั้งหมด <?php echo $nrows; ?> รายการ</div></td>
							</tr>
						
<?php 
}else{ 
	echo "<hr width=850>";
	echo "<center><h1>ไม่พบข้อมูล</h1></center>";
}
?>
			
			
			</td>
		</tr>

	</table>
	</div>
	</td>
	</tr>
	<tr>
		<td>
			<div id="panel" style="padding-top: 10px;"></div>
		</td>
	</tr>		
</table>





</body>
</html>
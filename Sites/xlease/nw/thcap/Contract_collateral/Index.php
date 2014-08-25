<?php
session_start();
$id_user = $_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../../index.php");
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
include("../../../config/config.php");


$strSort = $_GET["sort"];
if($strSort == "")
{
	$strSort = "reg_stamp";
}

$strOrder = $_GET["order"];
if($strOrder == "")
{
	$strOrder = "ASC";
}
$sql = pg_query("SELECT b.\"numDeed\",b.\"numLand\",b.\"numBook\",b.\"district\",c.\"proName\",a.\"contractID\",a.\"tc_clType\",a.\"tc_clTypeRef\",a.\"tc_clValue\",
d.\"appvreg_status\",d.\"appvunreg_status\",d.\"reg_stamp\",d.\"tc_clSerial\"
  FROM thcap_contract_collateral a 
  left join \"nw_securities\" b on a.\"securID\" = b.\"securID\"
  left join \"nw_province\" c on b.\"proID\" = c.\"proID\"
  left join \"thcap_contract_collateral_regdetails\" d on a.\"tc_clSerial\" = d.\"tc_clSerial\"
  where d.\"appvreg_status\" = '0' OR d.\"appvunreg_status\" = '0' order by \"$strSort\" $strOrder 
  ");
$strNewOrder = $strOrder == 'DESC' ? 'ASC' : 'DESC';



$sqluser = pg_query("SELECT fullname FROM \"Vfuser\" where id_user='$id_user'");
$reuser = pg_fetch_array($sqluser);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>(THCAP) ตรวจรับโฉนด-จำนอง-ไถ่ถอน</title>
<link type="text/css" rel="stylesheet" href="../act.css"></link>
<link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
<style type="text/css">
    #warppage
	{
	width:800px;
	margin-left:auto;
	margin-right:auto;
	
	min-height: 5em;
	background: rgb(255, 255, 255);
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

<body bgcolor='#EEEEEE'>
<div id="swarp" style="margin-left:auto; margin-right:auto;margin-top:50px;"></div>


<table width="1150" border="0" cellspacing="0" cellpadding="0"  align="center">
	<tr>
		<td align="left">
			<table width="600" border="1" cellspacing="0" cellpadding="0"  >
					<tr>
						<td bgcolor="#B4CDCD" align="center" height="25px">
							<h1><b>(THCAP) ตรวจรับโฉนด-จำนอง-ไถ่ถอน</b><h1>
						</td>
					</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<form id="myform" name="myform" method="post">
				<table width="1150" border="1" cellspacing="0" cellpadding="0"  align="center">	
					<tr>
						<td width="100%">
							<table width="100%" frame="box" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
								<tr><td colspan="13" align="center" bgcolor="#7A8B8B" height="25px" ><font color="white"><b>--- รายการทั้งหมด <?php echo $row2 = pg_num_rows($sql);; ?> รายการ ---</b></font></td></tr>
								<tr bgcolor="#B4CDCD">
									<th><a href='Index.php?sort=numDeed&order=<?php echo $strNewOrder ?>'>เลขที่โฉนด</a></th>
									<th><a href='Index.php?sort=numLand&order=<?php echo $strNewOrder ?>'>เลขที่ดิน</a></th>	
									<th><a href='Index.php?sort=numBook&order=<?php echo $strNewOrder ?>'>เล่มที่</a></th>
									<th>ตำบล/แขวง</a></th>
									<th>อำเภอ/เขต</a></th>
									<th><a href='Index.php?sort=proName&order=<?php echo $strNewOrder ?>'>จังหวัด</a></th>
									<th><a href='Index.php?sort=contractID&order=<?php echo $strNewOrder ?>'>ผูกกับสัญญากู้</a></th>
									<th><a href='Index.php?sort=tc_clType&order=<?php echo $strNewOrder ?>'>รูปแบบการผูก</a></th>
									<th><a href='Index.php?sort=tc_clTypeRef&order=<?php echo $strNewOrder ?>'>เลขอ้างอิง</a></th>
									<th><a href='Index.php?sort=tc_clValue&order=<?php echo $strNewOrder ?>'>จำนวนเงิน</a></th>
									<th><a href='Index.php?sort=numDeed&order=<?php echo $strNewOrder ?>'>ประเภทการอนุมัติ</a></th>
									<th><a href='Index.php?sort=reg_stamp&order=<?php echo $strNewOrder ?>'>วันที่</a></th>
									<th>เลือก</th>
									
								</tr>
			<?php 
			if($row2 != 0){
				$z = 1;
				while($result = pg_fetch_array($sql)){		
								$i++;
								if($i%2==0){
									echo "<tr bgcolor=#D1EEEE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#D1EEEE';\" align=center>";
								}else{
									echo "<tr bgcolor=#E0FFFF onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#E0FFFF';\" align=center>";
								}
								
								list($dis,$aum) = explode("/",$result['district']); // ตำบล/อำเภอ
								if($result['tc_clType'] == '1'){$type = 'ค้ำประกัน';}else{$type = 'จำนอง';} //ประเภท
								if($result['appvreg_status'] != ""){$typeapp = 'รับเข้า';$typeappat = '1';} //ประเภทการอนุมัติ ค้ำประกัน/จำนอง
								if($result['appvunreg_status'] != ""){$typeapp = 'ไถ่ถอน';$typeappat = '2';} //ประเภทการอนุมัติ ไถ่ถอน
								if($result['tc_clValue'] != ""){$value = number_format($result['tc_clValue'],2);}else{ $value = ""; }//จำนวนเงิน กรณี จำนอง
								$conid = $result['contractID'];
								$tcserial_id = $result['tc_clSerial'];

								?> 
									<td><?php echo $result['numDeed']; ?></td>
									<td><?php echo $result['numLand']; ?></td>
									<td><?php echo $result['numBook']; ?></td>
									<td><?php echo $dis; ?></td>
									<td><?php echo $aum; ?></td>
									<td><?php echo $result['proName']; ?></td>
									<td><span onclick="javascript:popU('../../thcap_installments/frm_Index.php?show=1&idno=<?php echo $conid?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"  >
									<u><?php echo $conid; ?></td></u></span>
									<td><?php echo $type; ?></td>
									<td><?php echo $result['tc_clTypeRef']; ?></td>
									<td align="right"><?php echo $value ; ?></td>
									<td><?php echo $typeapp; ?></td>
									<td><?php echo $result['reg_stamp']; ?></td>						
									<td><input type="checkbox" name="chkbox[]" id="idapp<?php echo $z; ?>" value="<?php echo $tcserial_id; ?>"></td>
								</tr>
									<input type="hidden" value="<?php echo $typeappat; ?>" name="typeappat[]"> <!-- ประเภทของการอนุมัติ โยนไปหน้า process เพื่อ ตรวจสอบ-->	
									
					<?php
						$z++;
				}
			
					?>			
							<tr bgcolor="#B4CDCD">
								<td align="right" colspan="13"  height="18px"> 
									
									<input type="button" value="ไม่รับ" >
									<input type="button" value="รับ" style="width:70px;" onclick="app(this.form)">
									<input type="hidden" value="<?php echo $z; ?>" id="chkchoise"> <!--เอาไว้อ้างอิงการวน loop ใน  javascript-->
									<input type="hidden" value="<?php echo $reuser['fullname']; ?>" id="nameuser"> <!-- ชื่อผู้อนุมัติไว้อ้างอิง ใน  javascript-->
									
									
								</td>		
							</tr>
			<?php }else{ echo "<tr><td colspan=\"13\" align=\"center\"><h3>ไม่มีรายการ</h3></td></tr>"; }	?>				
				</table>
			</form>
		</td>
	</tr>
</table>
</body>
<script type="text/javascript">
function app(frm)
{

var nameuser = $("#nameuser").val();
var con = $("#chkchoise").val();
var numchk;
numchk = 0;
	for(var num = 1;num<con;num++){
		if(document.getElementById("idapp"+num).checked){
			numchk+=1;			
		}		
	}
	if(numchk == 0){
		alert("กรุณาเลือกรายการก่อน");
	}else{
		if(confirm('======================================\n\n\t\tข้าพเจ้า\t'+nameuser+'\tขอรับรองว่า\nได้รับโฉนดมารักษาไว้พร้อมทั้งได้ตรวจสอบความถูกต้องครบถ้วนแล้ว\n\n======================================')==true){
			frm.action="process_app.php";
			frm.submit();
			document.myform.submit.disabled='true';
			return true;
		}else{ 
			return false;
		}
	}	
}
</script>


</html>